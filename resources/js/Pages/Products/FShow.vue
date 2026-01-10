<script setup lang="ts">

import FormSearch from "@components/FormSearch.vue";
import Pagination from "@components/Pagination.vue";
import {ProductBaseI, productI} from "@/Interfaces/ProductInterface";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {getMoney} from "@/Global/Helpers";
import {onMounted} from "vue";
import {useRoute} from "ziggy-js";



const route = useRoute();
/**
 * Informacion de la ventana
 */
const {url, component, props} = usePage();
const {auth} = props;

/**
 * Propiedades de la ventana
 */
const propsW = defineProps<{
	products: productI,
	stock?: boolean
}>();


onMounted(() => {
	//Tomar el parametros de buscar
	const search: string = route().params.search;

	//si existe el search
	if (search) {
		form.search = search;
	}
});


/**
 * Emitir los eventos
 */
const emit = defineEmits(['select']);


/**
 * Formulario de datos
 */
const form = useForm({
	search: '',
	per_page: 30,
	field: "name",
});


/**
 * Funciones
 */
// Funciones
const submit = () => {

	router.get(``, {
		page: 1,
		perPage: form.per_page,
		search: form.search,
		stock: propsW.stock
	}, {
		preserveState: true,
		preserveScroll: true,
	});

}


//editar el producto
const edit = (id: number) => {
	router.get(route('product.edit', {id: id}));
}


//Seleccionar
const selectData = (item: ProductBaseI) => {
	if (component === "Products/Show") {
		router.get(route('product.edit', {product: item.id}));
	} else {
		//Enviar los datos
		emit('select', item);
	}

}

//Eliminar el producto
const detroy = (id: number) => {
	// Swal.fire({
	// 	title: "Esta seguro?",
	// 	text: "Los cambios realizados son irreversible!",
	// 	icon: "warning",
	// 	showCancelButton: true,
	// 	confirmButtonColor: "#3085d6",
	// 	cancelButtonColor: "#d33",
	// 	confirmButtonText: "Si, Eliminar!",
	// 	cancelButtonText: "Cancelar"
	// }).then((result) => {
	// 	if (result.isConfirmed) {
	// 		router.patch(route('product.destroy', {product: id}), {}, {
	// 			onSuccess: () => {
	// 			}
	// 		})
	// 	}
	// });
}


</script>

<template>
	<div
		class="rounded-md ">
		<div
			class="flex justify-between">
			<div>
				<form @submit.prevent="submit">
					<FormSearch
						v-model:search="form.search"
						v-model:per-page.number="form.per_page"/>
				</form>
			</div>
			<h3 class="text-3xl font-bold float-right mt-6">
				Productos
			</h3>
		</div>

		<div
			class="max-h-[65vh] overflow-y-auto overflow-x-hidden">
			<table
				class=" mt-3 styleTable table-auto min-w-full">
				<thead>
				<tr>
					<th class="">Id</th>
					<th class="">Cod. Barra</th>
					<th class="">Ref.</th>
					<th class="">Nombre</th>
					<th class="">Disp.</th>
					<th class="">Precio</th>
					<th class="">Act</th>
				</tr>
				</thead>
				<tbody>
				<tr v-for="(item) in propsW.products.data">
					<td>{{ item.id }}</td>
					<td>{{ item.bar_code || 'N/A' }}</td>
					<td>{{ item.sku || 'N/A' }}</td>
					<td>{{ item.name }}</td>
					<td>{{ item.stock }}</td>
					<td>{{ getMoney(item.price) }}</td>
					<td>

						<!-- Entrada de producto -->
						<i
							v-if="url !== 'Products/Show'"
							title="Crear Entrada"
							@click="selectData(item)"
							class=" icon-efect fa-solid fa-circle-check"></i>

						<!-- Editar -->
<!--						<i-->
<!--							v-if="component === 'Products/Show' "-->
<!--							title="Editar"-->
<!--							@click="edit(item.id)"-->
<!--							class="ml-2 icon-efect fa-solid fa-pen-to-square"></i>-->

						<!-- Eliminar -->
						<i
							v-if="component === 'Products/Show' && auth.user.role === 'admin' "
							title="Eliminar"
							@click="detroy(item.id)"
							class="ml-2 icon-efect fa-solid fa-trash"></i>
					</td>
				</tr>
				</tbody>
			</table>
		</div>

		<!--        PAginacion de la ventana-->
		<Pagination
			:search="form.search"
			:field="form.field"
			:per-page="form.per_page"
			:current-page="propsW.products.current_page"
			:total-page="propsW.products.to"
			:next="propsW.products.next_page_url"
			:prev="propsW.products.prev_page_url"/>

	</div>
</template>

