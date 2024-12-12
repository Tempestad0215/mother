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
            class="bg-black w-full h-screen bg-opacity-60 absolute left-0 top-0 flex justify-center pt-[5rem]">
            <i
                @click="$emit('close')"
                class="absolute top-[2rem] right-[6rem] text-white text-[2rem] hover:scale-125 duration-200 fa-regular fa-rectangle-xmark"></i>
            <div class="max-w-[70rem]">
                <slot></slot>
            </div>
        </div>
    </Transition>
</template>
