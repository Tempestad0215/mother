<script setup lang="ts">
import { ProductBaseI, ProductFormI, ProductTypeEnumI } from '@/Interfaces/ProductInterface';
import { SupplierI } from '@/Interfaces/SupplierInterface';
import { useForm } from '@inertiajs/vue3';
import { onMounted, provide } from 'vue';
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
import { Button, Tab, TabList, TabPanel, TabPanels, Tabs, useToast } from 'primevue';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import ProductSaleValue from '@/Pages/Products/ProductSaleValue.vue';
import { PriceListWTI } from '@/Interfaces/PriceListInterface';
import InventoryDetail from '@/Pages/Products/Inventory/InventoryDetail.vue';

const route = useRoute();
const toast = useToast();

/**
 * Propiedades de la ventana
 */
const propsW = defineProps<{
  productEdit: ProductBaseI | null;
  update?: boolean;
  categories: categoryBaseI[];
  suppliers: SupplierI[];
  paymentTypes: PaymentTypeEnumI;
  productType: ProductTypeEnumI;
  branches: BranchInterfaceI[];
  units: UnitInterfaceI[];
  warehouses: WarehouseBaseI[];
  priceLists: Array<PriceListWTI>;
}>();

/**
 * Emitir eventos
 */
const emit = defineEmits(['showSupplier']);

/**
 * Datos del formulario
 */
const form = useForm<ProductFormI>({
  uuid: '',
  name: '',
  description: '',
  unit_uuid: null,
  price: 0,
  cost: 0,
  min_price: 0,
  special_price: 0,
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
  warehouseProduct: [],
});

provide(formProductKey, form);

/**
 * Al momento de cargar
 */
onMounted(() => {
  // Pasar los datos a editar
  if (propsW.productEdit) {
    form.uuid = propsW.productEdit.uuid;
    form.name = propsW.productEdit.name;
    form.is_service = propsW.productEdit.is_service === 1;
    form.description = propsW.productEdit.description ? propsW.productEdit.description : '';
    form.bar_code = propsW.productEdit.bar_code ? propsW.productEdit.bar_code : '';
    form.category_uuid = propsW.productEdit.category_uuid;
    form.supplier_uuid = propsW.productEdit.supplier_uuid;
    form.tax_uuid = propsW.productEdit.tax_uuid;
    form.sku = propsW.productEdit.sku || '';
    form.unit_uuid = propsW.productEdit.unit_uuid;
    form.brand_uuid = propsW.productEdit.brand_uuid || '';
    form.cost = Number(propsW.productEdit.cost);
    form.price = Number(propsW.productEdit.price);
    form.min_price = Number(propsW.productEdit.min_price) || 0;
    form.special_price = Number(propsW.productEdit.special_price) || 0;
  }
});

/**
 * Funcion para enviar los datos
 */
const submit = () => {
  if (propsW.update || form.update) {
    form.patch(route('product.update', form.uuid), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Registro Actualizado',
          life: 3000,
        });
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error en esta peticion. Detalle : ${Object.values(err)[0]}`,
          life: 3000,
        });
      },
    });
  } else {
    // Formulario para guardar los productos
    form.post(route('product.store'), {
      onSuccess: () => {
        form.reset();
        toast.add({
          severity: 'success',
          summary: 'Registro Actualizado',
          life: 3000,
        });
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error en esta peticion. Detalle : ${Object.values(err)[0]}`,
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
  <form @submit.prevent="submit">
    <ProductInformation
      :paymentTypes="propsW.paymentTypes"
      :categories="propsW.categories"
      :suppliers="propsW.suppliers"
    />
    <Tabs value="0" class="h-110">
      <TabList>
        <Tab value="0">General</Tab>
        <Tab v-if="form.name && form.name.length > 3" value="1">Ventas</Tab>
        <Tab v-if="form.name && form.name.length > 3" value="2">Inventario</Tab>
      </TabList>
      <TabPanels>
        <TabPanel value="0">
          <!--Informacion General-->
          <div class="">
            <div class="flex flex-col md:flex-row flex-wrap gap-3">
              <ProductExtra class="flex-1" :productType="propsW.productType" />

              <ProductGeneral class="" />
            </div>

            <!--Detalle del producto-->
            <ProductDetail
              :warehouses="propsW.warehouses"
              :units="propsW.units"
              :branches="propsW.branches"
            />
          </div>
        </TabPanel>
        <TabPanel value="1">
          <div class="text-center text-2xl font-bold">Detalle de Ventas</div>
          <ProductSaleValue
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
    <!-- Botones -->
    <div class="mt-4 space-x-3 text-right">
      <Button label="Limpiar" severity="warn" @click="form.reset()" />
      <Button
        :disabled="form.processing"
        type="submit"
        icon="pi pi-send"
        :label="propsW.update ? 'Actualizar' : 'Registrar'"
      />
    </div>
  </form>
</template>
