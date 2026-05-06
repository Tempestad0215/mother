<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import {
  Breadcrumb,
  Button,
  Card,
  FloatLabel,
  InputText,
  Checkbox,
  Tag,
  useToast,
  DataTable,
  Column,
  Dialog,
} from 'primevue';
import { itemsSettings } from '@/Helpers/SettingHelpers';
import { useForm } from '@inertiajs/vue3';
import { FilePenLine, Eraser } from '@lucide/vue';
import { ref } from 'vue';

const toast = useToast();

const propsW = defineProps<{
  priceLists: Array<any>;
}>();

const form = useForm({
  id: '',
  name: '',
  currency: '',
  status: true,
  update: false,
});

const showModal = ref(false);

const submit = () => {
  form.post(route('price-list.store'), {
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'Registro Exitoso',
        life: 3500,
      });
      form.reset();
      showModal.value = false;
    },
    onError: () => {
      toast.add({
        severity: 'error',
        summary: 'Ha Surgido un Error',
        life: 5000,
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
      </template>
      <template #content>
        <DataTable :value="propsW.priceLists">
          <template #header>
            <div class="text-right">
              <Button @click="showModal = !showModal" severity="secondary" label="Agregar" />
            </div>
          </template>
          <Column header="NOMBRE" field="name" />
          <Column header="MONEDA" field="currency" />
          <Column header="ACTION">
            <template #body>
              <div class="flex space-x-3">
                <FilePenLine class="text-yellow-500 cursor-pointer" />
                <Eraser class="text-red-500 cursor-pointer" />
              </div>
            </template>
          </Column>
        </DataTable>
      </template>
    </Card>
    <Dialog modal class="w-2/4" v-model:visible="showModal">
      <form @submit.prevent="submit" class="grid grid-cols-2 gap-3">
        <div class="col-span-full">
          <h3 class="text-2xl font-bold text-center">Lista de Precio</h3>
        </div>
        <div>
          <FloatLabel variant="on">
            <InputText v-model="form.name" fluid name="name" />
            <label for="name">Nombre</label>
          </FloatLabel>
          <div class="mt-2">
            <Tag v-if="form.errors.name" severity="danger" :value="form.errors.name" />
          </div>
        </div>
        <div>
          <FloatLabel variant="on">
            <InputText v-model="form.currency" fluid name="currency" />
            <label for="currency">Moneda</label>
          </FloatLabel>
          <div>
            <Tag v-if="form.errors.currency" severity="danger" :value="form.errors.currency" />
          </div>
        </div>

        <div class="flex items-center gap-2">
          <Checkbox
            :disabled="!form.update"
            v-model="form.status"
            inputId="status"
            binary
            name="size"
            value="Normal"
          />
          <label for="status">Estado</label>
        </div>
        <div class="mt-3 text-right col-span-full space-x-3">
          <Button severity="warn" label="Limpiar" />
          <Button :disabled="form.processing" type="submit" label="Registrar" />
        </div>
      </form>
    </Dialog>
  </AppLayout>
</template>

<style scoped></style>
