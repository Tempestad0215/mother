<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import FRegisterSupplier from '@/Pages/Suppliers/FRegister.vue'
import FloatBox from '@/Components/FloatBox.vue'
import {ref} from 'vue';
import FRegister from '@/Pages/Products/FRegister.vue';
import {supplierI} from "@/Interfaces/Supplier";
import {productBaseI} from "@/Interfaces/Product";
import {categoryBaseI} from "@/Interfaces/Categories";
import TabLink from "@components/TabLink.vue";
import {WHbaseI} from "@/Interfaces/Warehouse";




//Propiedades de la ventana
const props = defineProps<{
    productEdit? : productBaseI,
    update? : boolean,
    categories: categoryBaseI[],
    suppliers: supplierI[],
    warehouse: WHbaseI[],
    nextProduct?: number,
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

                :href="route('entry.index')">
                Entrada
            </TabLink>

            <TabLink
                :href="route('product.show')">
                Mostrar
            </TabLink>
        </template>

        <!-- Contenido de la ventana de los productos -->
        <div class="">
           <div
               class="fondo p-5 rounded-md">
               <FRegister
                    :nextProduct="props.nextProduct"
                   :suppliers="props.suppliers"
                   :categories="props.categories"
                   :product-edit="props.productEdit"
                   :update="props.update"
                   :warehouse="props.warehouse"
                   @show-supplier="showSupplierForm = true"/>
           </div>

            <!-- Formulario para Agregar el suplidor -->
            <FloatBox
                header="Registro de Proveedor"
                @close="showSupplierForm = false"
                v-if="showSupplierForm">
                <FRegisterSupplier
                />
            </FloatBox>
        </div>
    </AppLayout>
</template>
