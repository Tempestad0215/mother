<script setup lang="ts">
import {invoiceTypeI} from "@/Interfaces/SettingInterface";
import {usePage} from "@inertiajs/vue3";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {
	faArrowRotateBack,
	faBoxOpen,
	faTableCellsColumnLock,
} from "@fortawesome/free-solid-svg-icons";
import axios from "axios";
import {ProductBaseI, productFullI} from "@/Interfaces/ProductInterface";
import {computed, inject, ref} from "vue";
import {infoSaleI, saleDataI, SaleTypeEnumI} from "@/Interfaces/SaleInterface";
import {saleKey} from "@/utils/keys";
import {PaginationI} from "@/Interfaces/GlobalInterface";
import {getSequenceType} from "@/Global/Helpers";
import {useRoute} from "ziggy-js";
import {FloatLabel, InputText, Select, ToggleButton, Dialog} from "primevue";
import FShowProduct from "@/Pages/Products/FShowProduct.vue";


const route = useRoute();
const page = usePage()

const propsW = defineProps<{
	invoiceType: invoiceTypeI[],
	refund?: boolean,
	saleOpen: PaginationI<saleDataI>,
	products: PaginationI<ProductBaseI>,
    saleTypeEnum: SaleTypeEnumI
}>()


const emit = defineEmits<{
	(e: 'retunedBlur'): void,
	(e: 'totalAmount', index: number): void,
	(e: 'totalSale'):void
}>()

const form = inject(saleKey)!

const showProducts = ref(false)
const showSaleOpen = ref(false)
const showReturn = ref(false)
const showFormReturn = ref(false)




const getSaleType = computed(()=>{
    return Object.entries(propsW.saleTypeEnum).map(([key, value]) => {
        return {
            key: key,
            value: value,
            hidden: key === "Devolucion"
        }
    });
})

/**
 * Obtener los datos de productos
 * @param item
 */
function getData(item: productFullI) {
	//Obtener los datos de productos
	let info: infoSaleI | undefined = form.info_sale.find((el) => el.product_id === item.id);

	// Verificar si el producto exite
	if (info?.product_id === item.id) {
		info.stock += 1.00;
        showProducts.value = false;

	} else {

		//Pasar los datos al formulario
		// form.info_sale.push({
		// 	amount: 0,
		// 	discount_rate: item.discount,
		// 	discount_amount: 0,
		// 	price: item.price,
		// 	min_price: item.min_price,
		// 	special_price: item.special_price,
		// 	product_id: item.id,
		// 	product_name: item.name,
		// 	stock: 1,
		// 	reserved: 1,
		// 	tax: item.tax,
		// 	tax_rate: item.tax_rate / 100,
		// 	type: item.type
		// });

		//Cerrar la ventana
        showProducts.value = false;
	}

	// //Conseguir el index para poder realizar el cálculo
	let index = form.info_sale.findIndex((el) => el.product_id === item.id);

	//Calcular el indice
	emit('totalAmount', index)

}

/**
 * Verificar el tipo de factura
 */
async function checkInvoiceType() {

	// Verificar si es nota de credito
	if (form.invoice_type === 'B04') {
		//Resultado de la pregunta
		// const result = await Swal.fire({
		// 	title: "Desea Colocar Comprobante?",
		// 	text: "Registre El Comprobante Del Cliente!",
		// 	icon: "question",
		// 	showCancelButton: true,
		// 	confirmButtonColor: "#3085d6",
		// 	cancelButtonColor: "#d33",
		// 	confirmButtonText: "Si",
		// 	cancelButtonText: "No"
		// });

		//Verificar la accion
		// showClientRnc.value = result.isConfirmed;

	}
	// else showClientRnc.value = form.invoice_type !== 'B02';

	// Solo buscar los datos si es igual a 0 el ID. eso quiere decir que debe generar un comprobante
	if (form.id == 0) {
		//llamar el tipo de boleta
		getSequenceType(form.invoice_type);
	}
}


/**
 * Obtener el producto por codigo
 */
