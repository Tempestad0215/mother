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
    return props.active ? 'bg-gray-800 text-white' : '';
});


</script>

<template>
    <Link
        class="bg-gray-300 px-3 py-1 rounded-md hover:bg-gray-800 ease-in-out duration-300 hover:text-white"
        :class="isActive"
        :href="props.href">
        <slot/>
    </Link>
</template>
