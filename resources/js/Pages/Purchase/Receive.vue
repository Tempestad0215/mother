<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import {
  Breadcrumb,
  Button,
  Card,
  Column,
  DataTable,
  DatePicker,
  Divider,
  FloatLabel,
  Textarea,
} from 'primevue';
import { purchaseBreadCrumb } from '@/Helpers/PurchaseHelper';
import { Eraser, Send } from '@lucide/vue';
</script>

<template>
  <AppLayout>
    <div class="w-full px-2 sm:px-4 py-4 max-w-7xl mx-auto">
      <Card class="shadow-sm rounded-lg border border-slate-200">
        <template #title>
          <div class="space-y-2">
            <Breadcrumb :model="purchaseBreadCrumb" class="text-xs sm:text-sm p-0 bg-transparent" />
            <h3 class="text-xl sm:text-2xl font-bold text-center text-slate-800">
              Recepción / Entrada de Mercancía
            </h3>
          </div>
          <Divider class="my-3" />
        </template>

        <template #content>
          <form class="space-y-5" @submit.prevent>
            <!-- Selección de Fecha Documento -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <FloatLabel variant="on" class="w-full">
                <DatePicker id="doc_date" dateFormat="yy-mm-dd" class="w-full" />
                <label for="doc_date">Fecha de Recepción</label>
              </FloatLabel>
            </div>

            <!-- Tabla de Productos a Recibir (Scroll Horizontal en Móvil) -->
            <div class="overflow-x-auto rounded-lg border border-slate-200 shadow-sm">
              <DataTable
                size="small"
                striped-rows
                show-gridlines
                class="min-w-[800px] w-full text-xs sm:text-sm"
              >
                <Column header="#" class="w-12 text-center" />
                <Column header="Código" class="w-28" />
                <Column header="Nombre / Descripción" class="min-w-[200px]" />
                <Column header="Costo" class="w-28" />
                <Column header="Cantidad" class="w-24" />
                <Column header="Almacén" class="w-32" />
                <Column header="Descuento" class="w-24" />
                <Column header="ITBIS" class="w-24" />
                <Column header="Importe" class="w-28 font-bold" />
              </DataTable>
            </div>

            <!-- Comentario de Entrada -->
            <FloatLabel variant="on" class="w-full">
              <Textarea
                id="comment"
                class="w-full min-h-[80px] max-h-[150px]"
                rows="3"
                autoResize
              />
              <label for="comment">Comentario u Observaciones</label>
            </FloatLabel>

            <!-- Botones de Acción Adaptativos -->
            <div class="pt-2 flex flex-col-reverse sm:flex-row justify-end gap-3">
              <Button
                type="reset"
                severity="warn"
                label="Limpiar"
                class="w-full sm:w-auto h-10"
                outlined
              >
                <template #icon>
                  <Eraser class="w-4 h-4 mr-1" />
                </template>
              </Button>

              <Button
                type="submit"
                label="Procesar Entrada"
                class="w-full sm:w-auto h-10 bg-emerald-600 hover:bg-emerald-700 border-none"
              >
                <template #icon>
                  <Send class="w-4 h-4 mr-1" />
                </template>
              </Button>
            </div>
          </form>
        </template>
      </Card>
    </div>
  </AppLayout>
</template>

<style scoped>
:deep(.p-datatable-sm .p-datatable-tbody > tr > td) {
  padding: 0.5rem 0.75rem;
}
</style>
