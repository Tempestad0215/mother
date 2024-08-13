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
import LinkHeader from "@components/LinkHeader.vue";


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
            <LinkHeader
                :href="route('product.create')">
                Registrar
            </LinkHeader>
            <LinkHeader
                :active="true"
                :href="route('product.show')">
                Mostrar
            </LinkHeader>
            <LinkHeader
                :href="route('product-in.create')">
                Entrada
            </LinkHeader>

        </template>

        <div>
            <ContentBox class="md:max-w-full mx-5" >
               <FloatShow
                    :products="props.products"/>

            </ContentBox>

        </div>

    </AppLayout>

</template>
