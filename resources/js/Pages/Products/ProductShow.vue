<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import { Head} from '@inertiajs/vue3';
import { productI } from '@/Interfaces/Product';
import { PropType } from 'vue';
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";


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

        </template>


<!--        Contenido de la ventana-->
        <div
            class="max-w-[70rem] bg-blue-300 rounded-md mx-auto p-5">
               <FloatShowPro
                   class=""
                    :products="props.products"/>


        </div>

    </AppLayout>

</template>
