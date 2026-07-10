<script setup lang="ts">
import { invoiceTypeI, sequenceDataI } from '@/Interfaces/SettingInterface';
import { usePage } from '@inertiajs/vue3';
import { ProductTableI } from '@/Interfaces/ProductInterface';
import { computed, inject, ref } from 'vue';
import { infoSaleI, saleDataI, SaleTypeEnumI } from '@/Interfaces/SaleInterface';
import { saleKey } from '@/utils/keys';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { createSequence, getSequenceType } from '@/Global/Helpers';
import {
  Card,
  Dialog,
  FloatLabel,
  InputText,
  Select,
  SelectChangeEvent,
  ToggleButton,
  useToast,
} from 'primevue';
import FShowProduct from '@/Pages/Products/FShowProduct.vue';
import { PreciseCalculator } from '@/utils/Decimal';
import { Grid2X2Plus, ShoppingCart, Undo2 } from '@lucide/vue';
import { getInfoFromPriceList } from '@/Helpers/ProductHelper';
import SaleOpenShow from './SaleOpenShow.vue';
import ReturnForm from '@components/ReturnForm.vue';
import axios from 'axios';

//Datos de la ventana
const toast = useToast();
const page = usePage();
//Datos del back end
const propsW = defineProps<{
  invoiceType: invoiceTypeI[];
  refund?: boolean;
  saleOpen: PaginationI<saleDataI>;
  products: PaginationI<ProductTableI>;
  saleTypeEnum: SaleTypeEnumI;
}>();

//Emitir eventos para el componente de devoluciones
const emit = defineEmits<{
  (e: 'retunedBlur'): void;
  (e: 'totalAmount', info: infoSaleI): void;
  (e: 'totalSale'): void;
  (e: 'sendClientName', name: string): void;
}>();

//Formulario
const form = inject(saleKey)!;

//Ventanas
const showProducts = ref(false);
const showSaleOpen = ref(false);
const showReturn = ref(false);
const showFormReturn = ref(false);
const loadingNextSequence = ref(false);
const sendReturnInfo = defineModel('sendReturnInfo', {
  type: Boolean,
  default: false,
});

// Obtener el tipo de venta
const getSaleType = computed(() => {
  return Object.entries(propsW.saleTypeEnum).map(([key, value]) => {
    let shouldHide: boolean;

    if (form.type === 'Ventas' || form.type === 'Cotizacion') {
      shouldHide = key === 'Devolucion';
    } else {
      shouldHide = key !== 'Devolucion';
    }

    return {
      key: key,
      value: value,
      hidden: shouldHide,
    };
  });
});

const getSequenceFiltered = computed(() => {
  const invoiceTypeSelected = form.invoice_type; // El tipo seleccionado en el formulario

  return Object.entries(propsW.invoiceType).map(([_, value]) => {
    // Definimos si este elemento específico es una Nota de Crédito
    const isNotaCredito = value.type === 'B04' || value.name?.includes('B04');

    // REGLA: Si este elemento es B04, lo ocultamos SOLO cuando el seleccionado NO sea B04
    let shouldHide = false;
    if (isNotaCredito) {
      shouldHide = invoiceTypeSelected !== 'B04';
    }

    return {
      key: value.name,
      value: value.type,
      hidden: shouldHide,
    };
  });
});

/**
 * Verificar el tipo de factura
 */
// const checkInvoiceType = async () => {
//   // Verificar si es nota de credito
//   if (form.invoice_type === 'B04') {
//     //Resultado de la pregunta
//     // const result = await Swal.fire({
//     // 	title: "Desea Colocar Comprobante?",
//     // 	text: "Registre El Comprobante Del Cliente!",
//     // 	icon: "question",
//     // 	showCancelButton: true,
//     // 	confirmButtonColor: "#3085d6",
//     // 	cancelButtonColor: "#d33",
//     // 	confirmButtonText: "Si",
//     // 	cancelButtonText: "No"
//     // });
//     //Verificar la accion
//     // showClientRnc.value = result.isConfirmed;
//   }
//   // else showClientRnc.value = form.invoice_type !== 'B02';
//
//   // Solo buscar los datos si es igual a 0 el ID. eso quiere decir que debe generar un comprobante
//   if (form.uuid == '') {
//     //llamar el tipo de boleta
//     getSequenceType(form.invoice_type);
//   }
// };

/**
 * Obtener el producto por codigo
 */
