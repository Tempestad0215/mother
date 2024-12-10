<script setup lang="ts">
import {usePage} from "@inertiajs/vue3";

//Informacion de la pagina
const page = usePage();

//
defineProps<{
    label: string
    onLabel: string,
    offLabel: string
}>();


const model = defineModel<boolean>()

</script>

<template>
    <div class="select-none">
        <label
            v-if="label !=  ''"
            class="block"
            for="">
            {{label}}
        </label>
        <input
            class="peer hidden"
            id="toggle"
            v-model="model"
            name="toggle"
            type="checkbox">
        <label
            class="bg-yellow-300 py-1 rounded-md shadow-md px-2 peer-checked:bg-green-300  duration-300 min-w-fit cursor-pointer"
            for="toggle">
            {{ model ? onLabel : offLabel}}
        </label>
    </div>
</template>
