<script setup lang="ts">
import { Column, DataTable } from 'primevue';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';

const propsW = defineProps<{
  warehouses: Array<WarehouseBaseI>;
}>();
</script>

<template>
  <DataTable :value="propsW.warehouses">
    <Column header="PREFIX" field="prefix"></Column>
    <Column header="NOMBRE" field="name"></Column>
    <Column header="STOCK">
      <template #body> </template>
    </Column>
    <Column header="COMPREMITIDO"> </Column>
    <Column header="PEDIDO"></Column>
    <Column header="STOCK MINIMO"></Column>
    <Column header="STOCK MAXIMO"></Column>
    <Column header="NIVEL NESARIO"></Column>
  </DataTable>
</template>

<style scoped></style>
