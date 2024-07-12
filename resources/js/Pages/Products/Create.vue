<script setup lang="ts">
import HeaderBox from '@/Components/HeaderBox.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentBox from '@components/ContentBox.vue';
import { Head } from '@inertiajs/vue3';
import Float from '@/Pages/Suppliers/FloatSupp.vue'
import FloatBox from '@/Components/FloatBox.vue'
import {  PropType, ref } from 'vue';
import NavLink from '@components/NavLink.vue';
import type { proSupResI } from '@/Interfaces/Product';
import FloatProduct from '@/Pages/Products/FloatPro.vue';



defineProps({
    productEdit: {
        type: Object as PropType<proSupResI>,
    },
    update: {
        type: Boolean
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
            <HeaderBox>
                <h2>
                    Registro de productos
                </h2>

                <template #link>
                    <NavLink
                        :active="true"
                        :href="route('product.create')" >
                        Registrar
                    </NavLink>
                    <NavLink
                        :href="route('product.show')" >
                        Mostrar
                    </NavLink>
                    <NavLink
                        :href="route('product-in.create')" >
                        Entrada
                    </NavLink>


                </template>

            </HeaderBox>
        </template>

        <!-- Contenido de la ventana de los productos -->
        <div>
            <ContentBox>

                <FloatProduct
                    @show-supplier="showSupplierForm = true"/>
            </ContentBox>

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
