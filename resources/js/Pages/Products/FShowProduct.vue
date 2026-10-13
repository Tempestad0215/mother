<script setup lang="ts">
import { ProductTableI } from '@/Interfaces/ProductInterface';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useRoute } from 'ziggy-js';
import { Column, DataTable, InputGroup, InputGroupAddon, InputText, Tag, useToast } from 'primevue';
import { getInfoFromWarehouse, productBreadCrumb } from '@/Helpers/ProductHelper';
import { PreciseCalculator } from '@/utils/Decimal';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { onPageChange, paginationOptions } from '@/Global/Helpers';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';
import { PriceListBaseI, PriceListProducts } from '@/Interfaces/PriceListInterface';

const toast = useToast();
const route = useRoute();

const { component } = usePage();

interface PropsI {
  products: PaginationI<ProductTableI>;
  stock?: boolean;
  isProduct?: boolean;
  mode?: 'CRUD' | 'SELECT';
  searchRoute?: string; // nombre de la ruta Ziggy a la que buscar
  searchRouteParams?: Record<string, unknown>; // params extra opcionales
  searchOnly?: string[];
}

const propsW = withDefaults(defineProps<PropsI>(), {
  stock: false,
  isProduct: true,
  mode: 'CRUD',
  searchRoute: undefined,
  searchRouteParams: () => ({}),
  searchOnly: () => ['products'],
});

defineEmits<{
  (e: 'selectData', data: ProductTableI): void;
}>();

const searchValue = ref('');

const searchData = () => {
  if (!propsW.searchRoute) {
    toast.add({
      severity: 'warn',
      summary: 'Please enter a valid search route',
      life: 3500,
    });
    return;
  }

  router.get(
    route(propsW.searchRoute, {
      ...propsW.searchRouteParams,
      search: searchValue.value,
      per_page: propsW.products.meta.per_page,
      page: 1, // 🔑 al buscar, volver siempre a la página 1
    }),
    {},
    {
      preserveState: true,
      preserveScroll: true,
      only: propsW.searchOnly,
    }
  );
};

const getPrice = (uuid: string, priceLists: PriceListProducts[]): number => {
  return priceLists.find((el) => el.uuid === uuid)?.price ?? 0.0;
};
</script>

<template>
  <DataTable
    lazy
    @page="onPageChange($event, '', ['products'])"
    paginator
    responsiveLayout="stack"
    breakpoint="768px"
    :rowsPerPageOptions="paginationOptions"
    :total-records="Number(propsW.products.meta.total)"
    :first="(propsW.products.meta.current_page - 1) * Number(propsW.products.meta.per_page)"
    :rows="Number(propsW.products.meta.per_page) ?? 0"
    :loading="!propsW.products.data"
    :value="propsW.products.data"
    class="shadow-sm rounded-lg overflow-hidden border border-slate-200"
  >
    <!-- Encabezado Adaptativo -->
    <template #header>
      <div class="space-y-3 p-1">
        <!-- Breadcrumb opcional -->
        <div v-if="propsW.isProduct" class="overflow-x-auto">
          <BreadCrumbComponent
            :itemOptions="productBreadCrumb"
            class="text-xs sm:text-sm p-0 bg-transparent"
          />
        </div>

        <!-- Buscador y Botón Nuevo -->
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
          <form @submit.prevent="searchData" class="w-full sm:w-auto">
            <InputGroup class="w-full sm:w-72">
              <InputText
                v-model="searchValue"
                placeholder="Buscar producto..."
                type="search"
                class="w-full"
              />
              <InputGroupAddon
                @click="searchData"
                class="cursor-pointer hover:bg-slate-100 transition"
              >
                <i class="pi pi-search"></i>
              </InputGroupAddon>
            </InputGroup>
          </form>
        </div>
      </div>
    </template>

    <!-- Columnas -->
    <Column field="code" header="Código" class="font-semibold text-slate-700" />
    <Column field="name" header="Nombre" />

    <Column v-if="component === 'Products/Register'" header="Costo">
      <template #body="{ data }: { data: ProductTableI }">
        <span>{{ PreciseCalculator.formatCurrency(data.cost) }}</span>
      </template>
    </Column>

    <Column header="Precio">
      <template #body="{ data }: { data: ProductTableI }">
        <span class="font-bold text-slate-900">
          {{
            PreciseCalculator.formatCurrency(getPrice(data.default_price_list, data.price_lists))
          }}
        </span>
      </template>
    </Column>

    <Column header="Tipo">
      <template #body="{ data }: { data: ProductTableI }">
        <Tag
          :severity="data.is_service ? 'warn' : 'info'"
          :value="data.is_service ? 'Servicio' : 'Producto'"
        />
      </template>
    </Column>

    <Column v-if="propsW.stock" header="Stock">
      <template #body="{ data }: { data: ProductTableI }">
        <span class="font-medium">
          {{ getInfoFromWarehouse(data.warehouses, data.default_warehouse)?.available ?? 0 }}
        </span>
      </template>
    </Column>

    <!-- Acciones -->
    <Column header="Acciones">
      <template #body="{ data }: { data: ProductTableI }">
        <slot name="actions" :data="data" />
      </template>
    </Column>
  </DataTable>
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
