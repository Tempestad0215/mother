<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { CriticalProduct, KPI, ProductLowStock, TopProduct } from '@/Interfaces/DashboardInterface';
import Chart from 'primevue/chart';
import { Card, Column, DataTable, Tag } from 'primevue';
import { onMounted, ref } from 'vue';

const propsW = defineProps<{
  kpis: KPI;
  productsLowStock: ProductLowStock[];
  topProduct: Array<TopProduct>;
}>();

const chartData = ref<any>(null);
const chartOptions = ref<any>(null);

onMounted(() => {
  chartData.value = setChartData();
  chartOptions.value = setChartOptions();
});

const setChartData = () => {
  return {
    labels: Object.keys(propsW.kpis || {}).map((key) => {
      return key.replace(/_/g, ' ').toUpperCase();
    }),
    datasets: [
      {
        data: Object.values(propsW.kpis || {}),
        backgroundColor: ['#6366F1', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'],
        hoverBackgroundColor: ['#4F46E5', '#059669', '#D97706', '#DC2626', '#7C3AED'],
        borderWidth: 2,
        borderColor: '#ffffff',
      },
    ],
  };
};

const setChartOptions = () => {
  return {
    plugins: {
      legend: {
        position: 'bottom',
        labels: {
          usePointStyle: true,
          font: {
            family: 'Inter, sans-serif',
            size: 12,
            weight: '500',
          },
          padding: 15,
        },
      },
    },
    maintainAspectRatio: false,
    responsive: true,
  };
};
</script>

<template>
  <AppLayout title="Dashboard">
    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-50/50 min-h-screen space-y-6">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        <Card class="shadow-sm border border-gray-100 rounded-xl overflow-hidden">
          <template #title>
            <div class="flex items-center gap-2 px-2 pt-2 text-gray-800">
              <span class="p-2 bg-red-50 text-red-600 rounded-lg text-sm font-semibold">⚠️</span>
              <h3 class="font-bold text-lg">Stock Crítico</h3>
            </div>
          </template>
          <template #content>
            <div v-if="propsW.productsLowStock.length > 0" class="space-y-4">
              <div
                v-for="(warehouse, key) in propsW.productsLowStock"
                :key="key"
                class="border border-gray-100 rounded-lg p-3 bg-white space-y-2"
              >
                <div class="flex justify-between items-center mb-1">
                  <span class="text-xs font-bold text-gray-500 uppercase tracking-wider"
                    >Almacén</span
                  >
                  <Tag :value="warehouse.warehouse_name" severity="warn" class="text-xs" />
                </div>

                <DataTable
                  :value="warehouse.critical_products"
                  size="small"
                  stripedRows
                  class="text-xs"
                >
                  <Column header="CÓDIGO" field="product_code">
                    <template #body="{ data }">
                      <span class="font-mono text-gray-600">{{ data.product_code }}</span>
                    </template>
                  </Column>
                  <Column header="PRODUCTO" field="product_name">
                    <template #body="{ data }">
                      <span class="font-medium text-gray-800 truncate block max-w-[120px]">{{
                        data.product_name
                      }}</span>
                    </template>
                  </Column>
                  <Column header="STOCK" class="text-right">
                    <template #body="{ data }: { data: CriticalProduct }">
                      <Tag severity="danger" :value="data.stock_quantity" class="font-bold" />
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
            <div v-else class="text-center py-8 text-gray-400 text-sm">
              No hay productos con stock crítico.
            </div>
          </template>
        </Card>

        <Card class="shadow-sm border border-gray-100 rounded-xl overflow-hidden">
          <template #title>
            <div class="flex items-center gap-2 px-2 pt-2 text-gray-800">
              <span class="p-2 bg-indigo-50 text-indigo-600 rounded-lg text-sm font-semibold"
                >🔥</span
              >
              <h3 class="font-bold text-lg">Top Más Vendidos</h3>
            </div>
          </template>
          <template #content>
            <DataTable
              v-if="propsW.topProduct.length > 0"
              :value="propsW.topProduct"
              size="small"
              stripedRows
              class="text-xs"
            >
              <Column header="CÓDIGO" field="code">
                <template #body="{ data }">
                  <span class="font-mono text-gray-500">{{ data.code }}</span>
                </template>
              </Column>
              <Column header="NOMBRE" field="name">
                <template #body="{ data }">
                  <span class="font-semibold text-gray-700 truncate block max-w-[140px]">{{
                    data.name
                  }}</span>
                </template>
              </Column>
              <Column header="VENDIDOS" field="total_qty" class="text-right">
                <template #body="{ data }">
                  <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">
                    {{ data.total_qty }} u.
                  </span>
                </template>
              </Column>
            </DataTable>
            <div v-else class="text-center py-8 text-gray-400 text-sm">
              Sin datos de ventas registradas.
            </div>
          </template>
        </Card>

        <Card class="shadow-sm border border-gray-100 rounded-xl overflow-hidden">
          <template #title>
            <div class="flex items-center gap-2 px-2 pt-2 text-gray-800">
              <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg text-sm font-semibold"
                >📈</span
              >
              <h3 class="font-bold text-lg">Métricas del Día</h3>
            </div>
          </template>
          <template #content>
            <div
              v-if="Object.values(propsW.kpis || {}).some((val) => val > 0)"
              class="flex flex-col items-center justify-center p-2"
            >
              <div class="w-full h-[260px] flex justify-center items-center">
                <Chart
                  type="doughnut"
                  :data="chartData"
                  :options="chartOptions"
                  class="w-full h-full"
                />
              </div>
            </div>
            <div v-else class="text-center py-12 text-gray-400 text-sm">
              No hay métricas registradas en el día de hoy.
            </div>
          </template>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>
