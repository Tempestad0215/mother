<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Float from '@/Pages/Suppliers/FloatSupp.vue'
import FloatBox from '@/Components/FloatBox.vue'
import {ref} from 'vue';
import FloatProduct from '@/Pages/Products/FloatPro.vue';
import {productSupplierI} from "@/Interfaces/Product";
import {categoryI} from "@/Interfaces/Categories";
import {supplierI} from "@/Interfaces/Supplier";


//Propiedades de la ventana
const props = defineProps<{
    productEdit? : productSupplierI,
    update? : boolean,
    categories: categoryI[],
    suppliers: supplierI[]
}>();



//Mostrar la ventana de suplidores
const showSupplierForm = ref(false);



</script>



<template>

    <!--Titulo de la ventana    -->
    <Head title="Productos"/>

    <!-- Contenido de la ventana -->
    <AppLayout>
        <!-- cabecera -->
        <template #header >


        </template>

        <!-- Contenido de la ventana de los productos -->
        <div class="max-w-[1100px] mx-auto">
           <div
               class="bg-gray-200 p-5 rounded-md">
               <FloatProduct
                   :suppliers="props.suppliers"
                   :categories="props.categories"
                   :product-edit="props.productEdit"
                   :update="props.update"
                   @show-supplier="showSupplierForm = true"/>
           </div>



            <!-- Formulario para Agregar el suplidor -->
            <FloatBox
                header="Registro de Proveedor"
                v-model:show="showSupplierForm">
                <Float
                />

            </FloatBox>


        </div>

    </AppLayout>

</template>
