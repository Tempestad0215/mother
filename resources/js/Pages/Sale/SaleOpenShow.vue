<script setup lang="ts">
import { saleDataI } from '@/Interfaces/SaleInterface';
import FormSearch from '@components/FormSearch.vue';
import { useForm } from '@inertiajs/vue3';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import Pagination from '@components/Pagination.vue';
import { Column, DataTable } from 'primevue';
import { getMoney } from '@/Global/Helpers';
import { MousePointerClick } from '@lucide/vue';

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
const emit = defineEmits<{
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
      <Column header="Codigo" field="code" />
      <Column header="Cliente" :field="(data: saleDataI) => data.client_name ?? 'N/A'" />
      <Column header="NCF" :field="(data: saleDataI) => data.ncf ?? 'N/A'" />
      <Column header="Itbis" :field="(data: saleDataI) => getMoney(data.tax)" />
      <Column header="Descuento" :field="(data: saleDataI) => getMoney(data.discount_amount)" />
      <Column header="Total" :field="(data: saleDataI) => getMoney(data.amount)" />
      <Column header="Fecha" :field="(data: saleDataI) => data.created_at" />
      <Column header="Act">
        <template #body="{data}">
          <div>
            <!-- dar estilo de hover scale de 125 -->
            <MousePointerClick
              @click="emit('senData', data)"
              class="hover:scale-125 duration-300 hover:text-green-400"
            />
          </div>
        </template>
      </Column>
      <template #footer>
        <Pagination search="" :pag="props.saleOpen" />
      </template>
    </DataTable>
  </div>
</template>

<style scoped></style>
