<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@layout/AppLayout.vue';
import {
  Card,
  DataTable,
  Column,
  Button,
  Select,
  DatePicker,
  FloatLabel,
  Divider,
  Tag,
  useToast,
} from 'primevue';
import {
  Search,
  FileSpreadsheet,
  Printer,
  TrendingUp,
  DollarSign,
  PackageCheck,
} from '@lucide/vue';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';
import { getMoney } from '@/Global/Helpers';

const toast = useToast();

// Métricas / KPIs del Reporte
const metrics = ref({
  totalSold: 125000.5,
  totalCost: 78000.0,
  netProfit: 47000.5,
  totalItems: 340,
});

// Mock de datos para el reporte de productos más vendidos
const reportData = ref([
  {
    code: 'PROD-001',
    product_name: 'Pintura de Óxido 1G',
    category: 'Pinturas',
    units_sold: 120,
    avg_cost: 350.0,
    avg_price: 550.0,
    total_revenue: 66000.0,
    total_cost: 42000.0,
    profit: 24000.0,
  },
  {
    code: 'PROD-002',
    product_name: 'Culata Doble HD',
    category: 'Repuestos',
    units_sold: 15,
    avg_cost: 2400.0,
    avg_price: 3800.0,
    total_revenue: 57000.0,
    total_cost: 36000.0,
    profit: 21000.0,
  },
]);

// Filtros
const filters = ref({
  from: new Date(),
  to: new Date(),
  category_uuid: null,
  warehouse_uuid: null,
});

const searchReport = () => {
  toast.add({
    severity: 'info',
    summary: 'Generando Reporte',
    detail: 'Consultando la base de datos...',
    life: 2500,
  });
};

const exportExcel = () => {
  toast.add({
    severity: 'success',
    summary: 'Exportación Exitosa',
    detail: 'Descargando archivo Excel...',
    life: 3000,
  });
};

const printReport = () => {
  window.print();
};
</script>

<template>
  <AppLayout>
    <div class="w-full px-2 sm:px-4 py-4 max-w-7xl mx-auto space-y-4">
      <!-- Breadcrumb y Título -->
      <Card class="shadow-sm rounded-lg border border-slate-200">
        <template #header>
          <div class="p-3 pb-0">
            <BreadCrumbComponent
              :itemOptions="[
                { label: 'Inicio', url: route('dashboard') },
                { label: 'Reportes', url: '#' },
                { label: 'Ventas por Producto', url: '#' },
              ]"
            />
          </div>
          <Divider class="my-2" />
        </template>

        <template #title>
          <div
            class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3"
          >
            <h3 class="text-xl sm:text-2xl font-bold text-slate-800">
              Reporte de Ventas y Margen de Ganancia
            </h3>

            <!-- Botones Exportar e Imprimir -->
            <div class="flex gap-2">
              <Button
                type="button"
                severity="success"
                outlined
                class="w-full sm:w-auto h-9 text-xs sm:text-sm justify-center"
                @click="exportExcel"
              >
                <template #icon>
                  <FileSpreadsheet class="w-4 h-4 mr-1" />
                </template>
                Excel
              </Button>

              <Button
                type="button"
                severity="secondary"
                outlined
                class="w-full sm:w-auto h-9 text-xs sm:text-sm justify-center"
                @click="printReport"
              >
                <template #icon>
                  <Printer class="w-4 h-4 mr-1 text-slate-700" />
                </template>
                Imprimir
              </Button>
            </div>
          </div>
        </template>

        <template #content>
          <!-- Filtros de Búsqueda Adaptativos -->
          <form
            @submit.prevent="searchReport"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-2"
          >
            <FloatLabel variant="on" class="w-full">
              <DatePicker id="from" v-model="filters.from" dateFormat="yy-mm-dd" fluid />
              <label for="from">Fecha Inicio</label>
            </FloatLabel>

            <FloatLabel variant="on" class="w-full">
              <DatePicker id="to" v-model="filters.to" dateFormat="yy-mm-dd" fluid />
              <label for="to">Fecha Fin</label>
            </FloatLabel>

            <FloatLabel variant="on" class="w-full">
              <Select
                id="category"
                v-model="filters.category_uuid"
                :options="[]"
                option-label="name"
                option-value="uuid"
                fluid
              />
              <label for="category">Categoría</label>
            </FloatLabel>

            <Button
              type="submit"
              label="Generar Reporte"
              class="w-full h-10 bg-emerald-600 hover:bg-emerald-700 border-none"
            >
              <template #icon>
                <Search class="w-4 h-4 mr-1" />
              </template>
            </Button>
          </form>
        </template>
      </Card>

      <!-- Tarjetas de Resumen KPI (Cards Rápidas) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <div
          class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between"
        >
          <div>
            <p class="text-xs text-slate-500 font-medium">Ventas Totales</p>
            <p class="text-lg font-bold text-slate-900">{{ getMoney(metrics.totalSold) }}</p>
          </div>
          <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
            <DollarSign class="w-6 h-6" />
          </div>
        </div>

        <div
          class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between"
        >
          <div>
            <p class="text-xs text-slate-500 font-medium">Costo Total</p>
            <p class="text-lg font-bold text-slate-700">{{ getMoney(metrics.totalCost) }}</p>
          </div>
          <div class="p-2 bg-slate-100 text-slate-600 rounded-lg">
            <PackageCheck class="w-6 h-6" />
          </div>
        </div>

        <div
          class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between"
        >
          <div>
            <p class="text-xs text-slate-500 font-medium">Ganancia Neta</p>
            <p class="text-lg font-bold text-emerald-600">{{ getMoney(metrics.netProfit) }}</p>
          </div>
          <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
            <TrendingUp class="w-6 h-6" />
          </div>
        </div>

        <div
          class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between"
        >
          <div>
            <p class="text-xs text-slate-500 font-medium">Unidades Vendidas</p>
            <p class="text-lg font-bold text-slate-800">{{ metrics.totalItems }} und.</p>
          </div>
          <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
            <PackageCheck class="w-6 h-6" />
          </div>
        </div>
      </div>

      <!-- Tabla de Datos con Layout Adaptativo (Stack) -->
      <Card class="shadow-sm rounded-lg border border-slate-200">
        <template #content>
          <DataTable
            :value="reportData"
            responsiveLayout="stack"
            breakpoint="768px"
            paginator
            :rows="10"
            striped-rows
            class="shadow-sm rounded-lg overflow-hidden border border-slate-200 text-xs sm:text-sm"
          >
            <Column field="code" header="Código" class="font-semibold text-slate-700" />

            <Column field="product_name" header="Producto" />

            <Column field="category" header="Categoría">
              <template #body="{ data }">
                <Tag severity="info" :value="data.category" class="text-xs" />
              </template>
            </Column>

            <Column header="Vendidas" class="text-center font-medium">
              <template #body="{ data }">
                <span>{{ data.units_sold }} und.</span>
              </template>
            </Column>

            <Column header="Costo Un.">
              <template #body="{ data }">
                <span>{{ getMoney(data.avg_cost) }}</span>
              </template>
            </Column>

            <Column header="Precio Un.">
              <template #body="{ data }">
                <span>{{ getMoney(data.avg_price) }}</span>
              </template>
            </Column>

            <Column header="Total Venta" class="font-semibold text-slate-800">
              <template #body="{ data }">
                <span>{{ getMoney(data.total_revenue) }}</span>
              </template>
            </Column>

            <Column header="Ganancia Neta" class="font-bold text-emerald-600">
              <template #body="{ data }">
                <span>{{ getMoney(data.profit) }}</span>
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
