<script setup lang="ts">
import Pagination from '@components/Pagination.vue';
import { ProductBaseI, ProductTableI } from '@/Interfaces/ProductInterface';
import { router, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useRoute } from 'ziggy-js';
import {
  Breadcrumb,
  Button,
  Card,
  Column,
  DataTable,
  InputGroup,
  InputGroupAddon,
  InputText,
  useConfirm,
  useToast,
} from 'primevue';
import { productBreadCrumb } from '@/Helpers/ProductHelper';
import { PreciseCalculator } from '@/utils/Decimal';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { getMoney } from '@/Global/Helpers';
import { SquarePlus } from '@lucide/vue';

const toast = useToast();
const confirm = useConfirm();
const route = useRoute();
/**
 * Informacion de la ventana
 */
const { component } = usePage();

interface PropsI {
  products: PaginationI<ProductTableI>;
  stock?: boolean;
  isProduct?: boolean;
}
/**
 * Propiedades de la ventana
 */
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

const searchData = () => {};

const editData = (data: ProductTableI) => {
  selectedProduct.value = data;
  isUpdate.value = true;
  createProduct.value = true;
};

const deleteData = (data: ProductTableI, event: Event) => {
  confirm.require({
    target: event.currentTarget as HTMLElement,
    message: 'Desea eliminar este registro, los cambios son irreversible',
    rejectProps: {
      label: 'Cancelar',
      severity: 'secondary',
      outlined: true,
    },
    acceptProps: {
      label: 'Eliminar',
    },
    accept: () => {
      router.delete(route('product.destroy', { product: data.uuid }), {
        onSuccess: () => {
          toast.add({
            severity: 'success',
            summary: 'Eliminado ',
            detail: 'El Registro Fue Eliminado Correctamente.',
            life: 3000,
          });
        },
      });
    },
  });
};

const getPriceFromList = (product: ProductTableI): number => {
  const item = product.price_lists.find((el) => el.uuid == product.default_price_list);
  if (item) {
    return item.price;
  } else {
    return 0;
  }
};
</script>

<template>
  <Card>
    <template #title>
      <div v-if="propsW.isProduct">
        <Breadcrumb :model="productBreadCrumb" />
      </div>
      <div class="flex justify-between items-center">
        <form @submit.prevent="searchData">
          <InputGroup class="max-w-60">
            <InputText v-model="searchValue" placeholder="Buscar" type="search" />
            <InputGroupAddon @click="searchData">
              <i class="pi pi-search"></i>
            </InputGroupAddon>
          </InputGroup>
        </form>
        <Button
          v-if="component != 'Sale/SaleCreate'"
          title="Nuevo"
          class="h-8"
          label="Producto"
          @click="createProduct = true"
        >
          <template #icon>
            <SquarePlus />
          </template>
        </Button>
      </div>
    </template>
    <template #content>
      <DataTable
        paginator
        :rows="propsW.products.meta.per_page ?? 0"
        :loading="!propsW.products.data"
        :value="propsW.products.data"
      >
        <Column field="code" header="Codigo" />
        <Column field="name" header="Nombre" />
        <Column
          v-if="component === 'Products/Register'"
          :field="(data: ProductBaseI) => `${PreciseCalculator.formatCurrency(data.cost)}`"
          header="Costo"
        />
        <Column header="Precio">
          <template #body="{ data }: { data: ProductTableI }">
            <span :class="{ 'text-red-500': getPriceFromList(data) <= 0 }">{{
              getMoney(getPriceFromList(data))
            }}</span>
          </template>
        </Column>
        <Column
          :field="(data: ProductBaseI) => `${data.is_service ? 'Servicio' : 'Producto'}`"
          header="Tipo"
        />
        <Column
          :field="(data: ProductBaseI) => `${getMoney(data.stock)}`"
          header="Stock"
          v-if="propsW.stock"
        />
        <Column header="Act">
          <template #body="{ data }: { data: ProductTableI }">
            <div class="space-x-2">
              <Button
                v-if="component != 'Sale/SaleCreate'"
                @click="editData(data)"
                class="pt-1 h-8"
                title="Editar"
                icon="pi pi-file-edit"
              />
              <Button
                v-if="component != 'Sale/SaleCreate'"
                @click="deleteData(data, $event)"
                class="pt-1 h-8"
                title="Eliminar"
                severity="danger"
                icon="pi pi-trash"
              />
              <Button
                v-if="component == 'Sale/SaleCreate'"
                @click="$emit('selectData', data)"
                class="pt-1 h-8"
                title="Seleccionar"
                outlined
                severity="secondary"
                icon="pi pi-check-circle"
              />
            </div>
          </template>
        </Column>
        <template #paginatorcontainer>
          <Pagination :search="searchValue" :pag="propsW.products" />
        </template>
      </DataTable>
    </template>
  </Card>
</template>
