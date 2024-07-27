<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import HeaderBox from '@components/HeaderBox.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { productI } from '@/Interfaces/Product';
import FormSearch from '@components/FormSearch.vue';
import ContentBox from '@components/ContentBox.vue';
import { PropType } from 'vue';
import Pagination from '@components/Pagination.vue';
import NavLink from '@components/NavLink.vue';
import FloatShow from "@/Pages/Products/FloatShowPro.vue";


const props = defineProps({
    products: {
        type: Object as PropType<productI>,
        required: true
    }
});

</script>


<template>
    <Head title="Mostrar" />
    <AppLayout>
        <template #header >
            <HeaderBox>
                <h2>
                    Mostrar productos
                </h2>
                <template #link>
                    <NavLink
                        :href="route('product.create')" >
                        Registrar
                    </NavLink>
                    <NavLink
                        :active="true"
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

        <div>
            <ContentBox class="md:max-w-full mx-5" >
               <FloatShow
                    :products="props.products"/>

            </ContentBox>

        </div>

    </AppLayout>

</template>
