<script setup lang="ts">
import InputLabel from './InputLabel.vue';
import { ref } from 'vue';

const search = defineModel<string>('search');
const perPage = defineModel<number>('perPage', {
  default: 30,
});
const perPageOption = ref([30, 50, 60, 100]);
</script>

<template>
  <div class=" ">
    <InputLabel for="search" value="Buscar" />
    <div class="relative w-[450px]">
      <input
        class="border-orange-500 focus:border-blue-500 focus:ring-0 rounded-md shadow-sm w-full pr-[60px] !focus:border-green-300"
        v-model="search"
        placeholder="Buscar"
        type="search"
      />
      <select
        class="absolute inset-y-0 right-0 flex items-center h-full rounded-md border-0 border-orange-500 bg-transparent py-0 pl-2 pr-7 text-orange-500 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm"
        name="perPage"
        v-model="perPage"
      >
        <option
          class="bg-blue-300 even:bg-blue-400 text-white"
          v-for="(item, index) in perPageOption"
          :key="index"
        >
          {{ item }}
        </option>
      </select>
    </div>
  </div>
</template>
