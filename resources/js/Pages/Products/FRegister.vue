<script setup lang="ts">
import { ProductFormI, ProductTypeEnumI } from '@/Interfaces/ProductInterface';
import { SupplierI } from '@/Interfaces/SupplierInterface';
import { useForm } from '@inertiajs/vue3';
import { computed, onMounted, provide, watch } from 'vue';
import { categoryBaseI } from '@/Interfaces/CategoriesInterface';
import { PaymentTypeEnumI } from '@/Interfaces/GlobalInterface';
import ProductExtra from '@/Pages/Products/ProductExtra.vue';
import ProductDetail from '@/Pages/Products/ProductDetail.vue';
import ProductGeneral from '@/Pages/Products/ProductGeneral.vue';
import ProductInformation from '@/Pages/Products/ProductInformation.vue';
import { useRoute } from 'ziggy-js';
import { formProductKey } from '@/Injections/InjectionKeys';
import { BranchInterfaceI } from '@/Interfaces/BranchInterface';
import { UnitInterfaceI } from '@/Interfaces/UnitInterface';
import { Button, Card, Divider, Tab, TabList, TabPanel, TabPanels, Tabs, useToast } from 'primevue';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import ProductSaleValue from '@/Pages/Products/ProductSaleValue.vue';
import { PriceListWTI } from '@/Interfaces/PriceListInterface';
import InventoryDetail from '@/Pages/Products/Inventory/InventoryDetail.vue';
import { Eraser, Send } from '@lucide/vue';
import { useProductDialog } from '@/Composable/Products/useProductDialog';
import { mergeProductWarehouses } from '@/Helpers/ProductHelper';
import { useProductWarehouses } from '@/Composable/Products/useProductWarehouses';

const route = useRoute();
const toast = useToast();
const { selectedProduct, createProduct } = useProductDialog();

const propsW = withDefaults(
  defineProps<{
    categories: categoryBaseI[];
    suppliers: SupplierI[];
    paymentTypes: PaymentTypeEnumI;
    productType: ProductTypeEnumI;
    branches: BranchInterfaceI[];
    units: UnitInterfaceI[];
    warehouses: WarehouseBaseI[];
    priceLists: Array<PriceListWTI>;
  }>(),
  {
    productEdit: null,
    update: false,
    categories: () => [],
    suppliers: () => [],
    branches: () => [],
    units: () => [],
    warehouses: () => [],
    priceLists: () => [],
    paymentTypes: () => ({}) as PaymentTypeEnumI,
    productType: () => ({}) as ProductTypeEnumI,
  }
);

const emit = defineEmits(['showSupplier', 'close']);
const isUpdate = computed(() => !!selectedProduct.value);

const { getWarehouses } = useProductWarehouses();

const form = useForm<ProductFormI>({
  uuid: '',
  name: '',
  description: '',
  unit_uuid: null,
  price: 0,
  cost: 0,
  min_price: 0,
  promotional_price: 0,
  product_no_tax: 0,
  benefits: 0,
  benefits_rate: 0,
  is_service: false,
  category_uuid: '',
  supplier_uuid: '',
  warehouse_uuid: '',
  search: '',
  tax_uuid: '',
  weight: 0,
  bar_code: '',
  sku: '',
  brand_uuid: null,
  dimensions: '',
  inventoried: true,
  has_fraction: true,
  status: true,
  has_tax: true,
  has_special: false,
  has_promotion: false,
  update: false,
  warehouse_product: [],
  price_list_uuid: '',
  handle_warehouse: true,
});

provide(formProductKey, form);

onMounted(() => {
  if (selectedProduct.value) {
    form.uuid = selectedProduct.value.uuid;
    form.name = selectedProduct.value.name;
    form.is_service = selectedProduct.value.is_service;
    form.description = selectedProduct.value.description || '';
    form.bar_code = selectedProduct.value.bar_code || '';
    form.category_uuid = selectedProduct.value.category_uuid!!;
    form.supplier_uuid = selectedProduct.value.supplier_uuid;
    form.tax_uuid = selectedProduct.value.tax.uuid;
    form.sku = selectedProduct.value.sku || '';
    form.unit_uuid = selectedProduct.value.unit_uuid;
    form.brand_uuid = selectedProduct.value?.brand?.uuid ?? null;
    form.cost = Number(selectedProduct.value.cost);
    form.price_list_uuid = selectedProduct.value.default_price_list;
    form.handle_warehouse = selectedProduct.value.handle_warehouse;
    form.warehouse_uuid = selectedProduct.value.default_warehouse;
    // form.warehouse_product = selectedProduct.value.warehouses;
    form.default_price_list = selectedProduct.value.default_price_list;
    form.default_warehouse = selectedProduct.value.default_warehouse;
  }
});

