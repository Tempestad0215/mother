<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import {
  DataTable,
  Column,
  Button,
  Dialog,
  Card,
  useToast,
  useConfirm,
  Breadcrumb,
} from 'primevue';
import type { UnitInterfaceI } from '@/Interfaces/UnitInterface';
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import { itemsSettings } from '@/Helpers/SettingHelpers';
import FRegisterUnit from '@/Pages/Setting/Unit/FRegisterUnit.vue';

const confirm = useConfirm();
const toast = useToast();

const propsW = defineProps<{
  units: UnitInterfaceI[];
}>();

const createUnit = ref(false);
const selectedUnit = ref<UnitInterfaceI | null>(null);
const isUpdate = ref(false);

const editData = (data: UnitInterfaceI) => {
  selectedUnit.value = data;
  createUnit.value = true;
  isUpdate.value = true;
};

const deleteData = (data: UnitInterfaceI, event: Event) => {
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
      router.delete(route('unit.destroy', { unit: data.id }), {
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
</script>

<template>
  <AppLayout>
    <Card>
      <template #title>
        <div>
          <Breadcrumb :model="itemsSettings" />
        </div>
        <div class="text-right">
          <Button @click="createUnit = true" icon="pi pi-plus" label="Crear Unidad" />
        </div>
      </template>
      <template #content>
        <DataTable :value="units">
          <Column field="name" header="Nombre" />
          <Column field="description" header="Descripcion" />
          <Column class="w-40" header="Act">
            <template #body="{ data }: { data: UnitInterfaceI }">
              <div class="space-x-3">
                <Button
                  @click="editData(data)"
                  class="pt-1 h-8"
                  title="Editar"
                  icon="pi pi-file-edit"
                />
                <Button
                  @click="deleteData(data, $event)"
                  class="pt-1 h-8"
                  title="Elimianr"
                  severity="danger"
                  icon="pi pi-trash"
                />
              </div>
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>

    <Dialog
      modal
      v-model:visible="createUnit"
      @hide="selectedUnit = null"
      header="Registro de Unidad"
    >
      <FRegisterUnit :isUpdate="isUpdate" :unitEdit="selectedUnit" />
    </Dialog>
  </AppLayout>
</template>
