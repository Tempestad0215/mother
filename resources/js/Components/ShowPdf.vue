<script setup lang="ts">

/*
Propiedades de la ventana
 */
import {onMounted} from "vue";

const propsW = defineProps<{
    pdf: string,
}>();


const emit =defineEmits<{
    (e: 'sendError', msj: string): void;
}>()


onMounted(()=>{

    let iframe = document.getElementById("pdfA") as HTMLIFrameElement;

    //Estilo para ocultarlo
    // Establece el estilo del iframe para que no sea visible
    iframe.style.position = 'absolute';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = 'none';
    iframe.style.visibility = 'hidden';

    if (iframe && iframe.contentWindow)
    {
        iframe.contentWindow.print();

    }else{
        emit('sendError', 'Error al Cargar El PDF');
    }


});




</script>

<template>
    <div class="">
        <iframe
            id="pdfA"
            @wheel.passive="true"
            class="w-[70rem] mx-auto px-10 h-[80vh]"
            :src="propsW.pdf" >
        </iframe>
    </div>

</template>