function getProductCode() {

	//Verificar que tenga más de 6 caracter
	if (form.code_value.length > 0) {
		//realizar la busqueda en automatico
		axios.get(route('product.get.code', {search: form.code_value}))
			.then((res) => {
				//Formatear los datos
				const product: productFullI = res.data;
				//Pasar los datos al metodo
				getData(product);
				//Limpiar campo y errores en caso de tenerlo
			})
			.catch(() => {
				//Mensjae de que no existe en la base de datos
				form.setError('code_value', 'Este Producto no existe en la Base de Datos');
			})
	}
}

//Obtener los datos de las cuentas abiertas
function getSaleOpen(item: saleDataI) {

	//Colocar la variable en nada al principio
	form.info_sale = [];
	form.id = item.id;
	form.update = true;

	setTimeout(() => {
		//Verificar Pasar los datos a la variable
		item.info_sale.map((el, index) => {

			form.info_sale.push({...el})

			//Calcular el total
			emit('totalAmount', index);
		})
	}, 2);

	//calcular el total de las ventas
	emit('totalSale')

	//colocar los datos en el formulario
	form.client_id = item.client_id;
	form.client_rnc = item.client_document ?? "";
	form.ncf = item.ncf;
	form.invoice_type = item.invoice_type;
	form.client_name = item.client_name;
	form.close_table = item.close_table;
	form.comment = item.comment ?? "";

	//Cerra la ventana
	showSaleOpen.value = false;

	// Ejecutar el metodo de invoice
	checkInvoiceType();

}


function openReturn(){
	showReturn.value = !showReturn.value;
}

defineExpose({
	showReturn,
	getSequenceType,
	openReturn
})

</script>

<template>
	<!--                        Datos del formulario-->
	<div class=" flex justify-between items-center mt-3">
		<div class="flex">
			<form
				v-if="form.invoice_type !== 'B04' "
				@submit.prevent="getProductCode">
                <FloatLabel>
                    <InputText/>
                    <label for="code">Codigo</label>
                </FloatLabel>

			</form>
			<!-- Buscar los datos necesario -->
			<div
				v-if="!propsW.refund"
				class="ml-3">

				<FontAwesomeIcon
					title="Productos"
					@click="showProducts = !showProducts"
					class="icon-efect text-cyan-400 text-3xl" :icon="faBoxOpen"/>

				<FontAwesomeIcon
					title="Cuentas Abiertas"
					@click="showSaleOpen = !showSaleOpen"
					class=" ml-3 icon-efect text-cyan-400 text-3xl" :icon="faTableCellsColumnLock"/>
				<FontAwesomeIcon
					@click="showFormReturn = !showFormReturn"
					title="Devolucion"
					class="ml-3 icon-efect text-cyan-400 text-3xl"
					:icon="faArrowRotateBack"/>

			</div>
		</div>

		<div class="flex">
			<!--Tipo de factura-->
			<div
				v-if="page.props.setting.sequence"
				class="ml-3">
                <FloatLabel variant="on" >
                    <Select :options="propsW.invoiceType" />
                    <label for="type_sale">Tipo Venta</label>
                </FloatLabel>
			</div>


			<!--Tipo de factura-->
			<div class="ml-2 w-40">
                <FloatLabel variant="on" >
                    <Select fluid v-model="form.type" option-value="value"  option-label="key" :option-disabled="(data) => data.hidden" :options="getSaleType" />
                    <label for="type_sale">Tipo Venta</label>
                </FloatLabel>
			</div>
			<!--Tipo de cuenta si abierta o cerrada-->
			<div
				v-if="!propsW.refund"
				class="ml-2">
                    <ToggleButton v-model="form.close_table"  on-label="Cuenta Cerrado" off-label="Cuenta Abieto" />

			</div>
		</div>

	</div>

    <Dialog
        class="w-300"
        v-model:visible="showProducts"
        modal>
        <FShowProduct
            :isProduct="false"
            :products="propsW.products"/>
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
