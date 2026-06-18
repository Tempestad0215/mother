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

const first = computed(() => {
  const currentPage = propsW.pag?.meta.current_page ?? 1;
  const perPage = propsW.pag?.meta.per_page ?? 10;
  return (currentPage - 1) * perPage;
});

const onPageChange = (_: number) => {
  const perPage = propsW.pag.meta.per_page;

  router.get(`${propsW.pag?.meta.path}?search=${propsW.search}&per_page=${perPage}`);
};

const changePerPage = (value: number) => {
  router.get(`${propsW.pag.meta.path}?search=${propsW.search}&per_page=${value}`);
};
</script>

<template>
  <Paginator
    :first="first"
    @update:first="onPageChange"
    @update:rows="changePerPage"
    :rowsPerPageOptions="[15, 30, 45, 60, 85, 100]"
    :rows="propsW.pag?.meta.per_page ?? 0"
    :totalRecords="propsW.pag?.meta.total ?? 0"
  >
  </Paginator>
</template>