watch(
  () => selectedProduct.value,
  async (product) => {
    if (!product) {
      form.reset();
      return;
    }

    // Rellenar campos básicos
    form.uuid = product.uuid;
    form.name = product.name;

    const warehouses = await getWarehouses(form.uuid);
    // ...

    // ✅ Fusionar almacenes
    form.warehouse_product = mergeProductWarehouses(warehouses ?? [], propsW.warehouses);
  },
  { immediate: true }
);

const submit = () => {
  if (createProduct.value || form.update) {
    form.patch(route('product.update', form.uuid), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Registro Actualizado',
          detail: 'Producto actualizado correctamente.',
          life: 3000,
        });
        emit('close');
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error en la petición: ${Object.values(err)[0]}`,
          life: 3000,
        });
      },
    });
  } else {
    form.post(route('product.store'), {
      onSuccess: () => {
        form.reset();
        toast.add({
          severity: 'success',
          summary: 'Registro Creado',
          detail: 'Producto creado correctamente.',
          life: 3000,
        });
        emit('close');
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error en la petición: ${Object.values(err)[0]}`,
          life: 3000,
        });
      },
    });
  }
};

function setCalculateData(productNoTax: string, benefits: string, benefitsMargin: string) {
  form.product_no_tax = Number(productNoTax);
  form.benefits = Number(benefits);
  form.benefits_rate = Number(benefitsMargin);
}
</script>

<template>
  <Card class="w-full border-none shadow-none p-0">
    <template #content>
      <form @submit.prevent="submit" class="space-y-4">
        <!-- Información Principal -->
        <ProductInformation
          :update="isUpdate"
          :code="selectedProduct?.code"
          :paymentTypes="propsW.paymentTypes"
          :categories="propsW.categories"
          :suppliers="propsW.suppliers"
        />

        <Divider class="my-3" />

        <!-- Tabs Adaptativos -->
        <Tabs value="0" class="w-full">
          <div class="overflow-x-auto">
            <TabList class="flex whitespace-nowrap min-w-full">
              <Tab value="0">General</Tab>
              <Tab v-if="form.name && form.name.length > 3" value="1">Ventas</Tab>
              <Tab v-if="form.name && form.name.length > 3" value="2">Inventario</Tab>
            </TabList>
          </div>

          <TabPanels class="pt-4 px-0">
            <TabPanel value="0">
              <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <ProductExtra :productType="propsW.productType" />
                  <ProductGeneral />
                </div>

                <ProductDetail
                  :priceLists="propsW.priceLists"
                  :warehouses="propsW.warehouses"
                  :units="propsW.units"
                  :branches="propsW.branches"
                />
              </div>
            </TabPanel>

            <TabPanel value="1">
              <ProductSaleValue
                :isUpdate="isUpdate"
                :units="propsW.units"
                :priceLists="propsW.priceLists"
                :warehouses="propsW.warehouses"
                @calculate="setCalculateData"
              />
            </TabPanel>

            <TabPanel value="2">
              <InventoryDetail :warehouses="propsW.warehouses" />
            </TabPanel>
          </TabPanels>
        </Tabs>

        <!-- Botones de Acción Adaptativos -->
        <div class="pt-4 flex flex-col-reverse sm:flex-row justify-end gap-3">
          <Button
            v-if="!isUpdate"
            label="Limpiar"
            severity="warn"
            type="button"
            @click="form.reset()"
            class="w-full sm:w-auto h-10"
            outlined
          >
            <template #icon>
              <Eraser class="w-4 h-4 mr-1" />
            </template>
          </Button>

          <Button
            :disabled="form.processing"
            type="submit"
            :label="isUpdate ? 'Actualizar' : 'Registrar'"
            class="w-full sm:w-auto h-10 bg-emerald-600 hover:bg-emerald-700 border-none"
          >
            <template #icon>
              <Send class="w-4 h-4 mr-1" />
            </template>
          </Button>
        </div>
      </form>
    </template>
  </Card>
</template>
