<script setup lang="ts">

const propsW = defineProps<{
    header: string,
}>();

defineEmits<{
    (e: 'close'): void;
}>();


const show = defineModel<boolean>('show',{
    default: false
})


</script>


<template>
    <Transition>
        <div
            class="bg-blue-300 backdrop-blur-md w-full h-screen bg-opacity-80 absolute left-0 top-0 flex justify-center pt-[5rem] pb-[5rem]">

            <i
                @click="$emit('close')"
                class="absolute top-[2rem] right-[6rem] text-white text-[2rem] hover:scale-125 duration-200 fa-regular fa-rectangle-xmark"></i>
            <div class="max-w-[70rem]">
                <h3 class="fondo flex-1 text-2xl font-semibold text-center rounded-md mb-2">
                    {{propsW.header}}
                </h3>
                <slot/>
            </div>
        </div>

    </Transition>
</template>
