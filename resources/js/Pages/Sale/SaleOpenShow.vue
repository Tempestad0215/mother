<script setup lang="ts">
import { saleDataI } from '@/Interfaces/SaleInterface';
import FormSearch from '@components/FormSearch.vue';
import { useForm } from '@inertiajs/vue3';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import Pagination from '@components/Pagination.vue';
import { Column, DataTable } from 'primevue';

/**
 * Propiedades de la ventana
 */
const props = defineProps<{
  saleOpen: PaginationI<saleDataI>;
}>();

/**
 * Formulario
 */

const form = useForm({
  search: '',
  per_page: 15,
  field: 'name',
});

/**
 * Emitir los datos al padre
 */
defineEmits<{
  (e: 'senData', item: saleDataI): void;
}>();

/**
 * Funciones
 */

//Buscar los datos
const submit = () => {
  form.get(`?search=${form.search}`, {
    preserveScroll: true,
    preserveState: true,
  });
};
</script>

<template>
  <div class="">
    <div class="flex items-center justify-between">
      <form @submit.prevent="submit()">
        <FormSearch holder="Buscar" v-model:select-value="form.per_page" v-model="form.search" />
      </form>
    </div>

    <DataTable :value="props.saleOpen.data">
      <Column header="Cliente" />
      <Column header="Itbis" />
      <Column header="Total" />
      <Column header="Act">
        <div>
          <i class="icon-efect fa-solid fa-circle-check"></i>
        </div>
      </Column>
      <template #footer>
        <Pagination search="" :pag="props.saleOpen" />
      </template>
    </DataTable>
  </div>
</template>

<style scoped></style>
