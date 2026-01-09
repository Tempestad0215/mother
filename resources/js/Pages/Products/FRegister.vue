<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {productBaseI, ProductOptionsI} from '@/Interfaces/ProductInterface';
import {supplierI} from '@/Interfaces/SupplierInterface';
import {useForm, usePage} from '@inertiajs/vue3';
import {onMounted, Ref, ref} from 'vue';
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {taxI} from "@/Interfaces/GlobalInterface";
import {warehouseBaseI} from "@/Interfaces/WarehouseInterface";
import ProductExtra from "@/Pages/Products/ProductExtra.vue";
import ProductDetail from "@/Pages/Products/ProductDetail.vue";
import ProductGeneral from "@/Pages/Products/ProductGeneral.vue";
import ProductSale from "@/Pages/Products/ProductSale.vue";
import ProductSaleValue from "@/Pages/Products/ProductSaleValue.vue";
import ProductInformation from "@/Pages/Products/ProductInformation.vue";
import ErrorComponent from "@components/ErrorComponent.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faPrint} from "@fortawesome/free-solid-svg-icons";
import {useRoute} from "ziggy-js";


const route = useRoute();
/**
 * Info general
 */
const {props} = usePage();

/**
 * Propiedades de la ventana
 */
const propsW = defineProps<{
	productEdit: productBaseI | null,
	update?: boolean,
	categories: categoryBaseI[],
	suppliers: supplierI[],
	warehouse: warehouseBaseI[],
	nextProduct?: number,
}>();


/**
 * Emitir eventos
 */
const emit = defineEmits(['showSupplier']);


/**
 * Datos del formulario
 */
const form = useForm({
	id: 0,
	name: "",
	description: "",
	unit: "",
	price: 0,
	cost: 0,
	min_price: 0,
	special_price: 0,
	product_no_tax: 0,
	benefits: 0,
	benefits_rate: 0,
	type: "producto",
	category_id: 0,
	supplier_id: 0,
	warehouse_id: 0,
	search: "",
	tax: 0,
	tax_rate: 0,
	tax_tex: "",
	weight: 0,
	bar_code: "",
	sku: "",
	brand: "",
	dimensions: "",
	inventoried: true,
	has_fraction: true,
	status: true,
	has_tax: true,
	has_special: false,
	has_promotion: false,
	update: false,
});

/**
 *Datos de la ventana
 */
const taxes: Ref<taxI[]> = ref(props.setting.tax);
const dataUnit: Ref<string[]> = ref(props.setting.unit);
const typeOptions: Ref<ProductOptionsI[]> = ref([
	{
		name: 'Producto',
		value: 'producto',
	},
	{
		name: 'Servicio',
		value: 'servicio'
	}]);


/**
 * Al momento de cargar
 */
onMounted(() => {

	// Pasar los datos a editar
	if (propsW.productEdit) {
		form.id = propsW.productEdit.id;
		form.name = propsW.productEdit.name;
		form.type = propsW.productEdit.type;
		form.description = propsW.productEdit.description ? propsW.productEdit.description : "";
		form.bar_code = propsW.productEdit.bar_code ? propsW.productEdit.bar_code : "";
		form.category_id = propsW.productEdit.category_id;
		form.supplier_id = propsW.productEdit.supplier_id;
		form.tax_rate = Number(propsW.productEdit.tax_rate);
		form.sku = propsW.productEdit.sku || "";
		form.unit = propsW.productEdit.unit;
		form.brand = propsW.productEdit.brand || "";
		form.cost = Number(propsW.productEdit.cost);
		form.price = Number(propsW.productEdit.price);
		form.min_price = Number(propsW.productEdit.min_price) || 0;
		form.special_price = Number(propsW.productEdit.special_price) || 0;
	}

	//Elegir el primer si existe
	if (propsW.warehouse.length > 0) {
		form.warehouse_id = propsW.warehouse[0].id;
	}

});


/**
 * Funcion para enviar los datos
 */
const submit = () => {

	if (propsW.update || form.update) {
		form.patch(route('product.update', form.id), {
			onSuccess: () => {
			}
		})
	} else {
		// Formulario para guardar los productos
		form.post(route('product.store'), {
			onSuccess: () => {
				form.reset()
			}
		});
	}

}


