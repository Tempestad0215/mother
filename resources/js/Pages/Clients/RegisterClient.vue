<script setup lang="ts">
import { ref } from 'vue';
import {
  Button,
  Column,
  DataTable,
  InputGroup,
  InputGroupAddon,
  InputText,
  useConfirm,
  useToast,
} from 'primevue';
import { router } from '@inertiajs/vue3';
import { useRoute } from 'ziggy-js';
import Pagination from '@components/Pagination.vue';
import { getSearchTable } from '@/Global/SearchTable';
import { clientBaseI } from '@/Interfaces/ClientInterface';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { FilePenLine, Shredder, UserPlus } from '@lucide/vue';
import AppLayout from '@layout/AppLayout.vue';

const route = useRoute();
const confirm = useConfirm();
const toast = useToast();

const props = defineProps<{
  clientData: PaginationI<clientBaseI>;
}>();

// v-models para controlar modal y edición desde el padre
const showClient = defineModel<boolean>('showClient');
const clientSelected = defineModel<clientBaseI | null>('clientSelected');
const updateClient = defineModel<boolean>('updateClient');

const searchValue = ref('');

const searchData = () => {
  getSearchTable(
    route('client.index', {
      search: searchValue.value,
      per_page: props.clientData.meta.per_page,
      page: props.clientData.meta.current_page,
    })
  );
};

const editData = (data: clientBaseI) => {
  clientSelected.value = data;
  updateClient.value = true;
  showClient.value = true;
};

const deleteData = (data: clientBaseI, event: Event) => {
  confirm.require({
    target: event.currentTarget as HTMLElement,
    message: '¿Desea eliminar este cliente?',
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
      router.delete(route('client.destroy', { client: data.uuid }), {
        onSuccess: () => {
          toast.add({
            severity: 'success',
            summary: 'Cliente Eliminado',
            detail: 'El cliente fue eliminado correctamente.',
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
</script>

<template>
  <AppLayout>
    <DataTable
      paginator
      responsiveLayout="stack"
      breakpoint="768px"
      :rows="parseFloat(props.clientData.meta.per_page.toString())"
      :loading="!props.clientData.data"
      :value="props.clientData.data"
      class="shadow-sm rounded-lg overflow-hidden border border-slate-200"
    >
      <!-- Encabezado Adaptativo (Igual a Categorías) -->
      <template #header>
        <div
          class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 p-1"
        >
          <!-- Buscador -->
          <form @submit.prevent="searchData" class="w-full sm:w-auto">
            <InputGroup class="w-full sm:w-72">
              <InputText
                v-model="searchValue"
                placeholder="Buscar cliente..."
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

          <!-- Botón Nuevo Cliente (En celular ocupa el 100% como Categorías) -->
          <Button
            class="w-full sm:w-auto justify-center h-10 px-4 bg-emerald-600 hover:bg-emerald-700 border-none"
            @click="showClient = true"
            label="Nuevo Cliente"
          >
            <template #icon>
              <UserPlus class="w-5 h-5 mr-1" />
            </template>
          </Button>
        </div>
      </template>

      <!-- Columnas -->
      <Column field="code" header="Código" class="font-semibold text-slate-700" />
      <Column field="name" header="Nombre" />
      <Column field="personal_id" header="RNC / Cedula">
        <template #body="{ data }">
          <span>{{ data.personal_id || 'N/A' }}</span>
        </template>
      </Column>
      <Column field="phone" header="Teléfono">
        <template #body="{ data }">
          <span>{{ data.phone || 'N/A' }}</span>
        </template>
      </Column>

      <!-- Acciones -->
      <Column header="Acciones">
        <template #body="{ data }: { data: clientBaseI }">
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

      <!-- Paginador -->
      <template #paginatorcontainer>
        <div class="p-2 border-t border-slate-200">
          <Pagination :search="searchValue" :pag="props.clientData" />
        </div>
      </template>
    </DataTable>
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
</style>
