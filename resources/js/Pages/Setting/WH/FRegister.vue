<script setup lang="ts">
import { onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useRoute } from 'ziggy-js';
import { Forward, Loader2 } from 'lucide-vue-next';

import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';

import type { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';

const route = useRoute();

const propsW = defineProps<{
  editWareHouses: WarehouseBaseI | null;
  update: boolean;
}>();

const emit = defineEmits<{
  (e: 'saved'): void;
}>();

const form = useForm({
  uuid: '',
  prefix: '',
  name: '',
  description: '',
  location: '',
});

onMounted(() => {
  if (propsW.editWareHouses) {
    form.uuid = propsW.editWareHouses.uuid;
    form.prefix = propsW.editWareHouses.prefix;
    form.name = propsW.editWareHouses.name;
    form.description = propsW.editWareHouses.description;
    form.location = propsW.editWareHouses.location;
  }
});

const submit = (): void => {
  if (propsW.update) {
    form.put(route('warehouse.update', { wh: form.uuid }), {
      onSuccess: () => {
        form.reset();
        emit('saved');
      },
    });
  } else {
    form.post(route('warehouse.store'), {
      onSuccess: () => {
        form.reset();
        emit('saved');
      },
    });
  }
};
</script>

<template>
  <Card>
    <CardContent class="pt-6">
      <form class="w-80 space-y-4" @submit.prevent="submit">
        <div class="space-y-1.5">
          <Label for="prefix">Prefix</Label>
          <Input id="prefix" v-model="form.prefix" placeholder="Ej. ALM-01" />
        </div>

        <div class="space-y-1.5">
          <Label for="name">Nombre</Label>
          <Input id="name" v-model="form.name" placeholder="Nombre del almacén" />
        </div>

        <div class="space-y-1.5">
          <Label for="description">Descripción</Label>
          <Input id="description" v-model="form.description" placeholder="Descripción" />
        </div>

        <div class="space-y-1.5">
          <Label for="location">Ubicación</Label>
          <Input id="location" v-model="form.location" placeholder="Ubicación" />
        </div>

        <div class="pt-2 flex justify-end">
          <Button type="submit" :disabled="form.processing">
            <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
            <Forward v-else class="w-4 h-4 mr-2" />
            {{ propsW.update ? 'Actualizar' : 'Registrar' }}
          </Button>
        </div>
      </form>
    </CardContent>
  </Card>
</template>
