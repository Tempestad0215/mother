<script setup lang="ts">
import InputLabel from './InputLabel.vue';
import {ref} from "vue";


const search = defineModel<string>('searh');
const perPage = defineModel<number>('perPage',{
    default: 15
});
const perPageOption = ref([15,45,60,100]);


</script>


<template>
    <div class=" ">
        <InputLabel
            for="search"
            value="Buscar" />
        <div class="relative w-[450px]">
            <input
                class="border-gray-300 focus:border-green-300 focus:ring-green-300 rounded-md shadow-sm w-full pr-[60px] !focus:border-green-300 focus:ring-0"
                v-model="search"
                placeholder="Buscar"
                type="search" />
            <div class="absolute inset-y-0 right-0 flex items-center">
                <select
                    class="h-full rounded-md border-0 bg-transparent py-0 pl-2 pr-7 text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm "
                    name="perPage"
                    v-model="perPage"
                >
                    <option
                        v-for="(item, index) in perPageOption"
                        :key="index" >
                        {{item}}
                    </option>
                </select>
            </div>

        </div>

    </div>

</template>
