<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useRoute } from 'ziggy-js';
import {
  ChevronLeft,
  ChevronRight,
  ChevronsLeft,
  ChevronsRight,
  FilePenLine,
  PackagePlus,
  Search,
  Shredder,
} from 'lucide-vue-next';

import AppLayout from '@/Layouts/AppLayout.vue';
import { Button } from '@/Components/ui/button';
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from '@/Components/ui/dialog';
import { Input } from '@/Components/ui/input';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table';
import FRegister from '@/Pages/Suppliers/FRegister.vue';

import type { SupplierI } from '@/Interfaces/SupplierInterface';
import type { PaginationI, PaymentTypeEnumI } from '@/Interfaces/GlobalInterface';
import { getSearchTable } from '@/Global/SearchTable';
import { paginationOptions } from '@/Global/Helpers';

const route = useRoute();

const propsW = defineProps<{
  suppliers: PaginationI<SupplierI>;
  update?: boolean;
  paymentTypes: PaymentTypeEnumI;
}>();

const searchValue = ref('');
const createSupplier = ref(false);
const selectedSupplier = ref<SupplierI | null>(null);
const isUpdate = ref(false);

const navigateWithParams = (page: number, perPage: number) => {
  getSearchTable(
    route('supplier.index', {
      search: searchValue.value || undefined,
      per_page: perPage,
      page,
    })
  );
};

const searchData = () => {
  navigateWithParams(1, Number(propsW.suppliers.meta.per_page) || 10);
};

const goToPage = (page: number) => {
  const lastPage = Number(propsW.suppliers.meta.last_page) || 1;
  const clampedPage = Math.max(1, Math.min(page, lastPage));
  navigateWithParams(clampedPage, Number(propsW.suppliers.meta.per_page) || 10);
};

const changePerPage = (value: string) => {
  navigateWithParams(1, Number(value));
};

const openCreate = () => {
  selectedSupplier.value = null;
  isUpdate.value = false;
  createSupplier.value = true;
};

const editData = (data: SupplierI) => {
  selectedSupplier.value = data;
  isUpdate.value = true;
  createSupplier.value = true;
};

const deleteData = (data: SupplierI) => {
  router.delete(route('supplier.destroy', { supplier: data.uuid }), {
    preserveScroll: true,
  });
};

const resetForm = () => {
  selectedSupplier.value = null;
  isUpdate.value = false;
};

const handleDialogOpenChange = (open: boolean) => {
  createSupplier.value = open;
  if (!open) {
    resetForm();
  }
};
</script>

