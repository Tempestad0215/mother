<script setup lang="ts">
import SecondaryButton from "@components/SecondaryButton.vue";

// const emit = defineEmits<{
//     (e: 'close')
// }>();
/**
 * Definir las propiedades
 */
const props = defineProps<{
    show?: boolean,
    maxWidth?: '2xl',
    closeable?: boolean,
    confirmText?: string;
    title?:string;
    text?: string;
}>();


/**
 * Datos de la ventana
 */


// const close = ():void => {
//     emit('close');
// };
</script>

<template>
    <div
        v-if="props.show"
        class="absolute top-20 w-[calc(100%-7rem)]  h-[calc(100%-6rem)] bg-black bg-opacity-60 z-30 flex items-center justify-center rounded-md">
        <div
            class="bg-gray-200 h-fit rounded-md p-10 max-w-2xl">


            <h3 class="text-center text-3xl font-bold border-b-2 border-gray-400">
                <i
                    class=" fa-solid fa-triangle-exclamation"></i>
                {{title}}
            </h3>


            <p class="my-5">
                {{text}}
            </p>

            <div class=" space-x-5 text-center">
                <SecondaryButton
                    class="bg-red-500 text-white">
                    {{confirmText}}
                </SecondaryButton>
                <SecondaryButton>
                    Cancelar
                </SecondaryButton>
            </div>

        </div>
    </div>

</template>
