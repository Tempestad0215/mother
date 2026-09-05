<script setup lang="ts">
import Pagination from '@components/Pagination.vue';
import { ProductBaseI, ProductTableI } from '@/Interfaces/ProductInterface';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useRoute } from 'ziggy-js';
import {
  Breadcrumb,
  Button,
  Column,
  DataTable,
  DataTablePageEvent,
  InputGroup,
  InputGroupAddon,
  InputText,
  useConfirm,
  useToast,
} from 'primevue';
import { getInfoFromWarehouse, productBreadCrumb } from '@/Helpers/ProductHelper';
import { PreciseCalculator } from '@/utils/Decimal';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { FilePenLine, PackagePlus, Shredder, CheckCircle } from '@lucide/vue';
import { onPageChange, paginationOptions } from '@/Global/Helpers';

const toast = useToast();
const confirm = useConfirm();
const route = useRoute();

const { component } = usePage();

interface PropsI {
  products: PaginationI<ProductTableI>;
  stock?: boolean;
  isProduct?: boolean;
}

const propsW = withDefaults(defineProps<PropsI>(), {
  stock: false,
  isProduct: true,
});

defineEmits<{
  (e: 'selectData', data: ProductTableI): void;
}>();

const selectedProduct = defineModel<ProductTableI | null>('selectedProduct', {
  default: null,
});
const searchValue = ref('');
const createProduct = defineModel<boolean>('createProduct', {
  default: false,
});
const isUpdate = ref(false);

const searchData = () => {
  router.get(
    route('product.index', {
      search: searchValue.value,
      per_page: propsW.products.meta.per_page,
      page: propsW.products.meta.current_page,
    }),
    {},
    {
      preserveState: true,
      preserveScroll: true,
      only: ['products'],
    }
  );
};

const editData = (data: ProductTableI) => {
  selectedProduct.value = data;
  isUpdate.value = true;
  createProduct.value = true;
};

const deleteData = (data: ProductTableI, event: Event) => {
  confirm.require({
    target: event.currentTarget as HTMLElement,
    message: '¿Desea eliminar este producto? Los cambios son irreversibles.',
    header: 'Confirmar Eliminación',
    icon: 'pi pi-exclamation-triangle',
    rejectProps: {
      label: 'Cancelar',
      severity: 'secondary',
      outlined: true,
    },
    acceptProps: {
      label: 'Eliminar',
      severity: 'danger',
    },
    accept: () => {
      router.delete(route('product.destroy', { product: data.uuid }), {
        onSuccess: () => {
          toast.add({
            severity: 'success',
            summary: 'Eliminado',
            detail: 'El producto fue eliminado correctamente.',
            life: 3000,
          });
        },
      });
    },
  });
};

// const onPageChange = (event: DataTablePageEvent) => {
//   console.log(event);
//
//   const perPage = paginationOptions.includes(event.rows) ? event.rows : paginationOptions[0];
//   const page = event.page + 1;
//
//   router.get(
//     route('product.index'),
//     {
//       page: page,
//       per_page: perPage,
//     },
//     {
//       preserveState: true,
//       preserveScroll: true,
//       only: ['products'],
//     }
//   );
// };
</script>

<template>
  <DataTable
    lazy
    @page="onPageChange($event, route('product.index'), ['products'])"
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
          <Breadcrumb :model="productBreadCrumb" class="text-xs sm:text-sm p-0 bg-transparent" />
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

          <Button
            v-if="component != 'Sale/SaleCreate'"
            class="w-full sm:w-auto justify-center h-10 px-4 bg-emerald-600 hover:bg-emerald-700 border-none"
            label="Nuevo Producto"
            @click="createProduct = true"
          >
            <template #icon>
              <PackagePlus class="w-5 h-5 mr-1" />
            </template>
          </Button>
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
        <span class="font-bold text-slate-900" :class="{ 'text-red-500': data.price <= 0 }">
          {{ PreciseCalculator.formatCurrency(data.price) }}
        </span>
      </template>
    </Column>

    <Column header="Tipo">
      <template #body="{ data }: { data: ProductTableI }">
        <span>{{ data.is_service ? 'Servicio' : 'Producto' }}</span>
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
        <div class="flex items-center gap-2 pt-1 sm:pt-0">
          <Button
            v-if="component != 'Sale/SaleCreate'"
            @click="editData(data)"
            class="p-button-outlined p-button-sm h-9 w-9 p-0 flex items-center justify-center"
            title="Editar"
          >
            <template #icon>
              <FilePenLine class="w-4 h-4 text-blue-600" />
            </template>
          </Button>

          <Button
            v-if="component != 'Sale/SaleCreate'"
            @click="deleteData(data, $event)"
            class="p-button-outlined p-button-sm h-9 w-9 p-0 flex items-center justify-center"
            title="Eliminar"
            severity="danger"
          >
            <template #icon>
              <Shredder class="w-4 h-4 text-red-600" />
            </template>
          </Button>

          <Button
            v-if="component == 'Sale/SaleCreate'"
            @click="$emit('selectData', data)"
            class="p-button-outlined p-button-sm h-9 px-3 flex items-center justify-center"
            title="Seleccionar"
            severity="secondary"
            label="Seleccionar"
          >
            <template #icon>
              <CheckCircle class="w-4 h-4 mr-1 text-emerald-600" />
            </template>
          </Button>
        </div>
      </template>
    </Column>

    <!-- Paginador -->
    <!--    <template #paginatorcontainer>-->
    <!--      <div class="p-2 border-t border-slate-200">-->
    <!--        <Pagination :search="searchValue" :pag="propsW.products" />-->
    <!--      </div>-->
    <!--    </template>-->
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
