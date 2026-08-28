<script setup lang="ts">
import { invoiceTypeI, sequenceDataI } from '@/Interfaces/SettingInterface';
import { usePage } from '@inertiajs/vue3';
import { ProductTableI } from '@/Interfaces/ProductInterface';
import { computed, inject, ref } from 'vue';
import { infoSaleI, saleDataI } from '@/Interfaces/SaleInterface';
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
import { EnumValueI } from '@/Interfaces/GeneralInterface';

// Datos de la ventana
const toast = useToast();
const page = usePage();

// Datos del back end
const propsW = defineProps<{
  invoiceType: invoiceTypeI[];
  refund?: boolean;
  saleOpen: PaginationI<saleDataI>;
  products: PaginationI<ProductTableI>;
  saleTypes: EnumValueI[];
}>();

// Emitir eventos para el componente de devoluciones
const emit = defineEmits<{
  (e: 'retunedBlur'): void;
  (e: 'totalAmount', info: infoSaleI): void;
  (e: 'totalSale'): void;
  (e: 'sendClientName', name: string): void;
}>();

// Formulario
const form = inject(saleKey)!;

// Ventanas
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
  return Object.entries(propsW.saleTypes)
    .map(([key, value]) => {
      let shouldHide: boolean;
      const item: EnumValueI = value as EnumValueI;

      if (form.type === 'VENTAS' || form.type === 'COTIZACION') {
        shouldHide = item.label === 'DEVOLUCION';
      } else {
        shouldHide = item.label !== 'DEVOLUCION';
      }

      return {
        key: item.label,
        value: item.value,
        hidden: shouldHide,
      };
    })
    .filter((option) => option.value !== 'TODO');
});

const getSequenceFiltered = computed(() => {
  const invoiceTypeSelected = form.invoice_type;

  return Object.entries(propsW.invoiceType).map(([_, value]) => {
    const isNotaCredito = value.type === 'B04' || value.name?.includes('B04');
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
 * Obtener el producto por código
 */
const getProductCode = () => {
  if (form.code_value.length > 0) {
    axios
      .get(route('product.get.code', { search: form.code_value }))
      .then((res) => {
        const info = res.data as ProductTableI;
        const getIndex = form.info_sale.findIndex((el) => el.product_uuid === info.uuid);

        if (getIndex >= 0) {
          const infoCurrent = form.info_sale[getIndex];
          infoCurrent.stock += 1.0;
          emit('totalAmount', infoCurrent);
          toast.add({
            severity: 'success',
            summary: `Producto: ${infoCurrent.product_name}`,
            detail: `Se agregó ${infoCurrent.stock} unidad(es)`,
            life: 3000,
          });
        } else {
          const taxRate = PreciseCalculator.divide(info.tax.rate, 100) ?? 0;
          const taxAmount = PreciseCalculator.multiply(taxRate.toString(), info.price);

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
        form.code_value = '';
      })
      .catch(() => {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `El código: ${form.code_value} no existe o no tiene stock.`,
          life: 3000,
        });
      });
  }
};

// Obtener los datos de las cuentas abiertas
const getSaleOpen = (item: saleDataI) => {
  form.info_sale = [];
  form.uuid = item.uuid;
  form.update = true;

  setTimeout(() => {
    item.info_sale.map((el, _) => {
      form.info_sale.push({
        ...el,
        price_type: el.price_type ?? 'price',
        temp_price: el.price,
        tax_amount: parseFloat(PreciseCalculator.multiply(el.price, el.tax_rate).toFixed(2)),
      });

      emit('totalAmount', el);
    });
  }, 2);

  emit('totalSale');

  form.client_uuid = item.client_uuid;
  form.client_rnc = item.client_document ?? '';
  form.ncf = item.ncf;
  form.invoice_type = item.invoice_type;
  form.client_name = item.client_name;
  form.close_table = item.close_table;
  form.comment = item.comment ?? '';

  showSaleOpen.value = false;
};

const openReturn = () => {
  showReturn.value = !showReturn.value;
};

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
  emit('totalSale');
};

const closeFormReturn = (isReturn: boolean) => {
  showFormReturn.value = false;

  if (isReturn) {
    form.type = 'Devolucion';
  }

  sendReturnInfo.value = isReturn;
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
        detail: `El número de factura se encuentra a ${restante} de los ${data.advise} disponibles.`,
        life: 3000,
      });
    }

    form.ncf = createSequence(data.type, data.next);
  } catch (err) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'No fue posible obtener el siguiente número de factura.',
      life: 3000,
    });
  } finally {
    loadingNextSequence.value = false;
  }
};

defineExpose({
  showReturn,
  getSequenceType,
  openReturn,
});
</script>

