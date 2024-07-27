<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import Float from '@/Pages/Suppliers/FloatSupp.vue'
import FloatBox from '@/Components/FloatBox.vue'
import {PropType, ref} from 'vue';
import FloatProduct from '@/Pages/Products/FloatPro.vue';
import LinkHeader from "@components/LinkHeader.vue";
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";
import {productI, proSupResI} from "@/Interfaces/Product";




const props = defineProps({
   products: {
       type: Object as PropType<productI>,
       required: true
   },
    productEdit: {
        type: Object as PropType<proSupResI>,
    },
    update:{
       type: Boolean,
        default: false
    }
});


const showSupplierForm = ref(false);



</script>



<template>
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
            <LinkHeader
                :active="true"
                :href="route('product.sale')">
                Venta
            </LinkHeader>
            <LinkHeader
                :active="true"
                :href="route('product-in.create')">
                Entrada
            </LinkHeader>


        </template>

        <!-- Contenido de la ventana de los productos -->
        <div>
           <div
               class="bg-gray-200 p-5 rounded-md">
               <FloatProduct
                   :product-edit="props.productEdit"
                   :update="props.update"
                   @show-supplier="showSupplierForm = true"/>
           </div>

            <div>
                <FloatShowPro
                    :products="products"/>
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
