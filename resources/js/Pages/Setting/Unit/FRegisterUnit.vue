<script setup lang="ts">
import { Button, FloatLabel, InputText, useToast } from 'primevue';
import { UnitInterfaceI } from '@/Interfaces/UnitInterface';
import { useForm } from '@inertiajs/vue3';

const toast = useToast();

const propsW = defineProps<{
  isUpdate: boolean;
  unitEdit: UnitInterfaceI | null;
}>();

const form = useForm({
  id: 0,
  name: '',
  description: '',
});

const formReset = () => {
  form.reset('name', 'description', 'id');
};

const submit = () => {
  if (propsW.isUpdate) {
    form.put(route('unit.update', { id: form.id }), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Actualizado',
          life: 3000,
        });
        formReset();
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error en esta peticion. Detalle : ${Object.values(err)[0]}`,
          life: 5000,
        });
      },
    });
  } else {
    form.post(route('unit.store'), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Registrado',
          life: 3000,
        });
        formReset();
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error en esta peticion. Detalle : ${Object.values(err)[0]}`,
          life: 5000,
        });
      },
    });
  }
};
</script>

<template>
  <form class="w-100" @submit.prevent="submit">
    <FloatLabel class="mt-5" variant="on">
      <InputText v-model="form.name" class="w-full" id="name" />
      <label for="name">Nombre</label>
    </FloatLabel>
    <FloatLabel variant="on" class="mt-5">
      <InputText v-model="form.description" class="w-full" id="name" />
      <label for="name">Descripcion</label>
    </FloatLabel>
    <div class="mt-5 text-right">
      <Button type="submit" icon="pi pi-send" label="Registrar" />
    </div>
  </form>
</template>
