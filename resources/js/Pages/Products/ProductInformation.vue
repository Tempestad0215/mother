<script setup lang="ts">
import TextInput from "@components/TextInput.vue";
import InputLabel from "@components/InputLabel.vue";
import FRegisterCategory from "@/Pages/Categories/FRegister.vue";
import FRegisterSupplier from "@/Pages/Suppliers/FRegister.vue";
import FloatBox from "@components/FloatBox.vue";
import {inject, reactive, ref} from "vue";
import axios from "axios";
import {productBaseI} from "@/Interfaces/ProductInterface";
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {supplierI} from "@/Interfaces/SupplierInterface";
import {useRoute} from "ziggy-js";
import {formProductKey} from "@/Injections/InjectionKeys";
import {Card, InputText, FloatLabel, Select} from "primevue";

const route = useRoute();
defineProps<{
	categories: categoryBaseI[],
	suppliers: supplierI[],
}>()

defineEmits<{
	(e: 'selectProduct', item: productBaseI): void
}>()

const form = inject(formProductKey)!!
const state = reactive({
	productCheck: null as productBaseI[] | null,
})

const createCategory = ref(false);
const createSupplier = ref(false);



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
    <Card>
        <template #header>

        </template>
        <template #content>
            <div>
                <FloatLabel variant="on">
                    <InputText id="name"  v-model="form.name" />
                    <label for="name">Nombre</label>
                </FloatLabel>
                <FloatLabel variant="on">
                    <InputText id="description"  v-model="form.description" />
                    <label for="description">Descripcion</label>
                </FloatLabel>
                <FloatLabel variant="on">
                    <Select placeholder="Seleccione"  v-model="form.category_id" option-value="id" id="category" option-label="name" :options="categories" />
                    <label for="category">Categoria</label>
                </FloatLabel>
                <FloatLabel variant="on">
                    <Select placeholder="Seleccione"  v-model="form.supplier_id" option-value="id" id="supplier" option-label="name" :options="suppliers" />
                    <label for="supplier">Suplidor</label>
                </FloatLabel>
            </div>
        </template>
    </Card>

</template>
