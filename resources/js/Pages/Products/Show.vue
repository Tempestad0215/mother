<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import { Head} from '@inertiajs/vue3';
import { productI } from '@/Interfaces/Product';
import { PropType } from 'vue';
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";
import LinkHeader from "@components/LinkHeader.vue";


const props = defineProps({
    products: {
        type: Object as PropType<productI>,
        required: true
    }
});

</script>


<template>
<!--    Titulo de la ventana-->
    <Head title="Mostrar" />
    <AppLayout>

<!--        Titulo de la ventana-->
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


<!--        Contenido de la ventana-->
        <div
            class="max-w-[1100px] p-5">
               <FloatShowPro
                   class=""
                    :products="props.products"/>


        </div>

    </AppLayout>

</template>
