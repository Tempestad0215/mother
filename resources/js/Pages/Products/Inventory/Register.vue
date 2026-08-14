<script lang="ts" setup>
import { ProductBaseI, productI } from '@/Interfaces/ProductInterface';
import AppLayout from '@layout/AppLayout.vue';
import { ref, watch } from 'vue';
import { entryBaseI, entryProductI } from '@/Interfaces/EntryTransInterface';
import axios from 'axios';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { useRoute } from 'ziggy-js';
import { Button, Column, DataTable, InputGroup, InputGroupAddon, InputText } from 'primevue';
import { clientBaseI } from '@/Interfaces/ClientInterface';

const route = useRoute();
// Propiedades
const propsW = defineProps<{
  products: ProductBaseI[];
  productTable: productI;
  entry_edit?: entryBaseI;
  entries: PaginationI<entryProductI>;
  clientData: PaginationI<clientBaseI>;
}>();
//datos de la ventana
const productName = ref<string>();
const products = ref<ProductBaseI[] | null>(null);
const editData = ref<entryProductI | undefined>(undefined);

/**
 * Evento watch
 */

/**
 * Pra buscar los datos por cada cambio
 */
watch(productName, (newValue) => {
  if (newValue && newValue?.length > 3) {
    axios
      .get(route('product.get.json', { search: productName.value }))
      .then((res) => {
        products.value = res.data;
      })
      .catch(() => {});
  }
});

// Editar los datos
const edit = (item: entryProductI) => {
  editData.value = { ...item };
};
</script>

<template>
  <AppLayout>
    <DataTable
      paginator
      :rows="Number(propsW.clientData.meta.per_page) ?? 0"
      :loading="!propsW.clientData.data"
      :value="propsW.clientData.data"
    >
      <template #header>
        <div class="flex justify-between items-center">
          <form>
            <InputGroup class="max-w-60">
              <InputText placeholder="Buscar" type="search" />
              <InputGroupAddon>
                <i class="pi pi-search"></i>
              </InputGroupAddon>
            </InputGroup>
          </form>
          <Button class="h-8"> Crear Cliente </Button>
        </div>
      </template>
      <Column field="code" header="Codigo" />
      <Column field="name" header="Nombre" />
      <Column field="rnc" header="RNC" />
      <Column field="phone" header="Telefono" />
      <Column field="email" header="Correo" />
      <Column header="Act">
        <template #body="{ data }: { data: clientBaseI }">
          <div class="space-x-2">
            <Button class="pt-1 h-8" title="Editar" icon="pi pi-file-edit" />
            <Button class="pt-1 h-8" title="Eliminar" severity="danger" icon="pi pi-trash" />
          </div>
        </template>
      </Column>
      <template #paginatorcontainer>
        <!--        <Pagination :pag="propsW.clientData" />-->
      </template>
    </DataTable>
    <!--    <Dialog modal @hide="selectedClient = null" v-model:visible="createClient">-->
    <!--      <FRegister-->
    <!--        :clientDocument="clientDocument"-->
    <!--        :clientPrice="clientPrice"-->
    <!--        :clientType="clientType"-->
    <!--        :typeRNC="typeRNC"-->
    <!--        :update="isUpdate"-->
    <!--        :client-edit="selectedClient"-->
    <!--      />-->
    <!--    </Dialog>-->
  </AppLayout>
</template>
