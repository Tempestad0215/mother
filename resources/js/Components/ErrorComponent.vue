<script setup lang="ts">

defineProps<{
    message: string;
}>()

</script>

<template>
    <Transition>
        <div
            v-if="message"
            class=" max-w-2xl mx-auto text-center mt-3 bg-red-200 px-5 rounded-md py-2 shadow-md">
            {{message}}
        </div>
    </Transition>
</template>

<style scoped>

</style>
