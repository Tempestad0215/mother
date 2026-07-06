<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import { onMounted, onUpdated, provide, Ref, ref, watch } from 'vue';
import { ProductTableI } from '@/Interfaces/ProductInterface';
import { printPdf } from '@/Global/Helpers';
import { clientBaseI } from '@/Interfaces/ClientInterface';
import {
  CreateSaleI,
  infoSaleI,
  saleDataI,
  SaleTypeEnumI,
  WarehouseMapType,
} from '@/Interfaces/SaleInterface';
import { invoiceTypeI } from '@/Interfaces/SettingInterface';
import { MenuItemI, PaginationI } from '@/Interfaces/GlobalInterface';
import SaleInfo from '@/Pages/Sale/SaleInfo.vue';
import SaleDetail from '@/Pages/Sale/SaleDetail.vue';
import { saleKey } from '@/utils/keys';
import SaleFooter from '@/Pages/Sale/SaleFooter.vue';
import SaleTable from '@/Pages/Sale/SaleTable.vue';
import PaymentInvoice from '@components/PaymentInvoice.vue';
import { useRoute } from 'ziggy-js';
import { Breadcrumb, Button, Card, Dialog, useToast } from 'primevue';
import { CreditNoteBalance } from '@/Interfaces/CreditNoteInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import { SaleBreadCrumbs } from '@/Helpers/SaleHelper';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';

//Datos de la ventana
const toast = useToast();
const route = useRoute();

// Datos del back end
const page = usePage();

//Datos del back end
const propsW = defineProps<{
  products: PaginationI<ProductTableI>;
  clients: PaginationI<clientBaseI>;
  saleOpen: PaginationI<saleDataI>;
  invoiceType: invoiceTypeI[];
  saleInfo?: saleDataI;
  pdfUuid?: string;
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
  type: 'Ventas' as 'Ventas' | 'Devolucion' | 'Cotizacion',
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
  //Verificar si existe los datos para devoluicion
  setDataForm();
  //Buscar la secuencia si está en la configuration
  if (page.props.setting.sequence) saleInfoRef.value?.getSequence(form.invoice_type);

  //Para verificar
  let msjError = 'Este Codigo No es Validos, Introduzca Uno Validado';

  //Valizar si es igual
  if (page.props.errors.general === msjError) {
    showFormReturn.value = true;
  }
});

// Obtener los datos de las cuentas abiertas
onUpdated(() => {
  //Buscar la secuencia si está en la configuracion
  setTimeout(() => {
    if (page.props.setting.sequence) saleInfoRef.value?.getSequence(form.invoice_type);
  }, 200);

  //Para verificar
  let msjError = 'Este Codigo No es Validos, Introduzca Uno Validado';

  //Valizar si es igual
  if (page.props.errors.general === msjError) {
    showFormReturn.value = true;
  }

  // Enviar los datos
  setDataForm();
});

