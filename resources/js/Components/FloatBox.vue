<script setup lang="ts">

const propsW = defineProps<{
    header: string,
}>();


const show = defineModel<boolean>('show',{
    default: false
})


</script>


<template>
<!--        <Dialog-->
<!--            :header="propsW.header"-->
<!--            modal-->
<!--            v-model:visible="show">-->
<!--            <slot/>-->
<!--        </Dialog>-->
</template>
