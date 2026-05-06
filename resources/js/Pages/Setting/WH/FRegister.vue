<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { onMounted } from 'vue';
import { useRoute } from 'ziggy-js';
import { FloatLabel, InputText, Button, Card, useToast } from 'primevue';
import { Forward } from '@lucide/vue';

const route = useRoute();
const toast = useToast();

const propsW = defineProps<{
  editWareHouses: WarehouseBaseI | null;
  update: boolean;
}>();

/**
 * Formularios
 */
const form = useForm({
  uuid: '',
  name: '',
  description: '',
  location: '',
});

onMounted(() => {
  if (propsW.editWareHouses) {
    form.uuid = propsW.editWareHouses.uuid;
    form.name = propsW.editWareHouses.name;
    form.description = propsW.editWareHouses.description;
    form.location = propsW.editWareHouses.location;
  }
});

/*
funciones
 */
/**
 * Enviar los datos
 */
const submit = () => {
  if (propsW.update) {
    form.put(route('wh.update', { wh: form.uuid }), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Registro Actualizado',
          life: 3000,
        });
        form.reset();
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error en esta solicitud. Detalle : ${Object.values(err)[0]}`,
          life: 3000,
        });
      },
    });
  } else {
    form.post(route('wh.store'), {
      onSuccess: () => {
        form.reset();
        toast.add({
          severity: 'success',
          summary: 'Registro Creado',
          life: 3000,
        });
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error en esta solicitud. Detalle : ${Object.values(err)[0]}`,
          life: 3000,
        });
      },
    });
  }
};
</script>

<template>
  <Card>
    <template #content>
      <form class="w-80" @submit.prevent="submit">
        <FloatLabel variant="on" class="mt-5">
          <InputText class="w-full" id="name" v-model="form.name" />
          <label for="name">Nombre</label>
        </FloatLabel>
        <FloatLabel variant="on" class="mt-5">
          <InputText class="w-full" id="description" v-model="form.description" />
          <label for="description">Descripcion</label>
        </FloatLabel>
        <FloatLabel variant="on" class="mt-5">
          <InputText class="w-full" id="location" v-model="form.location" />
          <label for="location">Ubicacion</label>
        </FloatLabel>
        <div class="mt-5 text-right">
          <Button type="submit" :label="propsW.update ? 'Actualizar' : 'Registrar'">
            <template #icon>
              <Forward />
            </template>
          </Button>
        </div>
      </form>
    </template>
  </Card>
</template>
