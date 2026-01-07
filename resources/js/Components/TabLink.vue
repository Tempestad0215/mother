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

