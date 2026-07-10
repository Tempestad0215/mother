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

// Asignamos los datos cuando el componente se monta
onMounted(() => {
  chartData.value = setCharData();
});

const setCharData = () => {
  return {
    labels: Object.keys(propsW.kpis).map((key) => {
      return key.replace('_', ' ').toUpperCase();
    }),
    datasets: [
      {
        data: Object.values(propsW.kpis),
        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
        hoverBackgroundColor: ['#0891b2', '#ea580c', '#dc2626'],
      },
    ],
  };
};
</script>

<template>
  <AppLayout title="Dashboard">
    <div class="py-12 bg-gray-50 flex gap-5 items-center justify-between">
      <Card v-if="propsW.productsLowStock.length > 0" class="flex-1">
        <template #header>
          <h3 class="text-center font-bold text-2xl">Producto Stock Low</h3>
        </template>
        <template #content>
          <div v-for="(value, key) in propsW.productsLowStock" :key="key">
            <Tag :value="value.warehouse_name" severity="info" />
            <DataTable :value="value.critical_products">
              <Column header="CODIGO" field="product_code" />
              <Column header="NOMBRE" field="product_name" />
              <Column header="STOCK">
                <template #body="{ data }: { data: CriticalProduct }">
                  <Tag severity="danger" :value="data.stock_quantity" />
                </template>
              </Column>
            </DataTable>
          </div>
        </template>
      </Card>
      <Card class="mt-5 flex-1" v-if="propsW.topProduct.length > 0">
        <template #header>
          <h4 class="text-center font-bold text-2xl">Productos Mas Vendidos</h4>
        </template>
        <template #content>
          <DataTable :value="propsW.topProduct">
            <Column header="CODIGO" field="code" />
            <Column header="NOMBRE" field="name" />
            <Column header="STOCK" field="total_qty" />
          </DataTable>
        </template>
      </Card>

      <div
        v-if="Object.values(propsW.kpis).some((val) => val > 0)"
        class="max-w-7xl mx-auto sm:px-6 lg:px-8"
      >
        <div class="bg-white p-6 shadow-xl sm:rounded-lg flex flex-col items-center">
          <h3 class="text-lg font-bold text-gray-700 mb-4">Métricas del Día</h3>

          <div class="w-full md:w-120 flex justify-center">
            <!-- 🚀 PASAMOS chartData AQUÍ -->
            <Chart type="doughnut" :data="chartData" class="w-full" />
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
