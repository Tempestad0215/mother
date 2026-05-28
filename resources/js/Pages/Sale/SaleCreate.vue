<script setup lang="ts">
import { router, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import { onMounted, onUpdated, provide, Ref, ref } from 'vue';
import { ProductTableI } from '@/Interfaces/ProductInterface';
import { printPdf } from '@/Global/Helpers';
import { clientBaseI } from '@/Interfaces/ClientInterface';
import axios from 'axios';
import {
  CreateSaleI,
  creditNotesSaleI,
  infoSaleI,
  saleDataI,
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
import { Dialog, Card, Button, useToast } from 'primevue';

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
  refund?: boolean;
  pdfUuid?: string;
  saleTypeEnum: SaleTypeEnumI;
  warehouses: WarehouseMapType;
}>();

//Ventanas
const showReturn: Ref<boolean> = ref(false);
const showFormReturn: Ref<boolean> = ref(false);
const paymentBox = ref(false);
const saleInfoRef = ref<InstanceType<typeof SaleInfo>>()!;
const saleDetailRef = ref<InstanceType<typeof SaleDetail>>()!;
const saleTableRef = ref<InstanceType<typeof SaleTable>>()!;
const saleFooterRef = ref<InstanceType<typeof SaleFooter>>()!;
const salePaymentRef = ref<InstanceType<typeof PaymentInvoice>>()!;

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
  type: 'Ventas',
  type_payment: 'Contado',
  update: false,
  sequence: '',
  sequence_type: '',
  invoice_type: 'B02',
  credit_notes_value: '',
  credit_notes: [] as creditNotesSaleI[],
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

// Obtener el tipo de venta
const setDataForm = () => {
  //Verificar si existe los datos para devoluicion
  if (propsW.refund && propsW.saleInfo) {
    form.uuid = propsW.saleInfo.uuid;
    form.ncf_m = propsW.saleInfo.ncf;
    form.client_name = propsW.saleInfo.client_name;
    form.client_uuid = propsW.saleInfo.client_uuid;
    form.client_rnc = propsW.saleInfo.client_rnc;
    form.info_sale = propsW.saleInfo.info_sale;
    form.invoice_type = page.props.setting.sequence ? 'B04' : '';
    form.type = 'devolucion';

    //Recorrer los datos
    // form.info_sale.forEach((_, index) => totalAmount(index));

    //calcular totales
    // totalSale();
  }
};

// Enviar los datos para las devoluciones
const createCreditNotes = () => {
  // Enviar los datos para las devoluciones
  axios
    .patch(route('credit-note.store', { sale: form.uuid }), form)
    .then((res) => {
      if (res.data.success) {
        //Imprimir el pdf
        printPdf(route('invoice.belt.note', { creditNote: res.data.id }));
        //Limpiar el pdf
        // router.get(route('sale.create'));
        router.visit(route('sale.create'));
      }
    })
    .catch(() => {});
};

// Obtener el tipo de boleta
const sendData = async () => {
  // Verificar si esta el retorno
  if (propsW.refund) {
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

  // 	//si esta cerrada se vas a imprimir
  // 	if (form.close_table) {
  // 		//Mostrar el pdf de impresion
  // 		printPdf(route('invoice.belt.sale', {sale: res.data.pdfUuid}));
  // 	}
  // 	//Limpiar el fomulario
  // 	form.reset();
  // 	showReturn.value = false;
  // 	//Recargar los datos
  // 	router.reload({only: ['products', 'clients', 'saleOpen', 'invoiceType', 'refund']});
  //
  // } catch (err) {
  // 	form.setError("general", "Problema con esta Peticion")
  // }
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

  // try {
  //   const res = await axios.post(route('sale.create'), form);

  //   // La cuenta es cerrada
  //   if (form.close_table) {
  //     // Imprimir el pdf
  //     printPdf(route('invoice.belt.sale', { sale: res.data.pdfUuid }));
  //   }
  //   //Limpiar el fomulario
  //   form.reset();
  //   showReturn.value = false;
  //   //Recargar los datos
  //   router.reload({ only: ['products', 'clients', 'saleOpen', 'invoiceType', 'refund'] });
  // } catch (error) {
  //   console.log(error);
  //   form.setError('general', 'Problema con esta Peticion');
  // }
};

// Registrar la venta
const registerSale = () => {
  // Verificar si no hay problema con nada
  if (form.type === 'Cotizacion' || !form.close_table) {
    sendData();
  } else {
    paymentBox.value = true;
    salePaymentRef.value?.checkSale();
  }
};

// Proveer el formulario a los componentes hijos
provide(saleKey, form);
</script>

<template>
  <!--    Contenido general-->
  <AppLayout>
    <Card>
      <template #title> </template>
      <template #content>
        <form class="">
          <div>
            <SaleInfo
              ref="saleInfoRef"
              :clients="propsW.clients"
              @getSequenceType="(type: string) => saleDetailRef?.getSequenceType(type)"
              :invoice-type="form.invoice_type"
            />

            <SaleDetail
              :saleTypeEnum="propsW.saleTypeEnum"
              ref="saleDetailRef"
              :products="propsW.products"
              :sale-open="propsW.saleOpen"
              :invoice-type="propsW.invoiceType"
              :refund="propsW.refund"
              @total-sale=""
              @totalSale="saleTableRef?.calculateTotals()"
              @total-amount="(index: number) => saleTableRef?.totalAmount(index)"
            />

            <SaleTable :warehouses="propsW.warehouses" ref="saleTableRef" />
            <SaleFooter ref="saleFooterRef" />
            <!--                        Devuelta y demas detos-->
            <div class="text-right mt-5">
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
      <PaymentInvoice @senData="sendData" v-model:show-return="showReturn" ref="salePaymentRef" />
    </Dialog>
  </AppLayout>
</template>
