<script setup lang="ts">
import {router, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import {onMounted, onUpdated, provide, Ref, ref} from "vue";
import {productI} from "@/Interfaces/ProductInterface";
import {printPdf} from "@/Global/Helpers";
import {clientBaseI} from "@/Interfaces/ClientInterface";
import PrimaryButton from "@components/PrimaryButton.vue";
import axios from "axios";
import {CreateSaleI, creditNotesSaleI, infoSaleI, saleDataI} from "@/Interfaces/SaleInterface";
import {invoiceTypeI} from "@/Interfaces/SettingInterface";
import TabLink from "@components/TabLink.vue";
import {paginationI} from "@/Interfaces/GlobalInterface";
import SaleInfo from "@/Pages/Sale/SaleInfo.vue";
import ErrorComponent from "@components/ErrorComponent.vue";
import SaleDetail from "@/Pages/Sale/SaleDetail.vue";
import {saleKey} from "@/utils/keys";
import SaleFooter from "@/Pages/Sale/SaleFooter.vue";
import SaleTable from "@/Pages/Sale/SaleTable.vue";
import PaymentInvoice from "@components/PaymentInvoice.vue";
import FloatBox from "@components/FloatBox.vue";
import {useRoute} from "ziggy-js";

const route = useRoute();
/*
Utilizar el page para los datos de la página
 */
const page = usePage();

/*
 * Datos del back end
 */
const propsW = defineProps<{
	products: productI,
	clients: paginationI<clientBaseI>,
	saleOpen: paginationI<saleDataI>,
	invoiceType: invoiceTypeI[],
	saleInfo?: saleDataI,
	refund?: boolean,
	pdfUuid?: string,
}>();

/*
 * Datos de la ventana
 */
const showReturn: Ref<boolean> = ref(false);
const showFormReturn: Ref<boolean> = ref(false);


const saleInfoRef = ref<InstanceType<typeof SaleInfo>>()!
const saleDetailRef = ref<InstanceType<typeof SaleDetail>>()!
const saleTableRef = ref<InstanceType<typeof SaleTable>>()!
const saleFooterRef = ref<InstanceType<typeof SaleFooter>>()!
const salePaymentRef = ref<InstanceType<typeof PaymentInvoice>>()!


/*
 * Formulario
 */
const form = useForm<CreateSaleI>({
	id: 0,
	code_value: "",
	ncf: "",
	ncf_m: "",
	client_name: "",
	client_id: 0,
	client_rnc: "",
	client_rnc_status: "",
	client_social: "",
	info_sale: [] as infoSaleI[],
	tax: 0,
	discount_amount: 0,
	amount: 0,
	sub_total: 0,
	comment: "",
	comment_id: 0,
	close_table: false,
	received: 0,
	returned: 0,
	general: "",
	type: "ventas",
	type_payment: "CONTADO",
	update: false,
	sequence: "",
	sequence_type: "",
	invoice_type: "B02",
	credit_notes_value: "",
	credit_notes: [] as creditNotesSaleI[],
	credit_notes_amount: 0,
	pending: 0,
});

/*
al momento de cargar
 */
onMounted(() => {
	//Verificar si existe los datos para devoluicion
	setDataForm();
	//Buscar la secuencia si está en la configuration
	if (page.props.setting.sequence) saleInfoRef.value?.getSequence(form.invoice_type);

	//Para verificar
	let msjError = "Este Codigo No es Validos, Introduzca Uno Validado";

	//Valizar si es igual
	if (page.props.errors.general === msjError) {
		showFormReturn.value = true;
	}

});

/*
 * al momento de cargar
 */
onUpdated(() => {
	//Buscar la secuencia si está en la configuracion
	setTimeout(() => {
		if (page.props.setting.sequence) saleInfoRef.value?.getSequence(form.invoice_type);
	}, 200);

	//Para verificar
	let msjError = "Este Codigo No es Validos, Introduzca Uno Validado";

	//Valizar si es igual
	if (page.props.errors.general === msjError) {
		showFormReturn.value = true;
	}

	// Enviar los datos
	setDataForm();

});


/*
Funciones
 */
/**
 * Poner los datos en el formuilario
 */
function setDataForm() {
	//Verificar si existe los datos para devoluicion
	if (propsW.refund && propsW.saleInfo) {
		form.id = propsW.saleInfo.id;
		form.ncf_m = propsW.saleInfo.ncf;
		form.client_name = propsW.saleInfo.client_name;
		form.client_id = propsW.saleInfo.client_id;
		form.client_rnc = propsW.saleInfo.client_rnc;
		form.info_sale = propsW.saleInfo.info_sale;
		form.invoice_type = page.props.setting.sequence ? "B04" : "";
		form.type = "devolucion";

		//Recorrer los datos
		// form.info_sale.forEach((_, index) => totalAmount(index));

		//calcular totales
		// totalSale();
	}
}


function createCreditNotes() {
	// Enviar los datos para las devoluciones
	axios.patch(route('credit-note.store', {sale: form.id}), form)
		.then(res => {
			if (res.data.success) {
				//Imprimir el pdf
				printPdf(route('invoice.belt.note', {creditNote: res.data.id}));
				//Limpiar el pdf
				// router.get(route('sale.create'));
				router.visit(route('sale.create'));
			}
		})
		.catch(() => {

		});
}

/**
 * Enviar los datos para guardar
 */
async function sendData() {
	// Verificar si esta el retorno
	if (propsW.refund) {
		createCreditNotes()
	} else {
		//Verificar si no hay problema con nada
		if (!salePaymentRef.value?.returnedBlur() && form.close_table) {
			return;
		}
		//si es para actualizar
		if (form.update) {
			await updateSale()

		} else {
			await createSale()

		}
	}
}


async function createSale() {
	try {
		const res = await axios.patch(route('sale.update', {sale: form.id}), form)

		//si esta cerrada se vas a imprimir
		if (form.close_table) {
			//Mostrar el pdf de impresion
			printPdf(route('invoice.belt.sale', {sale: res.data.pdfUuid}));
		}
		//Limpiar el fomulario
		form.reset();
		showReturn.value = false;
		//Recargar los datos
		router.reload({only: ['products', 'clients', 'saleOpen', 'invoiceType', 'refund']});

	} catch (err) {
		form.setError("general", "Problema con esta Peticion")
	}
}

async function updateSale() {

	try {
		const res = await axios.post(route('sale.create'), form)

		// La cuenta es cerrada
		if (form.close_table) {

			// Imprimir el pdf
			printPdf(route('invoice.belt.sale', {sale: res.data.pdfUuid}));
		}
		//Limpiar el fomulario
		form.reset();
		showReturn.value = false;
		//Recargar los datos
		router.reload({only: ['products', 'clients', 'saleOpen', 'invoiceType', 'refund']});

	}catch (error) {
		form.setError("general", "Problema con esta Peticion")
	}
}

provide(saleKey, form)

</script>

<template>
	<!--    Contenido general-->
	<AppLayout>
		<!--        Cabecera de la ventana-->
		<template #header>
			<TabLink
				:active="true"
				:href="route('sale.create')">
				Registrar
			</TabLink>
			<TabLink
				:href="route('sale.show')">
				Mostrar
			</TabLink>
			<TabLink
				:href="route('credit-note.show')">
				N. Credito
			</TabLink>
			<TabLink
				:href="route('sale.close')">
				Cierre
			</TabLink>
			<TabLink
				:href="route('sale.counter')">
				Conteo
			</TabLink>
		</template>

		<!--        //contenido-->
		<div>
			<div
				class="fondo p-5 rounded-md mx-auto overflow-hidden">
				<form
					class=" max-w-3/5">
					<div>
						<SaleInfo
							ref="saleInfoRef"
							:clients="propsW.clients"
							@getSequenceType="(type:string) => saleDetailRef?.getSequenceType(type)"
							:invoice-type="form.invoice_type"/>

						<SaleDetail
							ref="saleDetailRef"
							:products="propsW.products"
							:sale-open="propsW.saleOpen"
							:invoice-type="propsW.invoiceType"
							:refund="propsW.refund"
							@total-sale=""
							@totalSale="saleTableRef?.totalSale()"
							@total-amount="(index:number) => saleTableRef?.totalAmount(index)"
						/>

						<SaleTable
							ref="saleTableRef"/>
						<SaleFooter
							ref="saleFooterRef"/>
						<!--                        Devuelta y demas detos-->
						<div class=" mt-2 w-64 float-right">

							<div class="">
								<PrimaryButton
									:disabled="form.processing"
									@click="salePaymentRef?.checkSale()"
									type="button">
									{{ form.close_table ? 'Cerrar Venta' : 'Registrar' }}
								</PrimaryButton>
							</div>
						</div>
					</div>
				</form>
			</div>

		</div>
		<ErrorComponent v-model:errors="form.errors"/>

		<!-- Ventana de Devuelta-->
		<FloatBox
			v-model:show="showReturn">
			<template #header>
				Ventana de Pago
			</template>
			<template #body>
				<PaymentInvoice
					@senData="sendData"
					v-model:show-return="showReturn"
					ref="salePaymentRef"/>
			</template>
		</FloatBox>
	</AppLayout>
</template>

