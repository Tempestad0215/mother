<script setup lang="ts">
import { invoiceTypeI } from '@/Interfaces/SettingInterface';
import { usePage } from '@inertiajs/vue3';
import { ProductTableI } from '@/Interfaces/ProductInterface';
import { computed, inject, ref } from 'vue';
import { infoSaleI, saleDataI } from '@/Interfaces/SaleInterface';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { getSequenceType } from '@/Global/Helpers';
import { Button, Card, Dialog, FloatLabel, InputText, Select, ToggleButton } from 'primevue';
import FShowProduct from '@/Pages/Products/FShowProduct.vue';
import { CircleCheckBig, Grid2X2Plus, ShoppingCart, Undo2 } from '@lucide/vue';
import SaleOpenShow from './SaleOpenShow.vue';
import ReturnForm from '@components/ReturnForm.vue';
import { EnumValueI } from '@/Interfaces/GeneralInterface';
import { useSaleSequence } from '@/Composable/Sales/useSaleSequence';
import { saleKey } from '@/Injections/saleKeys';
import { useSaleCart } from '@/Composable/Sales/useSaleCart';
import { useSaleOpen } from '@/Composable/Sales/useSaleOpen';

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

const { getSaleOpen } = useSaleOpen();
const { getProductCode, getDataProduct } = useSaleCart();
const { getNextSequence, loadingNextSequence } = useSaleSequence();

// Ventanas
const showProducts = ref(false);
const showSaleOpen = ref(false);
const showReturn = ref(false);
const showFormReturn = ref(false);
const sendReturnInfo = defineModel('sendReturnInfo', {
  type: Boolean,
  default: false,
});

// Obtener el tipo de venta
const getSaleType = computed(() => {
  return Object.entries(propsW.saleTypes)
    .map(([_, value]) => {
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

// Obtener los datos de las cuentas abiertas

const openReturn = () => {
  showReturn.value = !showReturn.value;
};

const closeFormReturn = (isReturn: boolean) => {
  showFormReturn.value = false;

  if (isReturn) {
    form.type = 'DEVOLUCION';
  }

  sendReturnInfo.value = isReturn;
  form.info_sale.forEach((el) => {
    emit('totalAmount', el);
  });
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
      <form v-if="!refund" class="w-full sm:w-60" @submit.prevent="getProductCode()">
        <FloatLabel variant="on" class="w-full">
          <InputText v-model="form.code_value" @blur="getProductCode()" class="w-full" />
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
          @click="showProducts = true"
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
        searchRoute="sale.index"
        :searchOnly="['products']"
        @select-data="getDataProduct($event)"
        :stock="true"
        :isProduct="false"
        :products="propsW.products"
      >
        <template #actions="{ data }: { data: ProductTableI }">
          <Button @click="" outlined>
            <template #icon> <CircleCheckBig /></template>
          </Button>
        </template>
      </FShowProduct>
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
