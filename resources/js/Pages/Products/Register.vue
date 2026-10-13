<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed, onMounted, provide, ref } from 'vue';
import { SupplierI } from '@/Interfaces/SupplierInterface';
import { ProductTableI, ProductTypeEnumI } from '@/Interfaces/ProductInterface';
import { categoryBaseI } from '@/Interfaces/CategoriesInterface';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { Button, Dialog } from 'primevue';
import { PaginationI, PaymentTypeEnumI } from '@/Interfaces/GlobalInterface';
import FRegister from '@/Pages/Products/FRegister.vue';
import {
  createProductKey,
  productDataKey,
  productEditKey,
  taxCurrentValueKey,
} from '@/Injections/InjectionKeys';
import { BranchInterfaceI } from '@/Interfaces/BranchInterface';
import { UnitInterfaceI } from '@/Interfaces/UnitInterface';
import { TaxBaseI } from '@/Interfaces/TaxInterface';
import FShowProduct from '@/Pages/Products/FShowProduct.vue';
import { PriceListWTI } from '@/Interfaces/PriceListInterface';
import { useProductDelete } from '@/Composable/useProductDelete';
import { PackagePlus, SquarePen, Trash } from '@lucide/vue';

// Propiedades de la ventana
const propsW = withDefaults(
  defineProps<{
    products: PaginationI<ProductTableI>;
    productEdit?: ProductTableI;
    update?: boolean;
    categories: categoryBaseI[];
    suppliers: SupplierI[];
    warehouses: WarehouseBaseI[];
    paymentTypes: PaymentTypeEnumI;
    productType: ProductTypeEnumI;
    branches: BranchInterfaceI[];
    units: UnitInterfaceI[];
    taxes: TaxBaseI[];
    priceLists: Array<PriceListWTI>;
  }>(),
  {
    update: false,
    categories: () => [],
    suppliers: () => [],
    warehouses: () => [],
    branches: () => [],
    units: () => [],
    taxes: () => [],
    priceLists: () => [],
    paymentTypes: () => ({}) as PaymentTypeEnumI,
    productType: () => ({}) as ProductTypeEnumI,
  }
);

const taxCurrentValue = ref(0);

const selectedProduct = ref<ProductTableI | null>(null);
const createProduct = ref<boolean>(false);

provide(productDataKey, propsW.products.data ?? []);
provide(taxCurrentValueKey, taxCurrentValue);
provide(productEditKey, selectedProduct);
provide(createProductKey, createProduct);

const { deleteProduct } = useProductDelete();

onMounted(() => {});

const clearCreate = () => {
  selectedProduct.value = null;
  createProduct.value = false;
};

const isUpdate = computed(() => !!selectedProduct.value);

const openEdit = (product: ProductTableI) => {
  selectedProduct.value = product;
  createProduct.value = true;
};
</script>

<template>
  <AppLayout>
    <template #header>
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-semibold tracking-tight">Productos</h1>
        <Button
          @click="createProduct = true"
          class="bg-emerald-600 hover:bg-emerald-700 border-none h-10 px-4"
        >
          <template #icon><PackagePlus class="w-5 h-5 mr-1" /></template>
        </Button>
      </div>
    </template>
    <div class="w-full px-2 sm:px-4 py-4 max-w-7xl mx-auto">
      <!-- Tabla / Vista Principal de Productos -->
      <div class="shadow-sm rounded-lg overflow-hidden border border-slate-200 bg-white">
        <!-- Header de la página -->

        <FShowProduct
          search-route="product.index"
          :searchOnly="['products']"
          :products="propsW.products"
        >
          <template #actions="{ data }: { data: ProductTableI }">
            <div class="space-x-3">
              <Button outlined @click="openEdit(data)">
                <template #icon> <SquarePen /> </template>
              </Button>
              <Button outlined severity="danger" @click="deleteProduct(data, $event)">
                <template #icon>
                  <Trash />
                </template>
              </Button>
            </div>
          </template>
        </FShowProduct>
      </div>

      <!-- Modal de Registro / Edición Adaptativo -->
      <Dialog
        v-model:visible="createProduct"
        modal
        dismissableMask
        :header="isUpdate ? 'Editar Producto' : 'Registro de Producto'"
        :breakpoints="{ '960px': '85vw', '641px': '95vw' }"
        :style="{ width: '60vw' }"
        class="p-dialog-responsive mx-2 sm:mx-0"
        @hide="clearCreate"
      >
        <div class="py-2">
          <FRegister
            :priceLists="propsW.priceLists"
            :warehouses="propsW.warehouses"
            :units="propsW.units"
            :branches="propsW.branches"
            :productType="propsW.productType"
            :paymentTypes="paymentTypes"
            :categories="propsW.categories"
            :suppliers="propsW.suppliers"
            @close="createProduct = false"
          />
        </div>
      </Dialog>
    </div>
  </AppLayout>
</template>

<style scoped>
:deep(.p-dialog-content) {
  padding: 1rem;
  max-height: 80vh;
  overflow-y: auto;
}

@media (max-width: 640px) {
  :deep(.p-dialog-content) {
    padding: 0.75rem;
    max-height: 85vh;
  }
}
</style>
