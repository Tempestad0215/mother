<script setup lang="ts">


const emit = defineEmits<{
    (e: 'close')
}>();

</script>


<template>
    <div
        class=" bg-black bg-opacity-80  absolute top-20 w-[calc(100%-7rem)]  h-[calc(100%-6rem)] flex justify-center py-10 rounded-md">

        <i
            @click="$emit('close')"
            class="icon-efect fa-regular fa-circle-xmark text-gray-100 text-4xl absolute top-5 right-5"></i>

        <slot/>

    </div>


</template>