const getProductCode = () => {
  //Verificar que tenga más de 6 caracter
  if (form.code_value.length > 0) {
    //realizar la busqueda en automatico
    axios
      .get(route('product.get.code', { search: form.code_value }))
      .then((res) => {
        //Formatear los datos

        const info = res.data as ProductTableI;

        const getIndex = form.info_sale.findIndex((el) => el.product_uuid === info.uuid);
        if (getIndex >= 0) {
          const infoCurrent = form.info_sale[getIndex];
          infoCurrent.stock += 1.0;
          emit('totalAmount', infoCurrent);
          toast.add({
            severity: 'success',
            summary: `Producto: ${infoCurrent.product_name}`,
            detail: `Se Agrego ${infoCurrent.stock} Productos`,
            life: 3000,
          });
        } else {
          const taxRate = PreciseCalculator.divide(info.tax.rate, 100) ?? 0;
          const taxAmount = PreciseCalculator.multiply(taxRate.toString(), info.price);

          //
          form.info_sale.push({
            amount: info.price,
            price: info.price,
            price_type: 'price',
            product_name: info.name,
            product_uuid: info.uuid,
            discount: 0,
            discount_amount: 0,
            reserved: 0,
            stock: 1,
            tax_amount: parseFloat(taxAmount.toString()),
            tax_rate: parseFloat(taxRate.toString()),
            tax_uuid: info.tax_uuid,
            temp_price: 0,
            warehouse_uuid: info.default_warehouse,
          });
        }
        //Limpiar campo y errores en caso de tenerlo
        form.code_value = '';
      })
      .catch(() => {
        //Mensjae de que no existe en la base de datos
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `El Codigo: ${form.code_value} No Existe o No Tiene Stock!`,
          life: 3000,
        });
      });
  }
};

//Obtener los datos de las cuentas abiertas
const getSaleOpen = (item: saleDataI) => {
  //Colocar la variable en nada al principio
  form.info_sale = [];
  form.uuid = item.uuid;
  form.update = true;

  setTimeout(() => {
    //Verificar Pasar los datos a la variable
    item.info_sale.map((el, _) => {
      form.info_sale.push({
        ...el,
        price_type: el.price_type ?? 'price',
        temp_price: el.price,
        tax_amount: parseFloat(PreciseCalculator.multiply(el.price, el.tax_rate).toFixed(2)),
      });

      //Calcular el total
      emit('totalAmount', el);
    });
  }, 2);

  //calcular el total de las ventas
  emit('totalSale');

  //colocar los datos en el formulario
  form.client_uuid = item.client_uuid;
  form.client_rnc = item.client_document ?? '';
  form.ncf = item.ncf;
  form.invoice_type = item.invoice_type;
  form.client_name = item.client_name;
  form.close_table = item.close_table;
  form.comment = item.comment ?? '';

  //Cerra la ventana
  showSaleOpen.value = false;
};

// Abrir el formulario para las devoluciones
const openReturn = () => {
  showReturn.value = !showReturn.value;
};

// Abrir el formulario para las devoluciones
const getDataProduct = (data: ProductTableI) => {
  showProducts.value = false;
  const getIndex = form.info_sale.findIndex((el) => el.product_uuid === data.uuid);

  if (getIndex >= 0) {
    form.info_sale[getIndex].stock += 1.0;
  } else {
    const taxRate = PreciseCalculator.divide(data.tax.rate, 100) ?? 0;

    const taxPlus = Number(PreciseCalculator.multiply(taxRate.toString(), data.price));

    let taxForProduct: number;

    if (taxPlus === 0) {
      taxForProduct = 0;
    } else {
      taxForProduct = Number(PreciseCalculator.multiply(taxPlus, 1));
    }

    // Tomar la info de la price list
    const priceList = getInfoFromPriceList(data.price_lists, data.default_price_list);

    form.info_sale.push({
      product_uuid: data.uuid,
      product_name: data.name,
      stock: 1,
      price: priceList?.price ?? 0,
      price_type: 'price',
      min_price: priceList?.min_price ?? 0,
      promotional_price: priceList?.promotional_price ?? 0,
      tax_uuid: data.tax.uuid,
      tax_amount: taxForProduct,
      warehouse_uuid: data.default_warehouse,
      tax_rate: parseFloat(PreciseCalculator.divide(data.tax.rate, 100).toString()),
      discount: 0,
      discount_amount: 0,
      reserved: 0,
      amount: data.price,
      is_service: Boolean(data.is_service),
      temp_price: data.price,
    });
  }
  //calcular el total de las ventas
  emit('totalSale');
};

