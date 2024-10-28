<script setup lang="ts">
import {computed} from "vue";
import FloatBox from "@components/FloatBox.vue";

/*
Propiedades de la ventana
 */
const propsW = defineProps<{
    pdf: string | null
}>();

/*
Emitir eventos
 */
defineEmits<{
    (e: 'closeWindow'):void
}>()



/*
Funcion computada
 */
const createUrlPdf = computed(() => {
    //Verificar si existe el pdf
    if (propsW.pdf != '') return `data:application/pdf;base64,${propsW.pdf}`
});

</script>

<template>
    <div>
        <Transition>
            <FloatBox
                @close="$emit('closeWindow')">
                <!--        Para ver los PDF-->
                <iframe

                    @wheel.passive="true"
                    class="w-full h-600px overflow-hidden mt-10"
                    :src="createUrlPdf" >
                </iframe>
            </FloatBox>
        </Transition>

    </div>

</template>

