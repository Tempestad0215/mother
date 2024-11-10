<script setup lang="ts">
import {computed} from "vue";

/*
Propiedades de la ventana
 */
const propsW = defineProps<{
    pdf: string | null
}>();


/*
Funcion computada
 */
const createUrlPdf = computed(() => {
    //Verificar si existe el pdf
    if (propsW.pdf != '') return `data:application/pdf;base64,${propsW.pdf}`
});

</script>

<template>
    <div class="">
        <iframe
            id="pdfA"
            @wheel.passive="true"
            class="w-[70rem] mx-auto px-10 h-[80vh]"
            :src="createUrlPdf" >
        </iframe>
    </div>

</template>

