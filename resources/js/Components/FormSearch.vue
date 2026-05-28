<script setup lang="ts">
import { FloatLabel, InputGroup, InputGroupAddon, InputText } from 'primevue';
import InputLabel from './InputLabel.vue';
import { Search } from '@lucide/vue';

const search = defineModel<string>('search');
</script>

<template>
  <div class=" ">
    <InputLabel for="search" value="Buscar" />
    <div class="relative w-100">
      <InputGroup>
        <FloatLabel>
          <InputText v-model="search" placeholder="Buscar" />
        </FloatLabel>
        <InputGroupAddon class="p-0 w-20">
          <Search />
        </InputGroupAddon>
      </InputGroup>

    </div>
  </div>
</template>
