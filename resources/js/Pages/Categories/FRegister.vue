<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { categoryBaseI } from '@/Interfaces/CategoriesInterface';
import { onMounted } from 'vue';
import { useRoute } from 'ziggy-js';
import { Card, FloatLabel, InputText, Button, useToast, Divider } from 'primevue';
import { Eraser, Forward } from '@lucide/vue';

const route = useRoute();
const toast = useToast();

const propsW = defineProps<{
  categoryEdit: categoryBaseI | null;
  update?: boolean;
}>();

const emit = defineEmits(['close']);

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
  // si es para actualizar
  if (propsW.update) {
    form.patch(route('category.update', { category: form.uuid }), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Registro Actualizado',
          detail: 'Categoría actualizada correctamente.',
          life: 3000,
        });
        emit('close');
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error Al Actualizar',
          detail: 'Este registro no pudo ser completado. Detalle: ' + Object.values(err)[0],
          life: 5000,
        });
      },
    });
  } else {
    form.post(route('category.store'), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Registro Creado',
          detail: 'Categoría creada correctamente.',
          life: 3000,
        });
        form.reset();
        emit('close');
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error Al Crear',
          detail: 'Este registro no pudo ser completado. Detalle: ' + Object.values(err)[0],
          life: 5000,
        });
      },
    });
  }
};
</script>

<template>
  <Card class="w-full max-w-xl mx-auto border-none shadow-none sm:shadow-sm">
    <template #header>
      <h3 class="text-xl sm:text-2xl font-bold text-center text-slate-800">
        {{ propsW.update ? 'Actualizar' : 'Crear' }} Categoría
      </h3>
      <Divider class="my-3" />
    </template>

    <template #content>
      <form class="w-full space-y-5" @submit.prevent="submit">
        <!-- Campo Nombre -->
        <FloatLabel variant="on" class="w-full">
          <InputText id="name" class="w-full" name="name" v-model="form.name" required />
          <label for="name">Nombre <span class="text-red-500">*</span></label>
        </FloatLabel>

        <!-- Campo Descripción -->
        <FloatLabel variant="on" class="w-full">
          <InputText
            id="description"
            class="w-full"
            name="description"
            v-model="form.description"
          />
          <label for="description">Descripción</label>
        </FloatLabel>

        <!-- Botones de Acción Adaptativos -->
        <div class="pt-3 flex flex-col-reverse sm:flex-row justify-end gap-3">
          <Button
            @click="form.reset()"
            type="reset"
            severity="warn"
            label="Limpiar"
            class="w-full sm:w-auto h-10"
            outlined
          >
            <template #icon>
              <Eraser class="w-4 h-4 mr-1" />
            </template>
          </Button>

          <Button
            type="submit"
            :label="propsW.update ? 'Actualizar' : 'Registrar'"
            :loading="form.processing"
            class="w-full sm:w-auto h-10"
          >
            <template #icon>
              <Forward class="w-4 h-4 mr-1" />
            </template>
          </Button>
        </div>
      </form>
    </template>
  </Card>
</template>
