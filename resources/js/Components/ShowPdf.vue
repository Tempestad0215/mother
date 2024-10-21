<script setup lang="ts">
import {computed} from "vue";
import FloatBox from "@components/FloatBox.vue";

/*
Propiedades de la ventana
 */
const propsW = defineProps<{
    pdf?: string
}>();

/*
Emitir eventos
 */
const emit = defineEmits<{
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
                    class="w-full mt-10"
                    :src="createUrlPdf" >
                </iframe>
            </FloatBox>
        </Transition>

    </div>

</template>

