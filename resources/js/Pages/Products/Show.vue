<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import { Head} from '@inertiajs/vue3';
import { productI } from '@/Interfaces/Product';
import ContentBox from '@components/ContentBox.vue';
import { PropType } from 'vue';
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
        </template>

        <div class="max-w-[1100px] mx-auto">
            <ContentBox class="md:max-w-full !bg-gray-200">
               <FloatShow
                   class=""
                    :products="props.products"/>

            </ContentBox>

        </div>

    </AppLayout>

</template>
