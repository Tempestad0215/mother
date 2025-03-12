<script setup lang="ts">
import {Link} from "@inertiajs/vue3";
import {computed} from "vue";


const props = defineProps({
    href: {
        type: String,
        required: true
    },
    active:{
        type: Boolean
    }
});

const isActive = computed(()=>{
    return props.active ? '!bg-cyan-400' : '';
});


</script>

<template>
    <Link
        class=""
        :class="isActive"
        :href="props.href">
        <slot/>
    </Link>

</template>

<style scoped>
a{
    @apply inline-block bg-gray-400 font-bold px-3 py-1 rounded-md hover:bg-gray-300 ease-in-out duration-300;
}
</style>
