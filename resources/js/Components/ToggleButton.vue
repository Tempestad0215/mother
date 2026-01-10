<script setup lang="ts">
import {usePage} from "@inertiajs/vue3";
import {v4 as uuid} from "uuid";

//Informacion de la pagina
const page = usePage();

//
const propsW = defineProps<{
    label: string
    onLabel: string,
    offLabel: string
    id?: string
}>();


const model = defineModel<boolean>();
const uniqueId = propsW.id ?? `toggle-${uuid()}`;

</script>

<template>
    <div class="select-none">
        <label
            v-if="label !=  ''"
            class="block text-gray-50"
            for="">
            {{label}}
        </label>
        <input
            class="peer hidden"
            :id="uniqueId"
            v-model="model"
            name="toggle"
            type="checkbox">
        <label
            class="bg-yellow-300 py-1 rounded-md shadow-md px-2 peer-checked:bg-green-300  duration-300 min-w-fit cursor-pointer"
            :for="uniqueId">
            {{ model ? onLabel : offLabel}}
        </label>
    </div>
</template>
