<script setup lang="ts">
import { SaleBreadCrumbs } from '@/Helpers/SaleHelper';
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
import { saleDataI, SaleSoldFilterI, SaleTypeEnum } from '@/Interfaces/SaleInterface';
import { Printer, Search, ReceiptText } from '@lucide/vue';
import { getMoney, printPdf } from '@/Global/Helpers';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';
import { EnumValueI } from '@/Interfaces/GeneralInterface';
import { onMounted } from 'vue';
import { useRoute } from 'ziggy-js';

const toast = useToast();

const propsW = defineProps<{
  filters?: SaleSoldFilterI;
  saleTypes: EnumValueI[];
  paymentTypes: EnumValueI[];
  sales: saleDataI[];
}>();

const form = useForm({
  from: new Date() as Date | null,
  to: new Date() as Date | null,
  type_sale: 'VENTAS' as SaleTypeEnum,
  type_payment: 'TODO',
});

onMounted(() => {
  if (propsW.filters && Object.keys(propsW.filters).length > 0) {
    Object.assign(form, propsW.filters);
  }
});

const submit = () => {
  form.post(route('sale.get-sold'), {
    preserveScroll: true,
    preserveState: true,
    onError: () => {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'No se pudo obtener la información de las ventas.',
        life: 5000,
      });
    },
  });
};

const convert = (uuid: string) => {
  router.get(route('sale.convert', uuid));
};
</script>

<template>
  <AppLayout>
    <div class="w-full px-2 sm:px-4 py-4 max-w-7xl mx-auto">
      <Card class="shadow-sm rounded-lg border border-slate-200">
        <template #header>
          <div class="p-3 pb-0">
            <BreadCrumbComponent :itemOptions="SaleBreadCrumbs" />
          </div>
          <Divider class="my-2" />
        </template>

        <template #title>
          <h3 class="text-xl sm:text-2xl font-bold text-center text-slate-800">
            Ventas Realizadas
          </h3>
          <Divider class="my-3" />
        </template>

        <template #content>
          <DataTable
            :value="propsW.sales"
            responsiveLayout="stack"
            breakpoint="768px"
            paginator
            :rows="15"
            striped-rows
            class="shadow-sm rounded-lg overflow-hidden border border-slate-200"
          >
            <!-- Formulario de Filtros en Cabecera -->
            <template #header>
              <form
                @submit.prevent="submit"
                class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 p-1"
              >
                <FloatLabel variant="on" class="w-full sm:w-auto flex-1">
                  <DatePicker id="from" v-model="form.from" dateFormat="yy-mm-dd" class="w-full" />
                  <label for="from">Desde</label>
                </FloatLabel>

                <FloatLabel variant="on" class="w-full sm:w-auto flex-1">
                  <DatePicker id="to" v-model="form.to" dateFormat="yy-mm-dd" class="w-full" />
                  <label for="to">Hasta</label>
                </FloatLabel>

                <FloatLabel variant="on">
                  <Select
                    v-model="form.type_sale"
                    :options="propsW.saleTypes"
                    optionLabel="label"
                    optionValue="value"
                  />
                  <label for="type_sale">Tipo Venta</label>
                </FloatLabel>

                <FloatLabel variant="on" class="w-full sm:w-48">
                  <Select
                    id="type"
                    v-model="form.type_payment"
                    class="w-full"
                    option-label="label"
                    option-value="value"
                    :options="propsW.paymentTypes"
                  />
                  <label for="type">Tipo Venta</label>
                </FloatLabel>

                <Button
                  type="submit"
                  label="Buscar"
                  :loading="form.processing"
                  class="w-full sm:w-auto h-10 px-6 bg-emerald-600 hover:bg-emerald-700 border-none"
                >
                  <template #icon>
                    <Search class="w-4 h-4 mr-1" />
                  </template>
                </Button>
              </form>
            </template>

            <!-- Columnas -->
            <Column header="#" class="w-12 text-center">
              <template #body="{ index }: { index: number }">
                <span class="font-medium text-slate-500">{{ index + 1 }}</span>
              </template>
            </Column>

            <Column header="N° Factura" field="code" class="font-semibold text-slate-700" />

            <Column header="Cliente" field="client_name" />
            <Column header="Tipo" field="type" />

            <Column header="ITBIS">
              <template #body="{ data }: { data: saleDataI }">
                <span class="text-blue-600 font-medium">{{ getMoney(data.tax) }}</span>
              </template>
            </Column>

            <Column header="Sub Total">
              <template #body="{ data }: { data: saleDataI }">
                <span>{{ getMoney(data.sub_total) }}</span>
              </template>
            </Column>

            <Column header="Total">
              <template #body="{ data }: { data: saleDataI }">
                <span class="font-bold text-slate-900">{{ getMoney(data.amount) }}</span>
              </template>
            </Column>

            <Column header="F. Creación" field="created_at" />

            <Column header="Acciones">
              <template #body="{ data }: { data: saleDataI }">
                <div class="flex items-center gap-2 pt-1 sm:pt-0">
                  <Button
                    v-if="data.type === 'COTIZACION'"
                    @click="convert(data.uuid)"
                    severity="secondary"
                    outlined
                    class="h-9 w-9 p-0 flex items-center justify-center"
                    title="Convertir a Factura"
                  >
                    <template #icon>
                      <ReceiptText class="w-4 h-4 text-slate-700" />
                    </template>
                  </Button>
                  <Button
                    @click="printPdf(route('invoice.sale', { sale: data.uuid }))"
                    severity="secondary"
                    outlined
                    class="h-9 w-9 p-0 flex items-center justify-center"
                    title="Imprimir Factura"
                  >
                    <template #icon>
                      <Printer class="w-4 h-4 text-slate-700" />
                    </template>
                  </Button>
                </div>
              </template>
            </Column>
          </DataTable>
        </template>
      </Card>
    </div>
  </AppLayout>
</template>

<style scoped>
:deep(.p-datatable-tbody > tr > td) {
  padding: 0.75rem 1rem;
}

@media (max-width: 768px) {
  :deep(.p-datatable-stacked .p-datatable-tbody > tr > td) {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e2e8f0;
  }
}
</style>
