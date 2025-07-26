<script setup lang="ts">
import TextInput from "@components/TextInput.vue";
import InputLabel from "@components/InputLabel.vue";
import FRegisterCategory from "@/Pages/Categories/FRegister.vue";
import FRegisterSupplier from "@/Pages/Suppliers/FRegister.vue";
import FloatBox from "@components/FloatBox.vue";
import {reactive, ref} from "vue";
import axios from "axios";
import {productBaseI} from "@/Interfaces/ProductInterface";
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {supplierI} from "@/Interfaces/SupplierInterface";

defineProps<{
	categories: categoryBaseI[],
	suppliers: supplierI[],
}>()

defineEmits<{
	(e: 'selectProduct', item: productBaseI): void
}>()

const state = reactive({
	productCheck: null as productBaseI[] | null,
})

const showCategory = ref(false);
const showSupplier = ref(false);

const name = defineModel<string>('name')
const description = defineModel<string>('description')
const categoryId = defineModel<number>('categoryId')
const supplierId = defineModel<number>('supplierId')


async function checkProductExits() {
	axios.get(route('product.get.json', {search: name.value}))
		.then(res => {
			if (res.status === 200) {
				state.productCheck = res.data as productBaseI[];
			}
		});
}


</script>

<template>
	<fieldset class="field">
		<legend>
			Informacion
		</legend>
		
		<!-- Nombre -->
		<div>
			<InputLabel
				class="inline ml-2"
				for="name"
				value="Nombre"/>
			<div class="relative">
				<TextInput
					@keyup="checkProductExits"
					class=" w-full peer"
					name="name"
					required
					autocomplete="off"
					v-model="name"
					placeholder="Nombre del producto"
				/>
				<div
					class=" opacity-0  peer-focus:opacity-100 z-20 text-gray-50 absolute w-full bg-gray-800 border-2 rounded-md">
					<ol
						v-for="(item, index) in state.productCheck"
						:key="index"
						class="odd:bg-cyan-400 rounded-md">
						<li
							@click="$emit('selectProduct', item)"
							class="rounded-md px-5">
							{{ item.name }}
						</li>
					</ol>
				</div>
			</div>
		
		</div>
		
		<!-- Descricion -->
		<div class="">
			<InputLabel
				class="inline ml-2"
				for="description"
				value="Descripcion"/>
			<TextInput
				class=" w-full"
				name="name"
				v-model="description"
				placeholder="Descripcion"
			/>
		</div>
		
		<div>
			<InputLabel
				class="inline ml-2"
				for="category"
				value="Categoria"/>
			<div>
				<select
					v-model="categoryId"
					class=" w-[90%] inputGeneral py-1 ">
					<option
						selected
						disabled
						:value="0">
						-- Categoria --
					</option>
					<option
						class="even:bg-blue-200"
						v-for="(item, index) in categories"
						:key="index"
						:value="item.id">
						{{ item.name }}
					</option>
				</select>
				<i
					@click="showCategory = true"
					class="icon-efect text-cyan-400 text-[1.5rem] ml-3 fa-solid fa-code-branch"></i>
			</div>
		
		</div>
		
		<!-- Proveedor -->
		<div class="">
			<InputLabel
				class="inline ml-2"
				for="supplier"
				value="Proveedor"/>
			<div>
				<select
					v-model="supplierId"
					class=" w-[90%] inputGeneral py-1 ">
					<option selected disabled :value="0">-- Suplidor --</option>
					<option
						class="even:bg-blue-200"
						v-for="(item, index) in suppliers"
						:key="index"
						:value="item.id">
						{{ item.company_name }}
					</option>
				</select>
				<i
					@click="showSupplier = true"
					class="icon-efect text-cyan-400 text-[1.5rem] ml-3 fa-solid fa-truck"></i>
			</div>
		</div>
	</fieldset>
	
	<!--    Mostrar la categorias-->
	<FloatBox
		v-if="showCategory"
		@close="showCategory = false"
		header="MAnejo de categorias">
		<FRegisterCategory
			class="w-[50rem]"
		/>
	</FloatBox>
	
	<!--    Mostar la ventana de suplidores-->
	<FloatBox
		v-if="showSupplier"
		@close="showSupplier = false"
		header="Manejo de Proveedores">
		<FRegisterSupplier
		/>
	</FloatBox>

</template>

<style scoped>

</style>
