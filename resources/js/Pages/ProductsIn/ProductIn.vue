<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import { productI, ProductTableI } from '@/Interfaces/ProductInterface';
import { useRoute } from 'ziggy-js';
import { productBreadCrumb } from '@/Helpers/ProductHelper';
import {
  AutoComplete,
  Breadcrumb,
  Button,
  Card,
  FloatLabel,
  InputText,
  InputNumber,
  Select,
  Tag,
  useToast,
  AutoCompleteOptionSelectEvent,
} from 'primevue';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { debounce } from 'lodash-es';
import axios from 'axios';
import { ref } from 'vue';

const toast = useToast();
const route = useRoute();
/**
 * Datos de la pagina
 */

/**
 * Propiedades de la ventana
 */
// propsW de la ventana
const propsW = defineProps<{
  products: productI;
  warehouses: Array<WarehouseBaseI>;
}>();

const products = ref<Array<ProductTableI>>([]);
/**
 * Formulario para enviar los daots
 */
// Datos del formulario
const form = useForm({
  product_uuid: '',
  warehouse_uuid: '',
  quantity: 0,
  cost: 0,
  reference: '',
});

// Enviar formulario
const submit = () => {
  form.post(route('entry.store'), {
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'Exito',
        detail: 'Registro Creado Correctamente',
        life: 3000,
      });
      form.reset();
    },
    onError: () => {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Error al crear lo registro',
        life: 3000,
      });
    },
  });
};

const getProductJson = debounce(async (event: { query: string }) => {
  try {
    const res = await axios.get(route('product.get.json'), {
      params: {
        search: event.query,
      },
    });

    products.value = res.data;
  } catch (e) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Error al crear lo registro',
      life: 3000,
    });
  }
});

const getProductUuid = (event: AutoCompleteOptionSelectEvent) => {
  const data = event.value as ProductTableI;
  form.product_uuid = data.uuid;
};
</script>

<template>
  <Head title="Entrada" />
  <AppLayout>
    <Card>
      <template #header>
        <div>
          <Breadcrumb :model="productBreadCrumb" />
        </div>
      </template>
      <template #content>
        <form @submit.prevent="submit()" class="grid grid-cols-2 gap-3">
          <div class="col-span-full">
            <h3 class="text-2xl text-center font-bold">Entrada de Mercancia</h3>
          </div>
          <FloatLabel variant="on">
            <AutoComplete
              :suggestions="products"
              @itemSelect="getProductUuid"
              :option-label="(data: ProductTableI) => `${data.code} | ${data.name}`"
              @valueChange="getProductJson"
              fluid
              class="w-full"
            />
            <label for="product_id">Producto</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <Select
              v-model="form.warehouse_uuid"
              option-value="uuid"
              option-label="name"
              :options="propsW.warehouses"
              fluid
            />
            <label for="product_id">Almacen</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <InputNumber v-model="form.cost" fluid />
            <label for="product_id">Costo</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <InputNumber v-model="form.quantity" fluid />
            <label for="product_id">Cantidad</label>
          </FloatLabel>

          <FloatLabel variant="on">
            <InputText v-model="form.reference" fluid />
            <label for="product_id">Referencia</label>
          </FloatLabel>
          <div class="col-span-full">
            <Tag severity="danger" :value="Object.values(form.errors)[0]" />
          </div>
          <div class="col-span-full text-right space-x-3">
            <Button severity="secondary">Limpiar</Button>
            <Button type="submit">Registrar</Button>
          </div>
        </form>
      </template>
    </Card>
  </AppLayout>
</template>
