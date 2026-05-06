<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { categoryBaseI } from '@/Interfaces/CategoriesInterface';
import { onMounted } from 'vue';
import { useRoute } from 'ziggy-js';
import { Card, FloatLabel, InputText, Button, useToast } from 'primevue';
import { Eraser, Forward } from '@lucide/vue';

const route = useRoute();
const toast = useToast();

const propsW = defineProps<{
  categoryEdit: categoryBaseI | null;
  update?: boolean;
}>();

/*
Formularios
 */
const form = useForm({
  uuid: '',
  name: '',
  description: '',
});

// Al momento de cargar
onMounted(() => {
  if (propsW.categoryEdit) {
    form.uuid = propsW.categoryEdit.uuid;
    form.name = propsW.categoryEdit.name;
    form.description = propsW.categoryEdit.description || '';
  }
});

// Funciones
const submit = () => {
  //si es para actualizar
  if (propsW.update) {
    form.patch(route('category.update', { category: form.uuid }), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Registro Actualizado',
          life: 3000,
        });
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error Al Actualizar',
          detail: 'Este Registro No Pudo Ser Completado, Detalle :' + Object.values(err)[0],
          life: 5000,
        });
      },
    });
  } else {
    form.post(route('category.store'), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Registro Creado Correctamente',
          life: 3000,
        });
        form.reset();
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error Al Actualizar',
          detail: 'Este Registro No Pudo Ser Completado, Detalle :' + Object.values(err)[0],
          life: 5000,
        });
      },
    });
  }
};
</script>

<template>
  <Card>
    <template #header>
      <h3 class="text-2xl font-bold text-center">
        {{ propsW.update ? 'Actualizar' : 'Crear' }} Categoria
      </h3>
    </template>
    <template #content>
      <form class="w-100" @submit.prevent="submit">
        <FloatLabel variant="on">
          <InputText class="w-full" name="name" v-model="form.name" />
          <label for="name">Nombre <span class="text-red-500">*</span> </label>
        </FloatLabel>
        <FloatLabel class="mt-5" variant="on">
          <InputText class="w-full" name="description" v-model="form.description" />
          <label for="description">Descripción</label>
        </FloatLabel>
        <div class="mt-5 text-right space-x-3">
          <Button @click="form.reset()" type="reset" class="h-8" severity="warn" label="Limpiar">
            <template #icon>
              <Eraser />
            </template>
          </Button>
          <Button
            type="submit"
            class="h-8"
            icon="pi pi-send"
            :label="propsW.update ? 'Actualizar' : 'Registrar'"
          >
            <template #icon>
              <Forward />
            </template>
          </Button>
        </div>
      </form>
    </template>
  </Card>
</template>
