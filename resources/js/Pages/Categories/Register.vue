<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { categoryBaseI } from '@/Interfaces/CategoriesInterface';
import {
  Button,
  Column,
  DataTable,
  Dialog,
  InputGroup,
  InputGroupAddon,
  InputText,
  useConfirm,
  useToast,
} from 'primevue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useRoute } from 'ziggy-js';
import FRegister from '@/Pages/Categories/FRegister.vue';
import { getSearchTable } from '@/Global/SearchTable';
import { FilePenLine, ListFilterPlus, Shredder } from '@lucide/vue';
import { onPageChange, paginationOptions } from '@/Global/Helpers';

const route = useRoute();
const confirm = useConfirm();
const toast = useToast();

const propsW = defineProps<{
  categories: PaginationI<categoryBaseI>;
  categoryEdit?: categoryBaseI;
}>();

const searchValue = ref('');
const createCategory = ref(false);
const categorySelected = ref<categoryBaseI | null>(null);
const isUpdate = ref<boolean>(false);

const searchData = () => {
  getSearchTable(
    route('category.create', {
      search: searchValue.value,
      per_page: propsW.categories.meta.per_page,
      page: propsW.categories.meta.current_page,
    })
  );
};

const editData = (data: categoryBaseI) => {
  categorySelected.value = data;
  createCategory.value = true;
  isUpdate.value = true;
};

const deleteData = (data: categoryBaseI, event: Event) => {
  confirm.require({
    target: event.currentTarget as HTMLElement,
    message: '¿Desea eliminar este registro?',
    header: 'Confirmar Eliminación',
    icon: 'pi pi-exclamation-triangle',
    rejectProps: {
      label: 'Cancelar',
      outlined: true,
    },
    acceptProps: {
      severity: 'danger',
      label: 'Eliminar',
    },
    accept: () => {
      router.delete(route('category.destroy', { category: data.uuid }), {
        onSuccess: () => {
          toast.add({
            severity: 'success',
            summary: 'Registro Eliminado',
            detail: 'La categoría fue eliminada correctamente.',
            life: 3000,
          });
        },
        onError: (err) => {
          toast.add({
            severity: 'error',
            summary: 'Error',
            detail: `Error al intentar eliminar los datos: ${Object.values(err)[0]}`,
            life: 5000,
          });
        },
      });
    },
  });
};

const resetForm = () => {
  categorySelected.value = null;
  isUpdate.value = false;
};
</script>

<template>
  <AppLayout>
    <div class="w-full px-2 sm:px-4 py-4 max-w-7xl mx-auto">
      <DataTable
        @page="onPageChange($event, route('category.index'), ['categories'])"
        lazy
        paginator
        responsiveLayout="stack"
        breakpoint="768px"
        :rowsPerPageOptions="paginationOptions"
        :totalRecords="parseInt(propsW.categories.meta.total.toString())"
        :rows="parseFloat(propsW.categories.meta.per_page.toString())"
        :loading="!propsW.categories.data"
        :value="propsW.categories.data"
        class="shadow-sm rounded-lg overflow-hidden border border-slate-200"
      >
        <!-- Encabezado de la Tabla Adaptativo -->
        <template #header>
          <div
            class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 p-1"
          >
            <form @submit.prevent="searchData" class="w-full sm:w-auto">
              <InputGroup class="w-full sm:w-72">
                <InputText
                  v-model="searchValue"
                  placeholder="Buscar categoría..."
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
              class="w-full sm:w-auto justify-center h-10 px-4"
              @click="createCategory = true"
              label="Nueva Categoría"
            >
              <template #icon>
                <ListFilterPlus class="w-5 h-5 mr-1" />
              </template>
            </Button>
          </div>
        </template>

        <!-- Columnas de Datos -->
        <Column field="code" header="Código" class="font-semibold text-slate-700" />
        <Column field="name" header="Nombre" />
        <Column field="description" header="Descripción">
          <template #body="{ data }">
            <span class="text-slate-600">
              {{ data.description || 'Sin descripción' }}
            </span>
          </template>
        </Column>

        <Column header="Acciones">
          <template #body="{ data }: { data: categoryBaseI }">
            <div class="flex items-center gap-2 pt-1 sm:pt-0">
              <Button
                @click="editData(data)"
                class="p-button-outlined p-button-sm h-9 w-9 p-0 flex items-center justify-center"
                title="Editar"
              >
                <template #icon>
                  <FilePenLine class="w-4 h-4 text-blue-600" />
                </template>
              </Button>

              <Button
                @click="deleteData(data, $event)"
                class="p-button-outlined p-button-sm h-9 w-9 p-0 flex items-center justify-center"
                title="Eliminar"
                severity="danger"
              >
                <template #icon>
                  <Shredder class="w-4 h-4 text-red-600" />
                </template>
              </Button>
            </div>
          </template>
        </Column>
      </DataTable>

      <!-- Modal de Registro / Edición -->
      <Dialog
        v-model:visible="createCategory"
        modal
        dismissableMask
        :header="isUpdate ? 'Editar Categoría' : 'Nueva Categoría'"
        :breakpoints="{ '960px': '75vw', '641px': '95vw' }"
        :style="{ width: '40vw' }"
        class="p-dialog-responsive mx-2 sm:mx-0"
        @hide="resetForm"
      >
        <div class="py-2">
          <FRegister
            :update="isUpdate"
            :categoryEdit="categorySelected"
            @close="createCategory = false"
          />
        </div>
      </Dialog>
    </div>
  </AppLayout>
</template>

<style scoped>
/* Ajustes finos para PrimeVue DataTable responsive stack */
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
