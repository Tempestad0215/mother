<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Float from '@/Pages/Suppliers/SupplierFloat.vue'
import FloatBox from '@/Components/FloatBox.vue'
import {ref} from 'vue';
import FloatProduct from '@/Pages/Products/ProductFloat.vue';
import {productBaseI} from "@/Interfaces/Product";
import {categoryBaseI} from "@/Interfaces/Categories";
import TabLink from "@components/TabLink.vue";




//Propiedades de la ventana
const props = defineProps<{
    productEdit? : productBaseI,
    update? : boolean,
    categories: categoryBaseI[],
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
            <TabLink
                :active="true"
                :href="route('product.create')">
                Registrar
            </TabLink>
            <TabLink
                :href="route('in.create')">
                Entrada
            </TabLink>

            <TabLink
                :href="route('product.show')">
                Mostrar
            </TabLink>
        </template>

        <!-- Contenido de la ventana de los productos -->
        <div class="max-w-[1100px] mx-auto">
           <div
               class="bg-blue-300 p-5 rounded-md">
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
                @close="showSupplierForm = false"
                v-if="showSupplierForm">
                <Float
                />
            </FloatBox>
        </div>
    </AppLayout>
</template>
