<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Eraser, Forward } from 'lucide-vue-next';
import { computed } from 'vue';
import { toast } from 'vue-sonner';
import { useRoute } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import type { categoryBaseI } from '@/Interfaces/CategoriesInterface';

const route = useRoute();

const propsW = defineProps<{
  categoryEdit: categoryBaseI | null;
  update?: boolean;
}>();

const emit = defineEmits(['close']);

const formAction = computed(() =>
  propsW.update && propsW.categoryEdit?.uuid
    ? route('category.update', { category: propsW.categoryEdit.uuid })
    : route('category.store'),
);

const handleSuccess = (reset: () => void) => {
  if (propsW.update) {
    toast.success('Categoría actualizada correctamente.');
  } else {
    toast.success('Categoría creada correctamente.');
    reset();
  }
  emit('close');
};

const handleError = (err: Record<string, string>) => {
  const detail = Object.values(err)[0] ?? 'Error de validación';
  toast.error(
    propsW.update
      ? `Error al actualizar. Detalle: ${detail}`
      : `Error al crear. Detalle: ${detail}`,
  );
};
</script>

<template>
  <Card class="mx-auto w-full max-w-xl border-none shadow-none sm:shadow-sm">
    <CardHeader>
      <CardTitle class="text-center text-xl font-bold text-slate-800 dark:text-slate-100 sm:text-2xl">
        {{ propsW.update ? 'Actualizar' : 'Crear' }} Categoría
      </CardTitle>
      <Separator class="my-3" />
    </CardHeader>

    <CardContent>
      <Form
        :key="propsW.categoryEdit?.uuid ?? (propsW.update ? 'edit' : 'create')"
        :action="formAction"
        :method="propsW.update ? 'patch' : 'post'"
        class="w-full space-y-5"
        #default="{ processing, errors, reset }"
        @success="handleSuccess(reset)"
        @error="handleError"
      >
        <input
          v-if="propsW.categoryEdit?.uuid"
          type="hidden"
          name="uuid"
          :value="propsW.categoryEdit.uuid"
        />

        <!-- Campo Nombre -->
        <div class="space-y-2">
          <Label for="name">
            Nombre <span class="text-destructive">*</span>
          </Label>
          <Input
            id="name"
            name="name"
            type="text"
            class="w-full"
            :default-value="propsW.categoryEdit?.name ?? ''"
            required
          />
          <p v-if="errors.name" class="text-destructive text-sm">
            {{ errors.name }}
          </p>
        </div>

        <!-- Campo Descripción -->
        <div class="space-y-2">
          <Label for="description">Descripción</Label>
          <Input
            id="description"
            name="description"
            type="text"
            class="w-full"
            :default-value="propsW.categoryEdit?.description ?? ''"
          />
          <p v-if="errors.description" class="text-destructive text-sm">
            {{ errors.description }}
          </p>
        </div>

        <!-- Botones de Acción Adaptativos -->
        <div class="flex flex-col-reverse justify-end gap-3 pt-3 sm:flex-row">
          <Button
            type="button"
            variant="outline"
            class="h-10 w-full sm:w-auto"
            @click="reset()"
          >
            <Eraser class="mr-1 h-4 w-4" />
            Limpiar
          </Button>

          <Button
            type="submit"
            :disabled="processing"
            class="h-10 w-full sm:w-auto"
          >
            <Forward class="mr-1 h-4 w-4" />
            {{ propsW.update ? 'Actualizar' : 'Registrar' }}
          </Button>
        </div>
      </Form>
    </CardContent>
  </Card>
</template>
