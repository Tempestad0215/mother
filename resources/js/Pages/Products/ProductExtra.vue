<script setup lang="ts">
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import {ProductOptionsI} from "@/Interfaces/ProductInterface";
import {WarehouseBaseI} from "@/Interfaces/WarehouseInterface";
import FloatBox from "@components/FloatBox.vue";
import FRegisterWarehouse from "@/Pages/Setting/WH/FRegister.vue";
import {ref} from "vue";


const propsW = defineProps<{
	typeOptions: ProductOptionsI[],
	wareHouses: WarehouseBaseI[],
}>()

const showWareHouse = ref<boolean>(false);

const sku = defineModel<string>('sku')
const barCode = defineModel<string>('barCode')
const type = defineModel<string>('type')
const wareHouseId = defineModel<number>('wareHouseId')

</script>

<template>
	<fieldset class="field">
		<legend>
			Extra
		</legend>
		<div>
			<InputLabel
				class="inline ml-2"
				for="sku"
				value="Codigo Externo"/>
			<TextInput
				name="sku"
				v-model="sku"
				class="w-full"
			/>
		</div>
		<div>
			<InputLabel
				class="inline ml-2"
				for="bar_code"
				value="Cod. Barra"/>
			<TextInput
				name="bar_code"
				v-model="barCode"
				class="w-full"
			/>
		</div>


		<!--Opciones de producto, si sera producto o servicio-->
		<div class="">
			<InputLabel
				class="inline ml-2"
				for="type"
				value="Tipo"/>
			<select
				v-model="type"
				class=" w-full inputGeneral py-1 ">
				<option
					class="even:bg-blue-200"
					v-for="(item, index) in propsW.typeOptions"
					:key="index"
					:value="item.value">
					{{ item.name }}
				</option>
			</select>
		</div>
		<div class="">
			<InputLabel
				class=" ml-2"
				for="warehouse"
				value="Almacen"/>
			<select
				v-model="wareHouseId"
				class=" w-[70%] inputGeneral py-1 ">
				<option
					class="even:bg-blue-200"
					v-for="(item, index) in propsW.wareHouses"
					:key="index"
					:value="item.id">
					{{ item.name }}
				</option>
			</select>
			<i
				@click="showWareHouse = true"
				class="ml-2 icon-efect text-[1.5rem] text-cyan-400 fa-solid fa-warehouse"></i>
		</div>


	</fieldset>

	<!--    Mostra la ventana para agrear almacenes-->
	<FloatBox
		v-if="showWareHouse"
		@close="showWareHouse = false"
		header="Manejos Almancenes">
		<FRegisterWarehouse/>
	</FloatBox>
</template>

<style scoped>

</style>