<template>
  <div class="flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-4 mt-3">
    <!-- Entrada de Código de Barras y Accesos Rápidos -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
      <form v-if="!refund" class="w-full sm:w-60" @submit.prevent>
        <FloatLabel variant="on" class="w-full">
          <InputText v-model="form.code_value" @blur="getProductCode" class="w-full" />
          <label for="code">Código de Barra</label>
        </FloatLabel>
      </form>

      <!-- Botones de Acción Rápida -->
      <div
        v-if="!refund"
        class="flex items-center justify-around sm:justify-start gap-4 bg-slate-50 p-2 rounded-lg border border-slate-200"
      >
        <ShoppingCart
          v-tooltip.bottom="'Productos Disponibles'"
          @click="showProducts = !showProducts"
          class="cursor-pointer hover:scale-110 transition text-slate-700 hover:text-emerald-600"
          :size="26"
        />
        <Grid2X2Plus
          v-tooltip.bottom="'Cuentas Abiertas'"
          @click="showSaleOpen = !showSaleOpen"
          class="cursor-pointer hover:scale-110 transition text-slate-700 hover:text-blue-600"
          :size="26"
        />
        <Undo2
          v-tooltip.bottom="'Devoluciones'"
          @click="showFormReturn = !showFormReturn"
          class="cursor-pointer hover:scale-110 transition text-slate-700 hover:text-amber-600"
          :size="26"
        />
      </div>
    </div>

    <!-- Opciones de Facturación (Comprobantes / Tipo Venta / Estado) -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
      <!-- Tipo de Comprobante Fiscal (NCF) -->
      <div v-if="page.props.setting.sequence" class="w-full sm:w-44">
        <FloatLabel variant="on" class="w-full">
          <Select
            fluid
            option-label="key"
            option-value="value"
            @change="getNextSequence"
            :loading="loadingNextSequence"
            v-model="form.invoice_type"
            :option-disabled="(data) => data.hidden"
            :options="getSequenceFiltered"
            class="w-full"
          />
          <label for="invoice_type">Comprobante</label>
        </FloatLabel>
      </div>

      <!-- Tipo de Operación/Venta -->
      <div class="w-full sm:w-40">
        <FloatLabel variant="on" class="w-full">
          <Select
            fluid
            :disabled="refund"
            v-model="form.type"
            option-value="value"
            option-label="key"
            :option-disabled="(data) => data.hidden"
            :options="getSaleType"
            class="w-full"
          />
          <label for="type_sale">Tipo Venta</label>
        </FloatLabel>
      </div>

      <!-- Conmutador Cuenta Abierta / Cerrada -->
      <div v-if="!propsW.refund" class="w-full sm:w-auto">
        <ToggleButton
          :disabled="form.type === 'Cotizacion' || refund"
          v-model="form.close_table"
          on-label="Cuenta Cerrada"
          off-label="Cuenta Abierta"
          class="w-full sm:w-auto h-10 font-semibold"
        />
      </div>
    </div>
  </div>

  <!-- Diálogo Catálogo de Productos -->
  <Dialog
    v-model:visible="showProducts"
    modal
    dismissableMask
    header="Catálogo de Productos"
    :breakpoints="{ '960px': '85vw', '641px': '95vw' }"
    :style="{ width: '65vw' }"
    class="p-dialog-responsive mx-2 sm:mx-0"
  >
    <div class="py-2">
      <FShowProduct
        @select-data="getDataProduct"
        :stock="true"
        :isProduct="false"
        :products="propsW.products"
      />
    </div>
  </Dialog>

  <!-- Diálogo Cuentas Abiertas -->
  <Dialog
    v-model:visible="showSaleOpen"
    modal
    dismissableMask
    header="Cuentas Abiertas"
    :breakpoints="{ '960px': '85vw', '641px': '95vw' }"
    :style="{ width: '50vw' }"
    class="p-dialog-responsive mx-2 sm:mx-0"
  >
    <div class="py-2">
      <Card class="border-none shadow-none">
        <template #content>
          <SaleOpenShow
            @sen-data="getSaleOpen"
            class="rounded-md p-2 sm:p-4"
            :sale-open="propsW.saleOpen"
          />
        </template>
      </Card>
    </div>
  </Dialog>

  <!-- Diálogo Nota de Crédito / Devoluciones -->
  <Dialog
    v-model:visible="showFormReturn"
    modal
    dismissableMask
    header="Nota de Crédito / Devolución"
    :breakpoints="{ '960px': '85vw', '641px': '95vw' }"
    :style="{ width: '45vw' }"
    class="p-dialog-responsive mx-2 sm:mx-0"
  >
    <div class="py-2">
      <ReturnForm
        class="w-full mx-auto"
        @sendClientName="emit('sendClientName', $event)"
        @closeFormReturn="closeFormReturn"
        :error="page.props.errors.general"
      />
    </div>
  </Dialog>
</template>

<style scoped>
:deep(.p-dialog-content) {
  padding: 1rem;
  max-height: 80vh;
  overflow-y: auto;
}

@media (max-width: 640px) {
  :deep(.p-dialog-content) {
    padding: 0.75rem;
    max-height: 85vh;
  }
}
</style>
