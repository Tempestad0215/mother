<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Float from '@/Pages/Suppliers/FloatSupp.vue'
import FloatBox from '@/Components/FloatBox.vue'
import {ref} from 'vue';
import FloatProduct from '@/Pages/Products/FloatPro.vue';
import LinkHeader from "@components/LinkHeader.vue";
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
            <LinkHeader
                :active="true"
                :href="route('product.create')">
                Registrar
            </LinkHeader>
<!--            Mostrar los productos -->
            <LinkHeader
                :href="route('product.show')">
                Mostrar
            </LinkHeader>



        </template>

        <!-- Contenido de la ventana de los productos -->
        <div>
           <div
               class="bg-gray-200 p-5 rounded-md">
               <FloatProduct
                   :suppliers="props.suppliers"
                   :categories="props.categories"
                   :product-edit="props.productEdit"
                   :update="props.update"
                   @show-supplier="showSupplierForm = true"/>
           </div>


            <Transition>
                <!-- Formulario para Agregar el suplidor -->
                <FloatBox
                    v-if="showSupplierForm"
                    @close=" showSupplierForm = !showSupplierForm "  >
                    <Float
                    />

                </FloatBox>
            </Transition>

        </div>

    </AppLayout>

</template>