// Obtener los datos de las cuentas abiertas
watch(
  () => page.flash,
  (newValue) => {
    if (newValue && 'saleInvoiceUrl' in page.flash) {
      const url = page.flash.saleInvoiceUrl as string;
      printPdf(url);
      // router.visit(route('sale.create'));
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

// Obtener el tipo de venta
const setDataForm = () => {
  //Verificar si existe los datos para devoluicion
  if (form.type === 'Devolucion' && propsW.saleInfo) {
    form.uuid = propsW.saleInfo.uuid;
    form.ncf_m = propsW.saleInfo.ncf;
    form.client_name = propsW.saleInfo.client_name;
    form.client_uuid = propsW.saleInfo.client_uuid;
    form.client_rnc = propsW.saleInfo.client_rnc;
    form.info_sale = propsW.saleInfo.info_sale;
    form.invoice_type = page.props.setting.sequence ? 'B04' : '';
    form.type = 'Devolucion';

    //Recorrer los datos
    // form.info_sale.forEach((_, index) => totalAmount(index));

    //calcular totales
    // totalSale();
  }
};

// Enviar los datos para las devoluciones
const createCreditNotes = () => {
  // Enviar los datos para las devoluciones
  form.post(route('credit-note.store', { sale: form.uuid }), {
    onSuccess: () => {
      toast.add({
        summary: 'Registro Creado',
        detail: 'Nota de Credito Creada Correctamente',
        severity: 'success',
        life: 3000,
      });
      // Imprimir el pdf de la nota de credito
      printPdf(route('invoice.credit-note', { creditNote: page.flash.credit_uuid }));
      // Limpiar el formulario
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

// Obtener el tipo de boleta
const sendData = async () => {
  if (form.pending > 0) {
    toast.add({
      summary: 'Advertencia',
      detail: 'Por favor, verifique el monto pendiente antes de continuar.',
      severity: 'warn',
      life: 3500,
    });
  } else {
    // Verificar si está el retorno
    if (form.type === 'Devolucion' && form.info_sale.length > 0) {
      // Enviar los datos para las devoluciones
      createCreditNotes();
    } else {
      //Verificar si no hay problema con nada
      // if (!salePaymentRef.value?.returnedBlur() && form.close_table) {
      // 	return;
      // }

      //si es para actualizar
      if (form.update) {
        await updateSale();
      } else {
        createSale();
      }
    }
  }
};

// Crear la venta
const createSale = () => {
  // try {
  // const res = await axios.patch(route('sale.update', {sale: form.id}), form)
  form.post(route('sale.store'), {
    onSuccess: () => {
      toast.add({
        summary: 'Registro Creado Correctamente',
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

// Actualizar la venta
const updateSale = async () => {
  form.patch(route('sale.update', { sale: form.uuid }), {
    onSuccess: () => {
      toast.add({
        summary: 'Registro Actualizado Correctamente',
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

// Registrar la venta
const registerSale = () => {
  // Verificar si no hay problema con nada
  if (form.type === 'Cotizacion' || !form.close_table || form.type === 'Devolucion') {
    sendData();
  } else {
    paymentBox.value = true;
    salePaymentRef.value?.checkSale();
  }
};

// Tomar la información de la nota de credito
const getInfoCreditNote = (data: CreditNoteBalance): void => {
  // Verificar si ya existe la nota de credito
  const exist = form.credit_notes.find((item) => item.uuid === data.uuid);

  // Verificar si ya existe la nota de credito
  if (exist)
    // Enviar el mensaje de error
    return toast.add({
      summary: 'Error',
      detail: 'Esta Nota De Credito Ya Fue Registrada',
      severity: 'error',
      life: 3500,
    });

  if (form.credit_notes_amount >= form.amount) {
    return toast.add({
      summary: 'Advertencia',
      detail: 'El Monto De La Nota De Credito Supera EL Total De La Venta, Por Favor Verifique',
      severity: 'warn',
      life: 3500,
    });
  }

  // Verificar la cantidad nueva
  const availableNew = parseFloat(
    PreciseCalculator.subtract(data.n_available, form.amount).toString()
  );

  // Pasar los datos
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

// Proveer el formulario a los componentes hijos
provide(saleKey, form);
</script>

<template>
  <!-- Contenido general-->
  <AppLayout>
    <Card>
      <template #header>
        <BreadCrumbComponent :item-options="SaleBreadCrumbs" />
      </template>
      <template #content>
        <form class="">
          <div>
            <!-- Informacion de la venta -->
            <SaleInfo
              v-model:client="client"
              ref="saleInfoRef"
              :clients="propsW.clients"
              @getSequenceType="(type: string) => saleDetailRef?.getSequenceType(type)"
              :invoice-type="form.invoice_type"
            />
            <!-- Detalle de la venta-->
            <SaleDetail
              v-model:send-return-info="returnInfo"
              :saleTypeEnum="propsW.saleTypeEnum"
              ref="saleDetailRef"
              :products="propsW.products"
              :sale-open="propsW.saleOpen"
              :invoice-type="propsW.invoiceType"
              :refund="form.type === 'Devolucion'"
              @sendClientName="setName"
              @total-sale=""
              @total-amount="saleTableRef?.calculateItemRow($event)"
              @totalSale="saleTableRef?.calculateTotals()"
            />

            <!-- Tabla de la venta-->
            <SaleTable :warehouses="propsW.warehouses" ref="saleTableRef" />

            <!-- Pie de la venta-->
            <SaleFooter ref="saleFooterRef" />

            <!-- Devuelta y demas detos-->
            <div class="text-right mt-5 space-x-3">
              <Button @click="cleanAllForm" severity="warn" label="Limpiar" />
              <Button
                @click="registerSale"
                type="button"
                :label="form.close_table ? 'Cerrar Venta' : 'Registrar'"
              />
            </div>
          </div>
        </form>
      </template>
    </Card>
    <!-- Ventana de Devuelta-->
    <Dialog v-model:visible="paymentBox" header="Ventana de Pago" modal>
      <PaymentInvoice
        @sendCreditData="getInfoCreditNote"
        @senData="sendData"
        v-model:show-return="showReturn"
        ref="salePaymentRef"
      />
    </Dialog>
  </AppLayout>
</template>
