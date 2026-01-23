<script setup lang="ts">
import {inject, ref} from "vue";
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {SupplierI} from "@/Interfaces/SupplierInterface";
import {useRoute} from "ziggy-js";
import {formProductKey, productDataKey} from "@/Injections/InjectionKeys";
import {Card, InputText, FloatLabel, Select, Button, AutoComplete, Dialog, useToast} from "primevue";
import {router} from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import FRegisterCategory from "@/Pages/Categories/FRegister.vue";
import FRegisterSupplier from "@/Pages/Suppliers/FRegister.vue";
import {PaymentTypeEnumI} from "@/Interfaces/GlobalInterface";
import {useProductStore} from "@/stores/ProductStore";
import axios from "axios";

const route = useRoute();
const toast = useToast();

defineProps<{
	categories: categoryBaseI[],
	suppliers: SupplierI[],
    paymentTypes: PaymentTypeEnumI,
}>()

const form = inject(formProductKey)!!
const productDataOption = inject(productDataKey)
const createCategory = ref(false);
const createSupplier = ref(false);
const openPrintDialog = ref(false);
const urlLabel = ref<string | undefined>(undefined);
const loadingUrlLabel = ref(false)
const productStore = useProductStore()
const pdfFrame = ref<HTMLIFrameElement | null>(null);


const searchProduct = debounce(()=>{
    router.get(route('product.create', {search: form.name}),{},{
        preserveState: true,
        preserveScroll: true,
        replace: true
    })
}, 300);


const printLabel = async (code:string | null) => {
    if (code !== null) {
        try {
            loadingUrlLabel.value = true;
            const {data} = await axios.get(route(`product.get-label`, {code: code}))


            // 2) Crear iframe oculto
            const iframe = document.createElement('iframe')
            iframe.style.position = 'fixed'
            iframe.style.right = '0'
            iframe.style.bottom = '0'
            iframe.style.width = '0'
            iframe.style.height = '0'
            iframe.style.border = '0'
            iframe.style.zIndex = '999999'
            iframe.src = data.url


// 3) Cuando cargue, imprimir y luego eliminar
            iframe.onload = () => {
                try {
                    iframe.contentWindow?.focus()
                    iframe.contentWindow?.print()
                } finally {
                    // A veces conviene esperar un poco para que el print se dispare
                    // setTimeout(() => iframe.remove(), 500)
                }
            }

            document.body.appendChild(iframe)

            // urlLabel.value = data.url;
            // openPrintDialog.value = true;
        }catch(error) {
            toast.add({
                severity: 'warn',
                summary: 'Error',
                detail: "Erro al Intentar Obtener El Label",
                life: 5000
            });
        }finally {
            loadingUrlLabel.value = false;
        }

    }
}

</script>

<template>
    <Card>
        <template #header>
            <div class="flex justify-between items-center">

                <div class="flex items-center gap-3">
                    <p>ID Siguiente : {{productStore.nextCode}}</p>
                    <Button :disabled="loadingUrlLabel" @click="printLabel(productStore.nextCode)"  icon="pi pi-print" />
                </div>
                <div class="text-right space-x-3">
                    <Button @click="createCategory = true"  icon="" label="Crear Categoria" />
                    <Button @click="createSupplier = true" icon="" label="Crear Suplidor" />
                </div>
            </div>
        </template>
        <template #content>
            <div class="grid grid-cols-1  md:grid-cols-2 gap-3">
                <FloatLabel variant="on">
                    <AutoComplete fluid :suggestions="productDataOption" option-label="name" @valueChange="searchProduct" id="name"  v-model="form.name" />
                    <label for="name">Nombre</label>
                </FloatLabel>
                <FloatLabel variant="on">
                    <InputText fluid id="description"  v-model="form.description" />
                    <label for="description">Descripcion</label>
                </FloatLabel>
                <FloatLabel variant="on">
                    <Select fluid placeholder="Seleccione"  v-model="form.category_id" option-value="id" id="category" option-label="name" :options="categories" />
                    <label for="category">Categoria</label>
                </FloatLabel>
                <FloatLabel variant="on">
                    <Select fluid placeholder="Seleccione"  v-model="form.supplier_id" option-value="id" id="supplier" option-label="company_name" :options="suppliers" />
                    <label for="supplier">Suplidor</label>
                </FloatLabel>
            </div>
        </template>
    </Card>
    <Dialog
        v-model:visible="createCategory"
        modal
        header="Crear Categoria">
        <FRegisterCategory
            :category-edit="null"/>
    </Dialog>
    <Dialog
        v-model:visible="createSupplier"
        modal
        header="Crear Suplidor">
        <FRegisterSupplier
            :paymentTypes="paymentTypes"
            :update="false"
            :supplierEdit="null"/> />

    </Dialog>



</template>