<template>
  <AppLayout>
    <div class="w-full px-2 sm:px-4 py-4 max-w-7xl mx-auto">
      <div class="rounded-lg border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
        <!-- Encabezado Adaptativo -->
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 border-b p-3 sm:p-4">
          <!-- Buscador -->
          <form @submit.prevent="searchData" class="flex w-full sm:w-80 items-center gap-2">
            <Input
              v-model="searchValue"
              placeholder="Buscar proveedor..."
              type="search"
              class="w-full"
            />
            <Button type="submit" variant="outline" size="icon" aria-label="Buscar proveedor">
              <Search class="h-4 w-4" />
            </Button>
          </form>

          <!-- Botón Nuevo Proveedor -->
          <Button
            class="w-full sm:w-auto justify-center h-10 px-4 bg-emerald-600 hover:bg-emerald-700 text-white"
            @click="openCreate"
          >
            <PackagePlus class="w-5 h-5 mr-2" />
            Nuevo Proveedor
          </Button>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Código</TableHead>
                <TableHead>Nombre Comercial</TableHead>
                <TableHead>Contacto</TableHead>
                <TableHead>Teléfono</TableHead>
                <TableHead>Correo</TableHead>
                <TableHead class="text-right">Acciones</TableHead>
              </TableRow>
            </TableHeader>

            <TableBody>
              <TableRow v-if="!propsW.suppliers.data || propsW.suppliers.data.length === 0">
                <TableCell colspan="6" class="h-24 text-center text-muted-foreground">
                  No se encontraron proveedores.
                </TableCell>
              </TableRow>

              <TableRow
                v-for="data in propsW.suppliers.data"
                :key="data.uuid ?? `${data.code}-${data.company_name}`"
              >
                <TableCell class="font-semibold">{{ data.code }}</TableCell>
                <TableCell>{{ data.company_name }}</TableCell>
                <TableCell>
                  <span>{{ data.contact || 'N/A' }}</span>
                </TableCell>
                <TableCell>
                  <span>{{ data.phone || 'N/A' }}</span>
                </TableCell>
                <TableCell>
                  <span>{{ data.email || 'N/A' }}</span>
                </TableCell>
                <TableCell class="text-right">
                  <div class="flex items-center justify-end gap-2">
                    <Button
                      type="button"
                      variant="outline"
                      size="icon"
                      class="h-9 w-9"
                      title="Editar"
                      aria-label="Editar proveedor"
                      @click="editData(data)"
                    >
                      <FilePenLine class="w-4 h-4 text-blue-600" />
                    </Button>

                    <Button
                      type="button"
                      variant="outline"
                      size="icon"
                      class="h-9 w-9 border-destructive/40 hover:bg-destructive/10"
                      title="Eliminar"
                      aria-label="Eliminar proveedor"
                      @click="deleteData(data)"
                    >
                      <Shredder class="w-4 h-4 text-destructive" />
                    </Button>
                  </div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>

        <!-- Paginación -->
        <div
          v-if="propsW.suppliers.meta"
          class="flex flex-col sm:flex-row items-center justify-between gap-3 border-t px-4 py-3 text-sm"
        >
          <!-- <div class="flex items-center gap-2 text-muted-foreground">
            <span>Filas por página</span>
            <Select
              :model-value="String(propsW.suppliers.meta.per_page)"
              @update:model-value="changePerPage"
            >
              <SelectTrigger class="h-8 w-[76px]">
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="option in paginationOptions"
                  :key="option"
                  :value="String(option)"
                >
                  {{ option }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div> -->

          <!-- <div class="flex items-center gap-4">
            <span class="text-muted-foreground">
              Página {{ propsW.suppliers.meta.current_page }} de {{ propsW.suppliers.meta.last_page }}
              ({{ propsW.suppliers.meta.total }} registros)
            </span>

            <div class="flex items-center gap-1">
              <Button
                variant="outline"
                size="icon"
                class="h-8 w-8"
                :disabled="propsW.suppliers.meta.current_page <= 1"
                @click="goToPage(1)"
              >
                <ChevronsLeft class="h-4 w-4" />
              </Button>
              <Button
                variant="outline"
                size="icon"
                class="h-8 w-8"
                :disabled="propsW.suppliers.meta.current_page <= 1"
                @click="goToPage(propsW.suppliers.meta.current_page - 1)"
              >
                <ChevronLeft class="h-4 w-4" />
              </Button>
              <Button
                variant="outline"
                size="icon"
                class="h-8 w-8"
                :disabled="propsW.suppliers.meta.current_page >= propsW.suppliers.meta.last_page"
                @click="goToPage(propsW.suppliers.meta.current_page + 1)"
              >
                <ChevronRight class="h-4 w-4" />
              </Button>
              <Button
                variant="outline"
                size="icon"
                class="h-8 w-8"
                :disabled="propsW.suppliers.meta.current_page >= propsW.suppliers.meta.last_page"
                @click="goToPage(propsW.suppliers.meta.last_page)"
              >
                <ChevronsRight class="h-4 w-4" />
              </Button>
            </div>
          </div> -->
        </div>
      </div>

      <!-- Modal de Registro / Edición -->
      <Dialog
        :open="createSupplier"
        @update:open="handleDialogOpenChange"
      >
        <DialogContent class="sm:max-w-[680px]">
          <DialogHeader>
            <DialogTitle>
              {{ isUpdate ? 'Editar Proveedor' : 'Nuevo Proveedor' }}
            </DialogTitle>
          </DialogHeader>

          <div class="py-2">
            <FRegister
              :paymentTypes="propsW.paymentTypes"
              :update="isUpdate"
              :supplierEdit="selectedSupplier"
              @close="handleDialogOpenChange(false)"
            />
          </div>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
</template>
