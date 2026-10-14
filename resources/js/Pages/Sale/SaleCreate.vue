<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import { onMounted, onUpdated, provide, Ref, ref, watch } from 'vue';
import { ProductTableI } from '@/Interfaces/ProductInterface';
import { printPdf } from '@/Global/Helpers';
import { clientBaseI } from '@/Interfaces/ClientInterface';
import {
  CreateSaleI,
  infoSaleI,
  saleDataI,
  SaleTypeEnum,
  SaleTypeEnumI,
  WarehouseMapType,
} from '@/Interfaces/SaleInterface';
import { invoiceTypeI } from '@/Interfaces/SettingInterface';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import SaleInfo from '@/Pages/Sale/SaleInfo.vue';
import SaleDetail from '@/Pages/Sale/SaleDetail.vue';
import SaleFooter from '@/Pages/Sale/SaleFooter.vue';
import SaleTable from '@/Pages/Sale/SaleTable.vue';
import PaymentInvoice from '@components/PaymentInvoice.vue';
import { Button, Card, Dialog, Divider } from 'primevue';
import { CreditNoteBalance } from '@/Interfaces/CreditNoteInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import { SaleBreadCrumbs } from '@/Helpers/SaleHelper';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';
import { CreditCard, Eraser, Send } from '@lucide/vue';
import { EnumValueI } from '@/Interfaces/GeneralInterface';
import { saleKey } from '@/Injections/saleKeys';
import { useSaleSubmit } from '@/Composable/Sales/useSaleSubmit';
import { useSaleSequence } from '@/Composable/Sales/useSaleSequence';
import { useSaleCreditNote } from '@/Composable/Sales/useSaleCreditNote';

const { createCreditNotes } = useSaleCreditNote({
  onSuccess: () => (paymentBox.value = false),
});
const { sendData } = useSaleSubmit({
  createCreditNotes,
});

// Datos del back end
const page = usePage();

// Datos del back end
const propsW = defineProps<{
  products: PaginationI<ProductTableI>;
  clients: PaginationI<clientBaseI>;
  saleOpen: PaginationI<saleDataI>;
  invoiceType: invoiceTypeI[];
  saleInfo?: saleDataI;
  pdfUuid?: string;
  saleTypes: EnumValueI[];
  saleTypeEnum: SaleTypeEnumI;
  warehouses: WarehouseMapType;
}>();

// Ventanas
const showReturn: Ref<boolean> = ref(false);
const showFormReturn: Ref<boolean> = ref(false);
const paymentBox = ref(false);
const saleInfoRef = ref<InstanceType<typeof SaleInfo>>()!;
const saleTableRef = ref<InstanceType<typeof SaleTable>>()!;
const salePaymentRef = ref<InstanceType<typeof PaymentInvoice>>()!;
const returnInfo = ref(false);
const client = ref('');

// Formulario
const form = useForm<CreateSaleI>({
  uuid: '',
  code_value: '',
  ncf: '',
  ncf_m: '',
  client_name: '',
  client_uuid: '',
  client_rnc: '',
  client_rnc_status: '',
  client_social: '',
  info_sale: [] as infoSaleI[],
  tax: 0,
  discount_amount: 0,
  amount: 0,
  sub_total: 0,
  comment: '',
  comment_uuid: '',
  close_table: false,
  received: 0,
  returned: 0,
  general: '',
  type: 'VENTAS' as SaleTypeEnum,
  type_payment: 'CONTADO',
  update: false,
  sequence: '',
  sequence_type: '',
  invoice_type: 'B02',
  credit_notes_value: '',
  credit_notes: [] as CreditNoteBalance[],
  credit_notes_amount: 0,
  pending: 0,
});

const { getSequence } = useSaleSequence();

// Al momento de cargar el componente
onMounted(() => {
  setDataForm();
  if (page.props.setting.sequence) getSequence(form.invoice_type);

  let msjError = 'Este Codigo No es Validos, Introduzca Uno Validado';
  if (page.props.errors.general === msjError) {
    showFormReturn.value = true;
  }

  if (propsW.saleInfo && Object.keys(propsW.saleInfo).length > 0) {
    const data = propsW.saleInfo;
    client.value = data.client_name;
    form.client_name = data.client_name;
    form.info_sale = data.info_sale;
    form.tax = data.tax;
    form.sub_total = data.sub_total;
    form.discount_amount = data.discount_amount;
    form.amount = data.amount;
    form.comment = data.comment;
  }
});

// Obtener los datos de las cuentas abiertas
onUpdated(() => {
  setTimeout(() => {
    if (page.props.setting.sequence) getSequence(form.invoice_type);
  }, 200);

  let msjError = 'Este Codigo No es Validos, Introduzca Uno Validado';
  if (page.props.errors.general === msjError) {
    showFormReturn.value = true;
  }

  setDataForm();
});

