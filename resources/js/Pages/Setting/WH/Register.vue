<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import FRegister from '@/Pages/Setting/WH/FRegister.vue';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { reactive, ref } from 'vue';
import { useRoute } from 'ziggy-js';
import {
  Button,
  Column,
  DataTable,
  Dialog,
  Breadcrumb,
  useConfirm,
  useToast,
  Card,
} from 'primevue';
import { itemsSettings } from '@/Helpers/SettingHelpers';
import { router } from '@inertiajs/vue3';
import { HousePlus, FilePenLine, Shredder } from '@lucide/vue';

const route = useRoute();
const confirm = useConfirm();
const toast = useToast();
/*
Propiedades
 */
const propsW = defineProps<{
  warehouses: WarehouseBaseI[];
}>();

const createWarehouse = ref(false);
const selectedWarehouses = ref<WarehouseBaseI | null>(null);
const isUpdate = ref(false);

const state = reactive({
  editWareHouse: null as WarehouseBaseI | null,
});

const editData = (data: WarehouseBaseI) => {
  selectedWarehouses.value = data;
  isUpdate.value = true;
  createWarehouse.value = true;
};

const deleteData = (data: WarehouseBaseI, event: Event) => {
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
      severity: 'danger',
    },
    accept: () => {
      router.delete(route('wh.destroy', { wh: data.uuid }), {
        onSuccess: () => {
          toast.add({
            severity: 'success',
            summary: 'Eliminado ',
            detail: 'El Registro Eliminado Correctamente.',
            life: 3000,
          });
        },
      });
    },
  });
};

const hideCreate = () => {
  selectedWarehouses.value = null;
  isUpdate.value = false;
};
</script>

<template>
  <AppLayout>
    <Card>
      <template #title>
        <div>
          <Breadcrumb :model="itemsSettings" />
        </div>
        <div class="flex justify-end items-center">
          <Button class="h-8" @click="createWarehouse = true">
            <HousePlus />
          </Button>
        </div>
      </template>
      <template #content>
        <DataTable :loading="!propsW.warehouses" :value="propsW.warehouses">
          <Column field="name" header="Nombre" />
          <Column field="description" header="Descripcion" />
          <Column field="location" header="Ubicacion" />
          <Column header="Act">
            <template #body="{ data }: { data: WarehouseBaseI }">
              <div class="space-x-2">
                <Button @click="editData(data)" class="pt-1 h-8" title="Editar">
                  <template #icon>
                    <FilePenLine />
                  </template>
                </Button>
                <Button
                  @click="deleteData(data, $event)"
                  class="pt-1 h-8"
                  title="Eliminar"
                  severity="danger"
                >
                  <template #icon>
                    <Shredder />
                  </template>
                </Button>
              </div>
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>

    <Dialog modal @hide="hideCreate" v-model:visible="createWarehouse" header="Registro Almacen">
      <FRegister :update="isUpdate" :editWareHouses="selectedWarehouses" />
    </Dialog>
  </AppLayout>
</template>
