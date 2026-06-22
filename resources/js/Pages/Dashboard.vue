<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { KPI, ProductLowStock, TopProduct } from '@/Interfaces/DashboardInterface';
import { ProductBaseI } from '@/Interfaces/ProductInterface';
import Chart from 'primevue/chart';
import { Card, DataTable, Column } from 'primevue';
import { onMounted, ref } from 'vue';

const propsW = defineProps<{
  kpis: KPI;
  productsLowStock: Array<ProductBaseI>;
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
    <div class="py-12 bg-gray-50">
      <Card>
        <template #content>
          <DataTable :value="propsW.productsLowStock">
            <Column header="CODIGO" value="code" />
            <Column header="NOMBRE" value="name" />
            <Column header="STOCK" value="" />
          </DataTable>
        </template>
      </Card>
      <Card>
        <template #content>
          <DataTable :value="propsW.topProduct">
            <Column header="CODIGO" />
            <Column header="NOMBRE" />
            <Column header="STOCK" />
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