// Obtener los datos de las cuentas abiertas
watch(
  () => page.flash,
  (newValue) => {
    if (newValue && 'saleInvoiceUrl' in page.flash) {
      const url = page.flash.saleInvoiceUrl as string;
      printPdf(url);
    }
  }
);

watch(
  () => form.credit_notes,
  (_) => {
    form.credit_notes_amount = 0;

    form.credit_notes.forEach((item) => {
      form.credit_notes_amount = parseFloat(
        PreciseCalculator.add(form.credit_notes_amount, item.n_available || 0).toString()
      );
    });

    const result = parseFloat(
      PreciseCalculator.subtract(form.pending, form.credit_notes_amount).toString()
    );

    if (result <= 0.0) {
      form.pending = 0;
      form.returned = 0;
    } else {
      form.pending = result;
    }
  },
  {
    deep: true,
  }
);

const setDataForm = () => {
  if (form.type === 'DEVOLUCION' && propsW.saleInfo) {
    form.uuid = propsW.saleInfo.uuid;
    form.ncf_m = propsW.saleInfo.ncf;
    form.client_name = propsW.saleInfo.client_name;
    form.client_uuid = propsW.saleInfo.client_uuid;
    form.client_rnc = propsW.saleInfo.client_rnc;
    form.info_sale = propsW.saleInfo.info_sale;
    form.invoice_type = page.props.setting.sequence ? 'B04' : '';
    form.type = 'DEVOLUCION';
  }
};

const registerSale = () => {
  if (form.type === 'Cotizacion' || !form.close_table || form.type === 'DEVOLUCION') {
    sendData();
  } else {
    paymentBox.value = true;
    salePaymentRef.value?.checkSale();
  }
};

const cleanAllForm = () => {
  form.reset();
};

provide(saleKey, form);
</script>

<template>
  <AppLayout>
    <div class="w-full px-2 sm:px-4 py-4 max-w-7xl mx-auto">
      <Card class="shadow-sm rounded-lg border border-slate-200">
        <template #header>
          <div class="p-3 pb-0">
            <BreadCrumbComponent :item-options="SaleBreadCrumbs" />
          </div>
          <Divider class="my-2" />
        </template>

        <template #content>
          <form @submit.prevent class="space-y-4">
            <!-- Información del Cliente y Facturación -->
            <SaleInfo ref="saleInfoRef" :clients="propsW.clients" />

            <!-- Búsqueda y Selección de Productos -->
            <SaleDetail
              v-model:send-return-info="returnInfo"
              :saleTypes="propsW.saleTypes"
              ref="saleDetailRef"
              :products="propsW.products"
              :sale-open="propsW.saleOpen"
              :invoice-type="propsW.invoiceType"
              :refund="form.type === 'DEVOLUCION'"
              @total-sale=""
              @total-amount="saleTableRef?.calculateItemRow($event)"
              @totalSale="saleTableRef?.calculateTotals()"
            />

            <!-- Tabla del Carrito/Detalle de Venta -->
            <div class="pt-2">
              <SaleTable :warehouses="propsW.warehouses" ref="saleTableRef" />
            </div>

            <!-- Resumen de Totales y Descuentos -->
            <SaleFooter ref="saleFooterRef" />

            <!-- Botones de Acción Adaptativos -->
            <div class="pt-4 flex flex-col-reverse sm:flex-row justify-end gap-3">
              <Button
                @click="cleanAllForm"
                severity="warn"
                label="Limpiar Formulario"
                type="button"
                class="w-full sm:w-auto h-11 px-5"
                outlined
              >
                <template #icon>
                  <Eraser class="w-4 h-4 mr-1" />
                </template>
              </Button>

              <Button
                @click="registerSale"
                type="button"
                :label="form.close_table ? 'Procesar Pago' : 'Registrar Venta'"
                class="w-full sm:w-auto h-11 px-6 bg-emerald-600 hover:bg-emerald-700 border-none font-semibold text-base"
              >
                <template #icon>
                  <CreditCard v-if="form.close_table" class="w-5 h-5 mr-1" />
                  <Send v-else class="w-4 h-4 mr-1" />
                </template>
              </Button>
            </div>
          </form>
        </template>
      </Card>
    </div>

    <!-- Modal Ventana de Pago -->
    <Dialog
      v-model:visible="paymentBox"
      header="Ventana de Pago"
      modal
      dismissableMask
      :breakpoints="{ '960px': '75vw', '641px': '95vw' }"
      :style="{ width: '50vw' }"
      class="p-dialog-responsive mx-2 sm:mx-0"
    >
      <div class="py-2">
        <PaymentInvoice @senData="sendData" v-model:show-return="showReturn" ref="salePaymentRef" />
      </div>
    </Dialog>
  </AppLayout>
</template>
