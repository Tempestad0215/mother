<script setup lang="ts">
import { SaleBreadCrumbs } from '@/Helpers/SaleHelper';
import { MenuItemI } from '@/Interfaces/GlobalInterface';
import { Link } from '@inertiajs/vue3';
import { Breadcrumb } from 'primevue';

const propsW = defineProps<{
  itemOptions: MenuItemI[];
}>();
</script>

<template>
  <Breadcrumb :model="itemOptions">
    <template #item="{ item }: { item: MenuItemI }">
      <Link :href="item.url" class="flex items-center">
        <component :size="20" :is="item.iconComponent" />
        <span>{{ item.label }}</span>
      </Link>
    </template>
  </Breadcrumb>
</template>

<style scoped></style>