function selectProduct(item: productBaseI) {
	// Swal.fire({
	// 	title: "Desea Actualizar?",
	// 	text: `Desea actualizar el producto : ${item.name} !`,
	// 	icon: "warning",
	// 	showCancelButton: true,
	// 	confirmButtonColor: "#3085d6",
	// 	cancelButtonColor: "#d33",
	// 	confirmButtonText: "Si Actualizar!",
	// 	cancelButtonText: "Cancelar!",
	// }).then((result) => {
	// 	if (result.isConfirmed) {
    //
	// 		form.id = Number(item.id);
	// 		form.name = item.name;
	// 		form.type = item.type;
	// 		form.description = item.name;
	// 		form.cost = Number(item.cost);
	// 		form.price = Number(item.price);
	// 		form.category_id = item.category_id;
	// 		form.supplier_id = item.supplier_id;
	// 		form.sku = item.sku ?? '';
	// 		form.unit = item.unit;
	// 		form.bar_code = item.bar_code ?? '';
	// 		form.type = item.type;
	// 		form.tax_rate = Number(item.tax_rate) ?? 0;
	// 		form.weight = Number(item.weight) ?? '';
	// 		form.brand = item.brand ?? '';
	// 		form.min_price = Number(item.min_price) ?? 0;
	// 		form.special_price = Number(item.special_price) ?? 0;
	// 		form.inventoried = item.inventoried;
	// 		form.has_fraction = item.has_fraction;
	// 		form.status = item.status;
	// 		form.has_tax = item.has_tax;
	// 		form.update = true
    //
	// 	}
	// });
}


function setCalculateData(
	productNoTax: string, benefits: string, benefitsMargin: string) {
	form.product_no_tax = Number(productNoTax);
	form.benefits = Number(benefits);
	form.benefits_rate = Number(benefitsMargin);
}


async function printLabel() {

	try {

        //await showPDf('Etiqueta', route('invoice.label', {id: form.id}))

	} catch (error) {

		// await Swal.fire('Error', 'No se pudo generar la etiqueta', 'error');
	}
}


</script>


<template>
	<!--Formulario-->
	<form
		@submit.prevent="submit">
		<!--Titulo-->
		<h3 class="text-2xl font-bold text-center">
			Registro de producto
		</h3>

		<div class="flex items-center">
			<div v-if="propsW.nextProduct">
				<p>Seguiente ID :
					<span class="px-2 py-1 rounded-md">
                    {{ propsW.nextProduct }}
                </span>
				</p>
			</div>
			<div
				v-show="form.id !== 0"
				class="flex items-center">
				<FontAwesomeIcon
					@click="printLabel"
					title="Imprimir Etiqueta" class=" ml-3 text-cyan-300 text-3xl" :icon="faPrint"/>
			</div>
		</div>


		<!--Informacion General-->
		<div class="">
			<ProductInformation
				@select-product="selectProduct"
				v-model:name="form.name"
				v-model:description="form.description"
				v-model:category-id="form.category_id"
				v-model:supplier-id="form.supplier_id"
				:categories="propsW.categories"
				:suppliers="propsW.suppliers"/>

			<ProductGeneral
				v-model:inventoried="form.inventoried"
				v-model:has-fraction="form.has_fraction"
				v-model:status="form.status"
				v-model:has_tax="form.has_tax"
				v-model:has_special="form.has_special"
				v-model:has_promotion="form.has_promotion"
			/>


			<div class=" grid grid-cols-2 gap-4 mt-3">
				<ProductExtra
					v-model:sku="form.sku"
					v-model:bar-code="form.bar_code"
					v-model:type="form.type"
					v-model:ware-house-id="form.warehouse_id"
					:type-options="typeOptions"
					:ware-houses="propsW.warehouse"/>

				<!--Detalle del producto-->
				<ProductDetail
					v-model:tax-rate="form.tax_rate"
					v-model:unit="form.unit"
					v-model:weigh="form.weight"
					v-model:brand="form.brand"
					v-model:dimension="form.dimensions"
					:data-unit="dataUnit"
					:is-product="form.type == 'producto'"
					:taxes="taxes"/>

			</div>
			<ProductSaleValue
				@calculate="setCalculateData"
				v-model:tax-rate="form.tax_rate"
				v-model:cost="form.cost"
				v-model:price="form.price"
				v-model:min_price="form.min_price"
				v-model:special_price="form.special_price"/>

			<ProductSale
				:price-no-tax="form.product_no_tax.toString()"
				:benefits="form.benefits.toString()"
				:benefits-margin="form.benefits_rate.toString()"/>


		</div>
		<ErrorComponent
			v-model:errors="form.errors"/>


		<!-- Botones -->
		<div class="mt-4 text-right">
			<PrimaryButton
				:disabled="form.processing">
				{{ propsW.update ? 'Actualizar' : 'Registrar' }}
			</PrimaryButton>
		</div>
	</form>


</template>
