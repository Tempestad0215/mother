<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import { Breadcrumb, Card, Column, DataTable, DatePicker, FloatLabel, Textarea } from 'primevue';
import { purchaseBreadCrumb } from '@/Helpers/PurchaseHelper';
</script>

<template>
  <AppLayout>
    <Card>
      <template #title>
        <Breadcrumb :model="purchaseBreadCrumb" />
      </template>
      <template #content>
        <form action="">
          <div>
            <FloatLabel variant="on">
              <DatePicker />
            </FloatLabel>
          </div>
          <DataTable>
            <Column header="#" />
            <Column header="Codigo" />
            <Column header="Nombre" />
            <Column header="Costo" />
            <Column header="Cantidad" />
            <Column header="Almacen" />
            <Column header="Descuento" />
            <Column header="Itbis" />
            <Column header="Importe" />
          </DataTable>

          <FloatLabel variant="on">
            <Textarea class="min-h-15 max-h-30" :rows="4" :cols="50" />
            <label for="comment">Comentario</label>
          </FloatLabel>
        </form>
      </template>
    </Card>
  </AppLayout>
</template>
