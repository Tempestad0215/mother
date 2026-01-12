<script setup lang="ts">
import {ProductBaseI, ProductFormI, ProductTypeEnumI} from '@/Interfaces/ProductInterface';
import {supplierI} from '@/Interfaces/SupplierInterface';
import {useForm, usePage} from '@inertiajs/vue3';
import {onMounted, provide} from 'vue';
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {PaymentTypeEnumI} from "@/Interfaces/GlobalInterface";
import {WarehouseBaseI} from "@/Interfaces/WarehouseInterface";
import ProductExtra from "@/Pages/Products/ProductExtra.vue";
import ProductDetail from "@/Pages/Products/ProductDetail.vue";
import ProductGeneral from "@/Pages/Products/ProductGeneral.vue";
import ProductSale from "@/Pages/Products/ProductSale.vue";
import ProductSaleValue from "@/Pages/Products/ProductSaleValue.vue";
import ProductInformation from "@/Pages/Products/ProductInformation.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faPrint} from "@fortawesome/free-solid-svg-icons";
import {useRoute} from "ziggy-js";
import {formProductKey} from "@/Injections/InjectionKeys";
import {BranchInterfaceI} from "@/Interfaces/BranchInterface";
import {UnitInterfaceI} from "@/Interfaces/UnitInterface";
import {AppPageProps} from "@/global";
import {Button, useToast} from "primevue";

const route = useRoute();
const {props} = usePage<AppPageProps>();
const toast = useToast();

/**
 * Propiedades de la ventana
 */
const propsW = defineProps<{
	productEdit: ProductBaseI | null,
	update?: boolean,
	categories: categoryBaseI[],
	suppliers: supplierI[],
	nextProduct?: number,
    paymentTypes: PaymentTypeEnumI,
    productType: ProductTypeEnumI,
    branches: BranchInterfaceI[]
    units: UnitInterfaceI[]
}>();



/**
 * Emitir eventos
 */
const emit = defineEmits(['showSupplier']);

/**
 * Datos del formulario
 */
const form = useForm<ProductFormI>({
	id: 0,
	name: "",
	description: "",
	unit_id: null,
	price: 0,
	cost: 0,
	min_price: 0,
	special_price: 0,
	product_no_tax: 0,
	benefits: 0,
	benefits_rate: 0,
	is_service: false,
	category_id: 0,
	supplier_id: 0,
	warehouse_id: 0,
	search: "",
	tax_id: 0,
	weight: 0,
	bar_code: "",
	sku: "",
	brand_id: null,
	dimensions: "",
	inventoried: true,
	has_fraction: true,
	status: true,
	has_tax: true,
	has_special: false,
	has_promotion: false,
	update: false,
});


provide(formProductKey, form)



/**
 * Al momento de cargar
 */
onMounted(() => {

	// Pasar los datos a editar
	if (propsW.productEdit) {
		form.id = propsW.productEdit.id;
		form.name = propsW.productEdit.name;
		form.is_service = propsW.productEdit.is_service === 1;
		form.description = propsW.productEdit.description ? propsW.productEdit.description : "";
		form.bar_code = propsW.productEdit.bar_code ? propsW.productEdit.bar_code : "";
		form.category_id = propsW.productEdit.category_id;
		form.supplier_id = propsW.productEdit.supplier_id;
		form.tax_id = Number(propsW.productEdit.tax_id);
		form.sku = propsW.productEdit.sku || "";
		form.unit_id = propsW.productEdit.unit_id;
		form.brand_id = propsW.productEdit.brand_id || 0;
		form.cost = Number(propsW.productEdit.cost);
		form.price = Number(propsW.productEdit.price);
		form.min_price = Number(propsW.productEdit.min_price) || 0;
		form.special_price = Number(propsW.productEdit.special_price) || 0;
	}


});


/**
 * Funcion para enviar los datos
 */
const submit = () => {
	if (propsW.update || form.update) {
		form.patch(route('product.update', form.id), {
			onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: "Registro Actualizado",
                    life: 3000
                })
			},
            onError: (err) => {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: `Error en esta peticion. Detalle : ${Object.values(err)[0]}`,
                    life: 3000
                })
            }
		})
	} else {
		// Formulario para guardar los productos
		form.post(route('product.store'), {
			onSuccess: () => {
				form.reset()
                toast.add({
                    severity: "success",
                    summary: "Registro Actualizado",
                    life: 3000
                })
			},
            onError: (err) => {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: `Error en esta peticion. Detalle : ${Object.values(err)[0]}`,
                    life: 3000
                })
            }
		});
	}

}


function selectProduct(item: ProductBaseI) {
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
                :paymentTypes="propsW.paymentTypes"
				:categories="propsW.categories"
				:suppliers="propsW.suppliers"/>
            <div class="flex flex-col md:flex-row flex-wrap gap-3">

                <ProductExtra
                    class="flex-1"
                    :productType="propsW.productType"/>

                <ProductGeneral
                    class=""
                />
            </div>

				<!--Detalle del producto-->
            <ProductDetail
                :units="propsW.units"
                :branches="propsW.branches"/>

			<ProductSaleValue
				@calculate="setCalculateData"/>

			<ProductSale/>

		</div>

		<!-- Botones -->
		<div class="mt-4 text-right">
            <Button type="submit" icon="pi pi-send" :label="propsW.update ? 'Actualizar' : 'Registrar'" />
		</div>
	</form>


</template>
