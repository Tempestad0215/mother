<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import {
    FilePenLine,
    ListFilterPlus,
    Search,
    Shredder
} from 'lucide-vue-next';
import { ref } from 'vue';
import { useRoute } from 'ziggy-js';

import { Button } from '@/Components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/Components/ui/dialog';
import { Input } from '@/Components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import AppLayout from '@/Layouts/AppLayout.vue';
import FRegister from '@/Pages/Categories/FRegister.vue';

import { getSearchTable } from '@/Global/SearchTable';
import type { categoryBaseI } from '@/Interfaces/CategoriesInterface';
import type { PaginationI } from '@/Interfaces/GlobalInterface';

const route = useRoute();

const propsW = defineProps<{
  categories: PaginationI<categoryBaseI>;
  categoryEdit?: categoryBaseI;
}>();

const searchValue = ref('');
const createCategory = ref(false);
const categorySelected = ref<categoryBaseI | null>(null);
const isUpdate = ref<boolean>(false);

const navigateWithParams = (page: number, perPage: number) => {
  getSearchTable(
    route('category.index', {
      search: searchValue.value || undefined,
      per_page: perPage,
      page,
    })
  );
};

const searchData = () => {
  navigateWithParams(1, Number(propsW.categories.meta.per_page) || 10);
};

const goToPage = (page: number) => {
  const lastPage = Number(propsW.categories.meta.last_page) || 1;
  const clampedPage = Math.max(1, Math.min(page, lastPage));
  navigateWithParams(clampedPage, Number(propsW.categories.meta.per_page) || 10);
};

const changePerPage = (value: string) => {
  navigateWithParams(1, Number(value));
};

const openCreate = () => {
  categorySelected.value = null;
  isUpdate.value = false;
  createCategory.value = true;
};

const editData = (data: categoryBaseI) => {
  categorySelected.value = data;
  isUpdate.value = true;
  createCategory.value = true;
};

const deleteData = (data: categoryBaseI) => {
  router.delete(route('category.destroy', { category: data.uuid }), {
    preserveScroll: true,
  });
};

const resetForm = () => {
  categorySelected.value = null;
  isUpdate.value = false;
};

const handleDialogOpenChange = (open: boolean) => {
  createCategory.value = open;
  if (!open) {
    resetForm();
  }
};
</script>

<template>
 <!--
  <AppLayout>
    <div class="w-full px-2 sm:px-4 py-4 max-w-7xl mx-auto">
      <div class="rounded-lg border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
        <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 border-b p-3 sm:p-4">
          <form @submit.prevent="searchData" class="flex w-full sm:w-80 items-center gap-2">
            <Input
              v-model="searchValue"
              placeholder="Buscar categoría..."
              type="search"
              class="w-full"
            />
            <Button type="submit" variant="outline" size="icon" aria-label="Buscar categoría">
              <Search class="h-4 w-4" />
            </Button>
          </form>

          <Button
            class="w-full sm:w-auto justify-center h-10 px-4"
            @click="openCreate"
          >
            <ListFilterPlus class="w-5 h-5 mr-2" />
            Nueva Categoría
          </Button>
        </div>

        <div class="overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Código</TableHead>
                <TableHead>Nombre</TableHead>
                <TableHead>Descripción</TableHead>
                <TableHead class="text-right">Acciones</TableHead>
              </TableRow>
            </TableHeader>

            <TableBody>
              <TableRow v-if="!propsW.categories.data || propsW.categories.data.length === 0">
                <TableCell colspan="4" class="h-24 text-center text-muted-foreground">
                  No se encontraron categorías.
                </TableCell>
              </TableRow>

              <TableRow
                v-for="data in propsW.categories.data"
                :key="data.uuid ?? `${data.code}-${data.name}`"
              >
                <TableCell class="font-semibold">{{ data.code }}</TableCell>
                <TableCell>{{ data.name }}</TableCell>
                <TableCell>
                  <span class="text-muted-foreground">
                    {{ data.description || 'Sin descripción' }}
                  </span>
                </TableCell>
                <TableCell class="text-right">
                  <div class="flex items-center justify-end gap-2">
                    <Button
                      type="button"
                      variant="outline"
                      size="icon"
                      class="h-9 w-9"
                      title="Editar"
                      aria-label="Editar categoría"
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
                      aria-label="Eliminar categoría"
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
        <!-- <div
          v-if="propsW.categories.meta"
          class="flex flex-col sm:flex-row items-center justify-between gap-3 border-t px-4 py-3 text-sm"
        >
          <div class="flex items-center gap-2 text-muted-foreground">
            <span>Filas por página</span>
            <Select
              :model-value="String(propsW.categories.meta.per_page)"
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
          </div>

          <div class="flex items-center gap-4">
            <span class="text-muted-foreground">
              Página {{ propsW.categories.meta.current_page }} de {{ propsW.categories.meta.last_page }}
              ({{ propsW.categories.meta.total }} registros)
            </span>

            <div class="flex items-center gap-1">
              <Button
                variant="outline"
                size="icon"
                class="h-8 w-8"
                :disabled="propsW.categories.meta.current_page <= 1"
                @click="goToPage(1)"
              >
                <ChevronsLeft class="h-4 w-4" />
              </Button>
              <Button
                variant="outline"
                size="icon"
                class="h-8 w-8"
                :disabled="propsW.categories.meta.current_page <= 1"
                @click="goToPage(propsW.categories.meta.current_page - 1)"
              >
                <ChevronLeft class="h-4 w-4" />
              </Button>
              <Button
                variant="outline"
                size="icon"
                class="h-8 w-8"
                :disabled="propsW.categories.meta.current_page >= propsW.categories.meta.last_page"
                @click="goToPage(propsW.categories.meta.current_page + 1)"
              >
                <ChevronRight class="h-4 w-4" />
              </Button>
              <Button
                variant="outline"
                size="icon"
                class="h-8 w-8"
                :disabled="propsW.categories.meta.current_page >= propsW.categories.meta.last_page"
                @click="goToPage(propsW.categories.meta.last_page)"
              >
                <ChevronsRight class="h-4 w-4" />
              </Button>
            </div>
          </div>
        </div>


      <Dialog
        :open="createCategory"
        @update:open="handleDialogOpenChange"
      >
        <DialogContent class="sm:max-w-[560px]">
          <DialogHeader>
            <DialogTitle>
              {{ isUpdate ? 'Editar Categoría' : 'Nueva Categoría' }}
            </DialogTitle>
          </DialogHeader>

          <div class="py-2">
            <FRegister
              :update="isUpdate"
              :categoryEdit="categorySelected"
              @close="handleDialogOpenChange(false)"
            />
          </div>
        </DialogContent>
      </Dialog>
    </div>
  </AppLayout>
  -->
</template>
