<script setup lang="ts" generic="T">
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { Paginator } from 'primevue';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

interface paginationActualI {
  search: string;
  pag: PaginationI<T>;
}

const propsW = defineProps<paginationActualI>();

// 1. Aseguramos que retorne un número real usando Number()
const first = computed(() => {
  const currentPage = Number(propsW.pag?.meta?.current_page) || 1;
  const perPage = Number(propsW.pag?.meta?.per_page) || 15;
  return (currentPage - 1) * perPage;
});

// 2. PrimeVue en @update:first envía un objeto de evento (PageState), no un número directo
const onPageChange = (event: any) => {
  // Calculamos la página destino basándonos en el salto de registros ('first') y filas ('rows')
  const nextPage = event.first / event.rows + 1;

  router.get(
    propsW.pag?.meta?.path ?? window.location.pathname,
    {
      search: propsW.search,
      per_page: event.rows,
      page: nextPage,
    },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['products'], // 🚀 Recarga parcial para no saturar tu backend
    }
  );
};

// 3. Manejo del cambio de cantidad de filas por página
const changePerPage = (event: any) => {
  router.get(
    propsW.pag?.meta?.path ?? window.location.pathname,
    {
      search: propsW.search,
      per_page: event.rows, // PrimeVue inyecta el nuevo límite aquí
      page: 1, // Al cambiar de filas, solemos reiniciar a la página 1
    },
    {
      preserveState: true,
      preserveScroll: true,
      only: ['products'],
    }
  );
};

// 4. Casteo riguroso a números reales para evitar el "Expected Number, got String"
const getRows = computed(() => {
  return Number(propsW.pag?.meta?.per_page) || 15;
});

const getTotalRecords = computed(() => {
  return Number(propsW.pag?.meta?.total) || 0;
});
</script>

<template>
  <!-- Usamos @page para escuchar de forma unificada los cambios de PrimeVue -->
  <Paginator
    :first="first"
    :rows="getRows"
    :totalRecords="getTotalRecords"
    :rowsPerPageOptions="[15, 30, 45, 60, 85, 100]"
    @page="onPageChange"
  />
</template>
