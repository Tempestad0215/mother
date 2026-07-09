<script setup lang="ts">
import { SaleBreadCrumbs, saleTypeOptions } from '@/Helpers/SaleHelper';
import {
  useToast,
  Card,
  DataTable,
  Column,
  Button,
  Select,
  DatePicker,
  FloatLabel,
  Divider,
} from 'primevue';
import { saleDataI } from '@/Interfaces/SaleInterface';
import { Eye, Printer } from '@lucide/vue';
import { getMoney, printPdf } from '@/Global/Helpers';
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';

const toast = useToast();

const propsW = defineProps<{
  filters?: any;
  sales: saleDataI[];
}>();

const form = useForm({
  from: new Date() as Date | null,
  to: new Date() as Date | null,
  type: null,
});

const submit = () => {
  form.post(route('sale.get-sold'), {
    onError: (err) => {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'No se pudo obtener la informacion',
        life: 5000,
      });
    },
  });
};
</script>

<template>
  <AppLayout>
    <Card>
      <template #title>
        <h3 class="text-2xl font-bold text-center">Ventas</h3>
        <Divider />
      </template>
      <template #header>
        <BreadCrumbComponent :itemOptions="SaleBreadCrumbs" />
      </template>
      <template #content>
        <DataTable :value="propsW.sales">
          <template #header>
            <form @submit.prevent="submit()" class="flex items-center gap-3" action="">
              <FloatLabel variant="on">
                <DatePicker v-model="form.from" />
                <label for="from">Desde</label>
              </FloatLabel>
              <FloatLabel variant="on">
                <DatePicker v-model="form.to" />
                <label for="to">Hasta</label>
              </FloatLabel>
              <FloatLabel variant="on">
                <Select
                  v-model="form.type"
                  class="w-40"
                  option-label="name"
                  option-value="value"
                  :options="saleTypeOptions"
                />
                <label for="type">Tipo</label>
              </FloatLabel>
              <Button type="submit" label="Buscar" />
            </form>
          </template>
          <Column class="w-5" header="#">
            <template #body="{ index }: { index: number }">
              {{ index + 1 }}
            </template>
          </Column>
          <Column header="N° Factura" field="code" />
          <Column header="Cliente" field="client_name" />
          <Column header="Itbis" field="tax">
            <template #body="{ data }: { data: saleDataI }">
              {{ getMoney(data.tax) }}
            </template>
          </Column>
          <Column header="Sub Total" field="sub_total">
            <template #body="{ data }: { data: saleDataI }">
              {{ getMoney(data.sub_total) }}
            </template>
          </Column>
          <Column header="Total" field="amount">
            <template #body="{ data }: { data: saleDataI }">
              {{ getMoney(data.sub_total) }}
            </template>
          </Column>
          <Column header="F. Creacion" field="created_at" />
          <Column header="ACT">
            <template #body="{ data }: { data: saleDataI }">
              <div class="flex items-center gap-3">
                <!--                <Eye />-->
                <Printer @click="printPdf(route('invoice.sale', { sale: data.uuid }))" />
              </div>
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>
  </AppLayout>
</template>

<style scoped></style>
