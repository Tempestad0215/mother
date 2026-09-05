<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import { SupplierI } from '@/Interfaces/SupplierInterface';
import { useRoute } from 'ziggy-js';
import {
  DataTable,
  Column,
  InputText,
  Button,
  InputGroupAddon,
  InputGroup,
  Dialog,
  useConfirm,
  useToast,
} from 'primevue';
import { ref } from 'vue';
import FRegister from '@/Pages/Suppliers/FRegister.vue';
import { PaginationI, PaymentTypeEnumI } from '@/Interfaces/GlobalInterface';
import Pagination from '@components/Pagination.vue';
import { getSearchTable } from '@/Global/SearchTable';
import { router } from '@inertiajs/vue3';
import { PackagePlus, FilePenLine, Shredder } from '@lucide/vue';
import { onPageChange, paginationOptions } from '@/Global/Helpers';

const route = useRoute();
const confirm = useConfirm();
const toast = useToast();

// Propiedades
const propsW = defineProps<{
  suppliers: PaginationI<SupplierI>;
  update?: boolean;
  paymentTypes: PaymentTypeEnumI;
}>();

const searchValue = ref('');
const createSupplier = ref(false);
const selectedSupplier = ref<SupplierI | null>(null);
const isUpdate = ref(false);

const searchData = () => {
  getSearchTable(
    route('supplier.create', {
      search: searchValue.value,
      per_page: propsW.suppliers.meta.per_page,
    })
  );
};

const editData = (data: SupplierI) => {
  selectedSupplier.value = data;
  createSupplier.value = true;
  isUpdate.value = true;
};

// Eliminar el registro seleccionado
const deleteData = (data: SupplierI, event: Event) => {
  confirm.require({
    target: event.currentTarget as HTMLElement,
    message: '¿Desea eliminar este registro? Los cambios son irreversibles.',
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
      router.delete(route('supplier.destroy', { supplier: data.uuid }), {
        onSuccess: () => {
          toast.add({
            severity: 'success',
            summary: 'Eliminado',
            detail: 'El registro fue eliminado correctamente.',
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
  selectedSupplier.value = null;
  isUpdate.value = false;
};
</script>

<template>
  <AppLayout>
    <div class="w-full px-2 sm:px-4 py-4 max-w-7xl mx-auto">
      <DataTable
        @page="onPageChange($event, route('supplier.index'), ['suppliers'])"
        lazy
        paginator
        responsiveLayout="stack"
        breakpoint="768px"
        :rowsPerPageOptions="paginationOptions"
        :totalRecords="propsW.suppliers.meta.total"
        :rows="parseFloat(propsW.suppliers.meta.per_page.toString())"
        :loading="!propsW.suppliers.data"
        :value="propsW.suppliers.data"
        class="shadow-sm rounded-lg overflow-hidden border border-slate-200"
      >
        <!-- Encabezado Adaptativo -->
        <template #header>
          <div
            class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 p-1"
          >
            <!-- Buscador -->
            <form @submit.prevent="searchData" class="w-full sm:w-auto">
              <InputGroup class="w-full sm:w-72">
                <InputText
                  v-model="searchValue"
                  placeholder="Buscar proveedor..."
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

            <!-- Botón Nuevo Proveedor -->
            <Button
              class="w-full sm:w-auto justify-center h-10 px-4 bg-emerald-600 hover:bg-emerald-700 border-none"
              @click="createSupplier = true"
              label="Nuevo Proveedor"
            >
              <template #icon>
                <PackagePlus class="w-5 h-5 mr-1" />
              </template>
            </Button>
          </div>
        </template>

        <!-- Columnas de Datos -->
        <Column field="code" header="Código" class="font-semibold text-slate-700" />
        <Column field="company_name" header="Nombre Comercial" />
        <Column field="contact" header="Contacto">
          <template #body="{ data }">
            <span>{{ data.contact || 'N/A' }}</span>
          </template>
        </Column>
        <Column field="phone" header="Teléfono">
          <template #body="{ data }">
            <span>{{ data.phone || 'N/A' }}</span>
          </template>
        </Column>
        <Column field="email" header="Correo">
          <template #body="{ data }">
            <span>{{ data.email || 'N/A' }}</span>
          </template>
        </Column>

        <!-- Acciones -->
        <Column header="Acciones">
          <template #body="{ data }: { data: SupplierI }">
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
        v-model:visible="createSupplier"
        modal
        dismissableMask
        :header="isUpdate ? 'Editar Proveedor' : 'Nuevo Proveedor'"
        :breakpoints="{ '960px': '75vw', '641px': '95vw' }"
        :style="{ width: '45vw' }"
        class="p-dialog-responsive mx-2 sm:mx-0"
        @hide="resetForm"
      >
        <div class="py-2">
          <FRegister
            :paymentTypes="propsW.paymentTypes"
            :update="isUpdate"
            :supplierEdit="selectedSupplier"
            @close="createSupplier = false"
          />
        </div>
      </Dialog>
    </div>
  </AppLayout>
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
