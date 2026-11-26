<script setup lang="ts">
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { useRoute } from 'ziggy-js';
import {
  ChevronLeft,
  ChevronRight,
  ChevronsLeft,
  ChevronsRight,
  Search,
} from 'lucide-vue-next';

import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
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

import type { ProductTableI } from '@/Interfaces/ProductInterface';
import type { PaginationI } from '@/Interfaces/GlobalInterface';
import type { PriceListProducts } from '@/Interfaces/PriceListInterface';
import { getInfoFromWarehouse, productBreadCrumb } from '@/Helpers/ProductHelper';
import { PreciseCalculator } from '@/utils/Decimal';
import { paginationOptions } from '@/Global/Helpers';

const route = useRoute();
const { component } = usePage();

interface PropsI {
  products: PaginationI<ProductTableI>;
  stock?: boolean;
  isProduct?: boolean;
  mode?: 'CRUD' | 'SELECT';
  searchRoute?: string;
  searchRouteParams?: Record<string, unknown>;
  searchOnly?: string[];
}

const propsW = withDefaults(defineProps<PropsI>(), {
  stock: false,
  isProduct: true,
  mode: 'CRUD',
  searchRoute: undefined,
  searchRouteParams: () => ({}),
  searchOnly: () => ['products'],
});

const searchValue = ref('');

const navigateWithParams = (page: number, perPage: number) => {
  const targetRoute = propsW.searchRoute;
  if (!targetRoute) {
    router.get(
      window.location.pathname,
      {
        search: searchValue.value || undefined,
        per_page: perPage,
        page,
      },
      {
        preserveState: true,
        preserveScroll: true,
        only: propsW.searchOnly,
      }
    );
    return;
  }

  router.get(
    route(targetRoute, {
      ...propsW.searchRouteParams,
      search: searchValue.value,
      per_page: perPage,
      page,
    }),
    {},
    {
      preserveState: true,
      preserveScroll: true,
      only: propsW.searchOnly,
    }
  );
};

const searchData = () => {
  navigateWithParams(1, Number(propsW.products.meta.per_page) || 10);
};

const goToPage = (page: number) => {
  const lastPage = Number(propsW.products.meta.last_page) || 1;
  const clampedPage = Math.max(1, Math.min(page, lastPage));
  navigateWithParams(clampedPage, Number(propsW.products.meta.per_page) || 10);
};

const changePerPage = (value: string) => {
  navigateWithParams(1, Number(value));
};

const getPrice = (uuid: string, priceLists: PriceListProducts[]): number => {
  return priceLists.find((el) => el.uuid === uuid)?.price ?? 0.0;
};
</script>

<template>
  <div class="rounded-lg border border-border bg-card text-card-foreground shadow-sm overflow-hidden">
    <!-- Encabezado Adaptativo -->
    <div class="border-b p-3 sm:p-4 space-y-3">
      <div v-if="propsW.isProduct" class="overflow-x-auto">
        <!-- <BreadCrumbComponent
          :itemOptions="productBreadCrumb"
          class="text-xs sm:text-sm p-0 bg-transparent"
        /> -->
      </div>

      <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
        <form @submit.prevent="searchData" class="flex w-full sm:w-80 items-center gap-2">
          <Input
            v-model="searchValue"
            placeholder="Buscar producto..."
            type="search"
            class="w-full"
          />
          <Button type="submit" variant="outline" size="icon" aria-label="Buscar producto">
            <Search class="h-4 w-4" />
          </Button>
        </form>
      </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Código</TableHead>
            <TableHead>Nombre</TableHead>
            <TableHead v-if="component === 'Products/Register'">Costo</TableHead>
            <TableHead>Precio</TableHead>
            <TableHead>Tipo</TableHead>
            <TableHead v-if="propsW.stock">Stock</TableHead>
            <TableHead class="text-right">Acciones</TableHead>
          </TableRow>
        </TableHeader>

        <TableBody>
          <TableRow v-if="!propsW.products.data || propsW.products.data.length === 0">
            <TableCell
              :colspan="
                5 +
                (component === 'Products/Register' ? 1 : 0) +
                (propsW.stock ? 1 : 0)
              "
              class="h-24 text-center text-muted-foreground"
            >
              No se encontraron productos.
            </TableCell>
          </TableRow>

          <TableRow
            v-for="data in propsW.products.data"
            :key="data.uuid ?? `${data.code}-${data.name}`"
          >
            <TableCell class="font-semibold">{{ data.code }}</TableCell>
            <TableCell>{{ data.name }}</TableCell>

            <TableCell v-if="component === 'Products/Register'">
              <span>{{ PreciseCalculator.formatCurrency(data.cost) }}</span>
            </TableCell>

            <TableCell>
              <span class="font-bold">
                {{
                  PreciseCalculator.formatCurrency(
                    getPrice(data.default_price_list, data.price_lists)
                  )
                }}
              </span>
            </TableCell>

            <TableCell>
              <Badge :variant="data.is_service ? 'secondary' : 'default'">
                {{ data.is_service ? 'Servicio' : 'Producto' }}
              </Badge>
            </TableCell>

            <TableCell v-if="propsW.stock">
              <span class="font-medium">
                {{ getInfoFromWarehouse(data.warehouses, data.default_warehouse)?.available ?? 0 }}
              </span>
            </TableCell>

            <TableCell class="text-right">
              <slot name="actions" :data="data" />
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>

    <!-- Paginación -->
    <div
      v-if="propsW.products.meta"
      class="flex flex-col sm:flex-row items-center justify-between gap-3 border-t px-4 py-3 text-sm"
    >
      <div class="flex items-center gap-2 text-muted-foreground">
        <span>Filas por página</span>
        <Select
          :model-value="String(propsW.products.meta.per_page)"
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
          Página {{ propsW.products.meta.current_page }} de {{ propsW.products.meta.last_page }}
          ({{ propsW.products.meta.total }} registros)
        </span>

        <div class="flex items-center gap-1">
          <Button
            variant="outline"
            size="icon"
            class="h-8 w-8"
            :disabled="propsW.products.meta.current_page <= 1"
            @click="goToPage(1)"
          >
            <ChevronsLeft class="h-4 w-4" />
          </Button>
          <Button
            variant="outline"
            size="icon"
            class="h-8 w-8"
            :disabled="propsW.products.meta.current_page <= 1"
            @click="goToPage(propsW.products.meta.current_page - 1)"
          >
            <ChevronLeft class="h-4 w-4" />
          </Button>
          <Button
            variant="outline"
            size="icon"
            class="h-8 w-8"
            :disabled="propsW.products.meta.current_page >= propsW.products.meta.last_page"
            @click="goToPage(propsW.products.meta.current_page + 1)"
          >
            <ChevronRight class="h-4 w-4" />
          </Button>
          <Button
            variant="outline"
            size="icon"
            class="h-8 w-8"
            :disabled="propsW.products.meta.current_page >= propsW.products.meta.last_page"
            @click="goToPage(propsW.products.meta.last_page)"
          >
            <ChevronsRight class="h-4 w-4" />
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>
