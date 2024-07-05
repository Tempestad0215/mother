<script setup lang="ts">


const emit = defineEmits(['close']);

</script>


<template>
    <div class=" bg-black bg-opacity-80  absolute top-0 w-full h-screen flex items-center justify-center">

        <i
            @click="$emit('close')"
            class="icon-efect fa-regular fa-circle-xmark text-gray-100 text-4xl absolute top-5 right-5 "></i>

        <slot/>

    </div>


</template>
