<script setup lang="ts">


defineProps<{
    priceNoTax: string;
    benefits: string;
    benefitsMargin: string;
}>()

</script>

<template>
    <fieldset disabled class="field grid grid-cols-3 gap-3">
        <legend>Info Ventas</legend>
        <p>
            <strong>Precio - Itbis</strong>
            <span class="inline-block px-3 rounded-md ml-3">{{ priceNoTax }}</span>
        </p>
        <p>
            <strong>Beneficio</strong>
            <span class="inline-block px-3 rounded-md ml-3">{{ benefits }}</span>
        </p>
        <p>
            <strong>Beneficios Margen </strong>
            <span class="inline-block px-3 rounded-md ml-3">{{ benefitsMargin }}</span>
        </p>
    </fieldset>
</template>

<style scoped>

</style>
