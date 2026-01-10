<script setup lang="ts">
import InputLabel from "@components/InputLabel.vue";

const inventoried = defineModel<boolean>('inventoried');
const hasFraction = defineModel<boolean>('hasFraction');
const status = defineModel<boolean>('status');
const has_tax = defineModel<boolean>('has_tax');
const has_special = defineModel<boolean>('has_special');
const has_promotion = defineModel<boolean>('has_promotion');

</script>

<template>
    <fieldset class="field p-2 w-[10rem] block float-right ml-3 text-gray-50">
        <legend>Manejo</legend>
        <div>
            <input
                id="has_inventoried"
                v-model="inventoried"
                name="has_inventoried"
                type="checkbox">
            <InputLabel class="inline ml-2" for="has_inventoried" value="Inventariar"/>
        </div>
        <div>
            <input
                id="has_fraction"
                name="has_fraction"
                v-model="hasFraction"
                type="checkbox">
            <InputLabel class="inline ml-2" for="has_fraction" value="Fraccionar"/>
        </div>
        <div>
            <input
                v-model="status"
                id="status"
                type="checkbox">
            <InputLabel class="inline ml-2" for="status" value="Estado"/>
        </div>
        <div>
            <input
                v-model="has_tax"
                id="has_tax"
                type="checkbox">
            <InputLabel class="inline ml-2" for="has_tax" value="Itbis"/>
        </div>
        <div>
            <input
                id="has_special"
                v-model="has_special"
                type="checkbox">
            <InputLabel class="inline ml-2" for="has_special" value="P. Especial"/>
        </div>
        <div>
            <input
                v-model="has_promotion"
                id="has_promotion"
                type="checkbox">
            <InputLabel class="inline ml-2" for="promotion" value="Promocion"/>
        </div>
    </fieldset>
</template>

<style scoped>

</style>
