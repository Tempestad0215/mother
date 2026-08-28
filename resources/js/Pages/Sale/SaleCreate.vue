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
import { saleKey } from '@/utils/keys';
import SaleFooter from '@/Pages/Sale/SaleFooter.vue';
import SaleTable from '@/Pages/Sale/SaleTable.vue';
import PaymentInvoice from '@components/PaymentInvoice.vue';
import { useRoute } from 'ziggy-js';
import { Button, Card, Dialog, Divider, useToast } from 'primevue';
import { CreditNoteBalance } from '@/Interfaces/CreditNoteInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import { SaleBreadCrumbs } from '@/Helpers/SaleHelper';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';
import { CreditCard, Eraser, Send } from '@lucide/vue';
import { EnumValueI } from '@/Interfaces/GeneralInterface';

// Datos de la ventana
const toast = useToast();
const route = useRoute();

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
const saleDetailRef = ref<InstanceType<typeof SaleDetail>>()!;
const saleTableRef = ref<InstanceType<typeof SaleTable>>()!;
const saleFooterRef = ref<InstanceType<typeof SaleFooter>>()!;
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

// Al momento de cargar el componente
onMounted(() => {
  setDataForm();
  if (page.props.setting.sequence) saleInfoRef.value?.getSequence(form.invoice_type);

  let msjError = 'Este Codigo No es Validos, Introduzca Uno Validado';
  if (page.props.errors.general === msjError) {
    showFormReturn.value = true;
  }
});

// Obtener los datos de las cuentas abiertas
onUpdated(() => {
  setTimeout(() => {
    if (page.props.setting.sequence) saleInfoRef.value?.getSequence(form.invoice_type);
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

const createCreditNotes = () => {
  form.post(route('credit-note.store', { sale: form.uuid }), {
    onSuccess: () => {
      toast.add({
        summary: 'Registro Creado',
        detail: 'Nota de crédito creada correctamente.',
        severity: 'success',
        life: 3000,
      });
      printPdf(route('invoice.credit-note', { creditNote: page.flash.credit_uuid }));
      form.reset();
      paymentBox.value = false;
      saleInfoRef.value?.resetData();
    },
    onError: (err) => {
      const errors = Object.values(err);
      toast.add({
        summary: 'Error',
        detail: errors[0],
        life: 3500,
        severity: 'error',
      });
    },
  });
};

const sendData = async () => {
  if (form.pending > 0) {
    toast.add({
      summary: 'Advertencia',
      detail: 'Por favor, verifique el monto pendiente antes de continuar.',
      severity: 'warn',
      life: 3500,
    });
  } else {
    if (form.type === 'DEVOLUCION' && form.info_sale.length > 0) {
      createCreditNotes();
    } else {
      if (form.update) {
        await updateSale();
      } else {
        createSale();
      }
    }
  }
};

const createSale = () => {
  form.post(route('sale.store'), {
    onSuccess: () => {
      toast.add({
        summary: 'Registro Creado',
        detail: 'Venta procesada correctamente.',
        severity: 'success',
        life: 3000,
      });
      form.reset();
      paymentBox.value = false;
      saleInfoRef.value?.resetData();
    },
    onError: (err) => {
      const errors = Object.values(err);
      toast.add({
        summary: 'Error',
        detail: errors[0],
        life: 3500,
        severity: 'error',
      });
    },
  });
};

const updateSale = async () => {
  form.patch(route('sale.update', { sale: form.uuid }), {
    onSuccess: () => {
      toast.add({
        summary: 'Registro Actualizado',
        detail: 'Venta actualizada correctamente.',
        severity: 'success',
        life: 3000,
      });
      form.reset();
      paymentBox.value = false;
      saleInfoRef.value?.resetData();
    },
    onError: (err) => {
      const errors = Object.values(err);
      toast.add({
        summary: 'Error',
        detail: errors[0],
        life: 3500,
        severity: 'error',
      });
    },
  });
};

const registerSale = () => {
  if (form.type === 'Cotizacion' || !form.close_table || form.type === 'DEVOLUCION') {
    sendData();
  } else {
    paymentBox.value = true;
    salePaymentRef.value?.checkSale();
  }
};

const getInfoCreditNote = (data: CreditNoteBalance): void => {
  const exist = form.credit_notes.find((item) => item.uuid === data.uuid);

  if (exist)
    return toast.add({
      summary: 'Error',
      detail: 'Esta nota de crédito ya fue registrada.',
      severity: 'error',
      life: 3500,
    });

  if (form.credit_notes_amount >= form.amount) {
    return toast.add({
      summary: 'Advertencia',
      detail: 'El monto de la nota de crédito supera el total de la venta.',
      severity: 'warn',
      life: 3500,
    });
  }

  const availableNew = parseFloat(
    PreciseCalculator.subtract(data.n_available, form.amount).toString()
  );

  form.credit_notes.push({
    dayRemaining: data.dayRemaining,
    expireSoon: data.expireSoon,
    uuid: data.uuid,
    code: data.code,
    n_available: parseFloat(data.n_available.toString()).toFixed(2),
    n_available_new: availableNew,
    ncf: data.ncf,
    created_at: data.created_at,
  });
};

const setName = (name: string) => {
  client.value = name;
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
            <SaleInfo
              v-model:client="client"
              ref="saleInfoRef"
              :clients="propsW.clients"
              @getSequenceType="(type: string) => saleDetailRef?.getSequenceType(type)"
              :invoice-type="form.invoice_type"
            />

            <!-- Búsqueda y Selección de Productos -->
            <SaleDetail
              v-model:send-return-info="returnInfo"
              :saleTypes="propsW.saleTypes"
              ref="saleDetailRef"
              :products="propsW.products"
              :sale-open="propsW.saleOpen"
              :invoice-type="propsW.invoiceType"
              :refund="form.type === 'DEVOLUCION'"
              @sendClientName="setName"
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
        <PaymentInvoice
          @sendCreditData="getInfoCreditNote"
          @senData="sendData"
          v-model:show-return="showReturn"
          ref="salePaymentRef"
        />
      </div>
    </Dialog>
  </AppLayout>
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