// Cerrar el formulario de devoluciones
const closeFormReturn = (isReturn: boolean) => {
  // Colocar la variable en nada al principio
  showFormReturn.value = false;

  // Colocar la variable de devolucion para mostrar o no los datos de la venta
  // Enviar el evento para mostrar o no los datos de la venta
  if (isReturn) {
    form.type = 'Devolucion';
  }

  sendReturnInfo.value = isReturn;
  // Obtener los datos de las cuentas abiertas
  form.info_sale.forEach((el) => {
    emit('totalAmount', el);
  });
};

const getNextSequence = async (event: SelectChangeEvent) => {
  loadingNextSequence.value = true;
  const info = event.value as string;
  try {
    const res = await axios.get(route('sequence.get', { type: info }));

    const data = res.data as sequenceDataI;

    const restante = parseFloat(PreciseCalculator.subtract(data.to, data.next).toString());

    if (restante <= data.advise) {
      toast.add({
        severity: 'warn',
        summary: 'Advertencia',
        detail: `El numero de factura se encuentra a ${restante} de los ${data.advise} disponibles!`,
        life: 3000,
      });
    }

    form.ncf = createSequence(data.type, data.next);
  } catch (err) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'No he posible obtener el siguiente numero de factura!',
      life: 3000,
    });
  } finally {
    loadingNextSequence.value = false;
  }
};

// Exponer los datos para el componente de devoluciones
defineExpose({
  showReturn,
  getSequenceType,
  openReturn,
});
</script>

<template>
  <!-- Datos del formulario-->
  <div class="flex justify-between items-center mt-3">
    <div class="flex mt-2">
      <form class="" v-if="!refund">
        <FloatLabel variant="on">
          <InputText v-model="form.code_value" @blur="getProductCode" />
          <label for="code">Codigo</label>
        </FloatLabel>
      </form>
      <!-- Buscar los datos necesario -->
      <div v-if="!refund" class="ml-3 flex items-center space-x-3">
        <ShoppingCart
          v-tooltip.bottom="'Productos Disponibles'"
          @click="showProducts = !showProducts"
          class="hover:scale-125 duration-300"
          :size="30"
        />
        <Grid2X2Plus
          v-tooltip.bottom="'Cuentas Abiertas'"
          @click="showSaleOpen = !showSaleOpen"
          class="hover:scale-125 duration-300"
          :size="30"
        />
        <Undo2
          v-tooltip.bottom="'Devoluciones'"
          @click="showFormReturn = !showFormReturn"
          class="hover:scale-125 duration-300"
          :size="30"
        />
      </div>
    </div>

    <div class="flex">
      <!--Tipo de factura-->
      <div v-if="page.props.setting.sequence" class="ml-3 w-40">
        <FloatLabel variant="on">
          <Select
            fluid
            option-label="key"
            option-value="value"
            @change="getNextSequence"
            :loading="loadingNextSequence"
            v-model="form.invoice_type"
            :option-disabled="(data) => data.hidden"
            :options="getSequenceFiltered"
          />
          <label for="type_sale">Tipo Venta</label>
        </FloatLabel>
      </div>

      <!--Tipo de factura-->
      <div class="ml-2 w-40">
        <FloatLabel variant="on">
          <Select
            fluid
            :disabled="refund"
            v-model="form.type"
            option-value="value"
            option-label="key"
            :option-disabled="(data) => data.hidden"
            :options="getSaleType"
          />
          <label for="type_sale">Tipo Venta</label>
        </FloatLabel>
      </div>
      <!--Tipo de cuenta si abierta o cerrada-->
      <div v-if="!propsW.refund" class="ml-2">
        <ToggleButton
          :disabled="form.type === 'Cotizacion' || refund"
          v-model="form.close_table"
          on-label="Cuenta Cerrada"
          off-label="Cuenta Abierta"
        />
      </div>
    </div>
  </div>

  <Dialog class="w-300" header="Productos" v-model:visible="showProducts" modal>
    <FShowProduct
      @select-data="getDataProduct"
      :stock="true"
      :isProduct="false"
      :products="propsW.products"
    />
  </Dialog>

  <!-- Vetana de las ordenes abierta -->
  <Dialog header="Cuentas Abiertas" modal v-model:visible="showSaleOpen">
    <Card>
      <template #content>
        <SaleOpenShow
          @sen-data="getSaleOpen"
          class="fondo rounded-md px-10 py-5"
          :sale-open="propsW.saleOpen"
        />
      </template>
    </Card>
  </Dialog>

  <!-- Formulario para la nota de credito-->
  <Dialog v-model:visible="showFormReturn" header="Nota de Creditos / Devolucion">
    <ReturnForm
      class="w-160 mx-auto"
      @sendClientName="emit('sendClientName', $event)"
      @closeFormReturn="closeFormReturn"
      :error="page.props.errors.general"
    />
  </Dialog>
</template>
