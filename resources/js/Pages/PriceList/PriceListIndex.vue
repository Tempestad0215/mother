<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { Eraser, FilePenLine, Plus, RotateCcw } from 'lucide-vue-next';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';
import { itemsSettings } from '@/helpers/SettingHelpers';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';

export interface PriceList {
  id: number | string;
  name: string;
  currency: string;
  status?: boolean | number;
}

const props = defineProps<{
  priceLists: PriceList[];
}>();

const showModal = ref(false);
const editingPriceList = ref<PriceList | null>(null);

const openCreateModal = () => {
  editingPriceList.value = null;
  showModal.value = true;
};

const openEditModal = (item: PriceList) => {
  editingPriceList.value = item;
  showModal.value = true;
};

const handleDelete = (item: PriceList) => {
  // Conecta aquí tu router.delete(...) o helper de ruta de eliminación
  console.log('Eliminar lista de precio:', item.id);
};
</script>

<template>
  <AppLayout>
    <Card>
      <CardHeader>
        <BreadCrumbComponent :itemOptions="itemsSettings" />
      </CardHeader>

      <CardContent>
        <div class="mb-4 flex justify-end">
          <Button type="button" variant="secondary" @click="openCreateModal">
            <Plus class="mr-2 h-4 w-4" />
            Agregar
          </Button>
        </div>

        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>NOMBRE</TableHead>
              <TableHead>MONEDA</TableHead>
              <TableHead class="text-right">ACTION</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="item in props.priceLists" :key="item.id">
              <TableCell>{{ item.name }}</TableCell>
              <TableCell>{{ item.currency }}</TableCell>
              <TableCell>
                <div class="flex justify-end space-x-3">
                  <button
                    type="button"
                    class="cursor-pointer text-yellow-500 hover:opacity-80"
                    @click="openEditModal(item)"
                  >
                    <FilePenLine class="h-4 w-4" />
                  </button>
                  <button
                    type="button"
                    class="cursor-pointer text-red-500 hover:opacity-80"
                    @click="handleDelete(item)"
                  >
                    <Eraser class="h-4 w-4" />
                  </button>
                </div>
              </TableCell>
            </TableRow>
            <TableRow v-if="props.priceLists.length === 0">
              <TableCell colspan="3" class="text-center text-muted-foreground">
                No hay listas de precio registradas.
              </TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </CardContent>
    </Card>

    <Dialog v-model:open="showModal">
      <DialogContent class="sm:max-w-2xl">
        <DialogHeader>
          <DialogTitle class="text-center text-2xl font-bold">
            {{ editingPriceList ? 'Editar Lista de Precio' : 'Lista de Precio' }}
          </DialogTitle>
        </DialogHeader>

        <Form
          :key="editingPriceList?.id ?? 'new'"
          action="/price-lists"
          method="post"
          class="grid grid-cols-1 gap-4 sm:grid-cols-2"
          #default="{ processing, errors, reset }"
          @success="showModal = false"
        >
          <input
            v-if="editingPriceList"
            type="hidden"
            name="id"
            :value="editingPriceList.id"
          />

          <div class="space-y-2">
            <Label for="name">Nombre</Label>
            <Input
              id="name"
              name="name"
              :default-value="editingPriceList?.name ?? ''"
              required
            />
            <p v-if="errors.name" class="text-sm text-destructive">
              {{ errors.name }}
            </p>
          </div>

          <div class="space-y-2">
            <Label for="currency">Moneda</Label>
            <Input
              id="currency"
              name="currency"
              :default-value="editingPriceList?.currency ?? ''"
              required
            />
            <p v-if="errors.currency" class="text-sm text-destructive">
              {{ errors.currency }}
            </p>
          </div>

          <div class="flex items-center gap-2 sm:col-span-2">
            <input
              type="hidden"
              name="status"
              :value="editingPriceList ? (editingPriceList.status ? '1' : '0') : '1'"
            />
            <Checkbox
              id="status"
              :disabled="!editingPriceList"
              :default-checked="editingPriceList ? Boolean(editingPriceList.status) : true"
            />
            <Label for="status">Estado</Label>
          </div>

          <div class="col-span-full mt-3 flex justify-end space-x-3">
            <Button
              type="button"
              variant="outline"
              @click="() => { reset(); editingPriceList = null; }"
            >
              <RotateCcw class="mr-2 h-4 w-4" />
              Limpiar
            </Button>
            <Button type="submit" :disabled="processing">
              {{ editingPriceList ? 'Actualizar' : 'Registrar' }}
            </Button>
          </div>
        </Form>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>
