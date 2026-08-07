<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted, provide, ref, watch } from 'vue';
import { SupplierI } from '@/Interfaces/SupplierInterface';
import { ProductTableI, ProductTypeEnumI } from '@/Interfaces/ProductInterface';
import { categoryBaseI } from '@/Interfaces/CategoriesInterface';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { Dialog } from 'primevue';
import { PaginationI, PaymentTypeEnumI } from '@/Interfaces/GlobalInterface';
import FRegister from '@/Pages/Products/FRegister.vue';
import { productDataKey, taxCurrentValueKey } from '@/Injections/InjectionKeys';
import { BranchInterfaceI } from '@/Interfaces/BranchInterface';
import { UnitInterfaceI } from '@/Interfaces/UnitInterface';
import { TaxBaseI } from '@/Interfaces/TaxInterface';
import FShowProduct from '@/Pages/Products/FShowProduct.vue';
import { PriceListWTI } from '@/Interfaces/PriceListInterface';

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
const createProduct = ref(false);
const isUpdate = ref(false);

provide(productDataKey, propsW.products.data ?? []);
provide(taxCurrentValueKey, taxCurrentValue);

onMounted(() => {});

const clearCreate = () => {
  selectedProduct.value = null;
  isUpdate.value = false;
};

watch(
  () => selectedProduct.value,
  (newValue) => {
    isUpdate.value = !!newValue;
  }
);
</script>

<template>
  <AppLayout>
    <div class="w-full px-2 sm:px-4 py-4 max-w-7xl mx-auto">
      <!-- Tabla / Vista Principal de Productos -->
      <div class="shadow-sm rounded-lg overflow-hidden border border-slate-200 bg-white">
        <FShowProduct
          v-model:createProduct="createProduct"
          v-model:selectedProduct="selectedProduct"
          :products="propsW.products"
        />
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
            :productEdit="selectedProduct"
            :update="isUpdate"
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
