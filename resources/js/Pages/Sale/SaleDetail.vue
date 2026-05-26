<script setup lang="ts">
import { invoiceTypeI } from '@/Interfaces/SettingInterface';
import { usePage } from '@inertiajs/vue3';
import { ProductTableI } from '@/Interfaces/ProductInterface';
import { computed, inject, ref, watch } from 'vue';
import { saleDataI, SaleTypeEnumI } from '@/Interfaces/SaleInterface';
import { saleKey } from '@/utils/keys';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { getSequenceType } from '@/Global/Helpers';
import { Dialog, FloatLabel, InputText, Select, ToggleButton } from 'primevue';
import FShowProduct from '@/Pages/Products/FShowProduct.vue';
import { PreciseCalculator } from '@/utils/Decimal';
import { Grid2X2Plus, ShoppingCart, Undo2 } from '@lucide/vue';
import { getInfoFromPriceList } from '@/Helpers/ProductHelper';

//Datos de la ventana
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
  (e: 'totalAmount', index: number): void;
  (e: 'totalSale'): void;
}>();

//Formulario
const form = inject(saleKey)!;

//Ventanas
const showProducts = ref(false);
const showSaleOpen = ref(false);
const showReturn = ref(false);
const showFormReturn = ref(false);

// Obtener el tipo de venta
const getSaleType = computed(() => {
  return Object.entries(propsW.saleTypeEnum).map(([key, value]) => {
    return {
      key: key,
      value: value,
      hidden: key === 'Devolucion',
    };
  });
});

// Obtener el tipo de factura
watch(
  () => form.type,
  (newVal) => {
    form.close_table = newVal === 'Cotizacion';
  }
);

/**
 * Verificar el tipo de factura
 */
// const checkInvoiceType = async ()=> {
//
// 	// Verificar si es nota de credito
// 	if (form.invoice_type === 'B04') {
// 		//Resultado de la pregunta
// 		// const result = await Swal.fire({
// 		// 	title: "Desea Colocar Comprobante?",
// 		// 	text: "Registre El Comprobante Del Cliente!",
// 		// 	icon: "question",
// 		// 	showCancelButton: true,
// 		// 	confirmButtonColor: "#3085d6",
// 		// 	cancelButtonColor: "#d33",
// 		// 	confirmButtonText: "Si",
// 		// 	cancelButtonText: "No"
// 		// });
//
// 		//Verificar la accion
// 		// showClientRnc.value = result.isConfirmed;
//
// 	}
// 	// else showClientRnc.value = form.invoice_type !== 'B02';
//
// 	// Solo buscar los datos si es igual a 0 el ID. eso quiere decir que debe generar un comprobante
// 	if (form.id == 0) {
// 		//llamar el tipo de boleta
// 		getSequenceType(form.invoice_type);
// 	}
// }

/**
 * Obtener el producto por codigo
 */
// const getProductCode =()=> {
//
// 	//Verificar que tenga más de 6 caracter
// 	if (form.code_value.length > 0) {
// 		//realizar la busqueda en automatico
// 		axios.get(route('product.get.code', {search: form.code_value}))
// 			.then((res) => {
// 				//Formatear los datos
// 				const product: productFullI = res.data;
// 				//Pasar los datos al metodo
// 				//Limpiar campo y errores en caso de tenerlo
// 			})
// 			.catch(() => {
// 				//Mensjae de que no existe en la base de datos
// 				form.setError('code_value', 'Este Producto no existe en la Base de Datos');
// 			})
// 	}
// }

//Obtener los datos de las cuentas abiertas
const getSaleOpen = (item: saleDataI) => {
  //Colocar la variable en nada al principio
  form.info_sale = [];
  form.uuid = item.uuid;
  form.update = true;

  setTimeout(() => {
    //Verificar Pasar los datos a la variable
    item.info_sale.map((el, index) => {
      form.info_sale.push({ ...el });

      //Calcular el total
      emit('totalAmount', index);
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
      min_price: priceList?.min_price ?? 0,
      special_price: priceList?.promotional_price ?? 0,
      tax_uuid: data.tax.uuid,
      tax_amount: taxForProduct,
      warehouse_uuid: data.default_warehouse,
      tax_rate: parseFloat(PreciseCalculator.divide(data.tax.rate, 100).toString()),
      discount: 0,
      discount_amount: 0,
      reserved: 0,
      amount: data.price,
      is_service: Boolean(data.is_service),
      price_temp: data.price,
    });
  }
//calcular el total de las ventas
  emit('totalSale');
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
      <form class="" v-if="form.invoice_type !== 'B04'">
        <FloatLabel variant="on">
          <InputText />
          <label for="code">Codigo</label>
        </FloatLabel>
      </form>
      <!-- Buscar los datos necesario -->
      <div v-if="!propsW.refund" class="ml-3 flex items-center space-x-3">
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
      <div v-if="page.props.setting.sequence" class="ml-3">
        <FloatLabel variant="on">
          <Select :options="propsW.invoiceType" />
          <label for="type_sale">Tipo Venta</label>
        </FloatLabel>
      </div>

      <!--Tipo de factura-->
      <div class="ml-2 w-40">
        <FloatLabel variant="on">
          <Select
            fluid
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
          :disabled="form.type === 'Cotizacion'"
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
  <!--	<FloatBox-->
  <!--		v-model:show="showSaleOpen">-->
  <!--		<template #header>-->
  <!--			Cuentas Abiertas-->
  <!--		</template>-->
  <!--		<template #body>-->
  <!--			<SaleOpenShow-->
  <!--				@sen-data="getSaleOpen"-->
  <!--				class=" fondo rounded-md px-10 py-5"-->
  <!--				:sale-open="propsW.saleOpen"/>-->
  <!--		</template>-->

  <!--	</FloatBox>-->

  <!-- Formulario para la nota de credito-->
  <!--	<FloatBox-->
  <!--		v-model:show="showFormReturn">-->
  <!--		<template #header>-->
  <!--			Notas de Credito-->
  <!--		</template>-->
  <!--		<template #body>-->
  <!--			<ReturnForm-->
  <!--				class="w-160 mx-auto"-->
  <!--				@closeFormReturn="showFormReturn = false"-->
  <!--				:error="page.props.errors.general"/>-->
  <!--		</template>-->

  <!--	</FloatBox>-->
</template>
