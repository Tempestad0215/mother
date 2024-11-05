<script setup lang="ts">


defineEmits<{
    (e: 'close'):void
}>();

</script>


<template>
<!--    <div-->
<!--        class=" bg-black bg-opacity-60 absolute top-20 left-[calc(10.8rem)]  w-[calc(100%)] max-w-[calc(1100px)]  h-[calc(100%-6rem)] flex justify-center py-10 rounded-md overflow-hidden">-->
        <div
            class="fixed inset-0 flex justify-center bg-gray-800 bg-opacity-60">
            <!--        Boton para cerrar-->
            <i
                @click="$emit('close')"
                class="icon-efect fa-regular fa-circle-xmark text-gray-100 text-4xl absolute top-[5rem] right-[2rem]"></i>

        <div
            class=" mt-[8rem] md:max-w-[1200px]">
            <slot/>
        </div>


    </div>


</template>
