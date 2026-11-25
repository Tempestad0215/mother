<script setup lang="ts">
import { Form, Head, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { RotateCcw, Search } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import { useRoute } from 'ziggy-js';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { productBreadCrumb } from '@/Helpers/ProductHelper';
import type { productI, ProductTableI } from '@/Interfaces/ProductInterface';
import type { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import AppLayout from '@/layouts/AppLayout.vue';

const route = useRoute();
const page = usePage();

const propsW = defineProps<{
  products: productI;
  warehouses: Array<WarehouseBaseI>;
}>();

const isOut = computed(() => page.url.replace(/^\/+/, '').startsWith('out'));
const formAction = computed(() =>
  isOut.value ? route('out.store') : route('entry.store'),
);

const searchQuery = ref('');
const suggestions = ref<ProductTableI[]>([]);
const showSuggestions = ref(false);
const selectedProduct = ref<ProductTableI | null>(null);
const selectedWarehouseUuid = ref('');
const formKey = ref(0);

let searchTimeout: ReturnType<typeof setTimeout> | null = null;

watch(searchQuery, (query) => {
  if (searchTimeout) clearTimeout(searchTimeout);

  if (!query.trim()) {
    suggestions.value = [];
    showSuggestions.value = false;
    return;
  }

  // Si el usuario edita el texto tras seleccionar, limpiamos el uuid previo si no coincide
  if (
    selectedProduct.value &&
    query !== `${selectedProduct.value.code} | ${selectedProduct.value.name}`
  ) {
    selectedProduct.value = null;
  }

  searchTimeout = setTimeout(async () => {
    try {
      const res = await axios.get<ProductTableI[]>(route('product.get.json'), {
        params: { search: query },
      });
      suggestions.value = res.data;
      showSuggestions.value = true;
    } catch {
      toast.error('Error al buscar productos');
    }
  }, 250);
});

const selectProduct = (item: ProductTableI) => {
  selectedProduct.value = item;
  searchQuery.value = `${item.code} | ${item.name}`;
  showSuggestions.value = false;
};

const handleReset = (reset: () => void) => {
  reset();
  selectedProduct.value = null;
  selectedWarehouseUuid.value = '';
  searchQuery.value = '';
  suggestions.value = [];
  showSuggestions.value = false;
  formKey.value += 1;
};

const handleSuccess = (reset: () => void) => {
  toast.success('Registro Creado Correctamente');
  handleReset(reset);
};

const handleError = () => {
  toast.error('Error al crear el registro');
};
</script>

<template>
  <Head :title="isOut ? 'Salida' : 'Entrada'" />
  <AppLayout>
    <Card>
      <CardHeader>
        <BreadCrumbComponent :itemOptions="productBreadCrumb" />
      </CardHeader>

      <CardContent>
        <Form
          :key="formKey"
          :action="formAction"
          method="post"
          class="grid grid-cols-1 gap-4 md:grid-cols-2"
          #default="{ processing, errors, reset }"
          @success="handleSuccess(reset)"
          @error="handleError"
        >
          <div class="col-span-full">
            <h3 class="text-center text-2xl font-bold">
              {{ isOut ? 'Salida' : 'Entrada' }} de Mercancía
            </h3>
          </div>

          <!-- Buscador y selección de producto -->
          <div class="relative space-y-2">
            <Label for="product_search">Producto</Label>
            <div class="relative">
              <Search
                class="text-muted-foreground pointer-events-none absolute top-2.5 left-3 h-4 w-4"
              />
              <Input
                id="product_search"
                v-model="searchQuery"
                type="text"
                placeholder="Buscar por código o nombre..."
                class="pl-9"
                autocomplete="off"
                @focus="suggestions.length > 0 && (showSuggestions = true)"
              />
            </div>
            <input
              type="hidden"
              name="product_uuid"
              :value="selectedProduct?.uuid ?? ''"
            />

            <div
              v-if="showSuggestions && suggestions.length > 0"
              class="bg-popover text-popover-foreground absolute z-20 mt-1 max-h-56 w-full overflow-y-auto rounded-md border shadow-md"
            >
              <button
                v-for="item in suggestions"
                :key="item.uuid"
                type="button"
                class="hover:bg-accent hover:text-accent-foreground w-full px-3 py-2 text-left text-sm"
                @click="selectProduct(item)"
              >
                {{ item.code }} | {{ item.name }}
              </button>
            </div>

            <p v-if="errors.product_uuid" class="text-destructive text-sm">
              {{ errors.product_uuid }}
            </p>
          </div>

          <!-- Almacén -->
          <div class="space-y-2">
            <Label for="warehouse_uuid">Almacén</Label>
            <Select v-model="selectedWarehouseUuid">
              <SelectTrigger id="warehouse_uuid" class="w-full">
                <SelectValue placeholder="Selecciona un almacén" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="warehouse in propsW.warehouses"
                  :key="warehouse.uuid"
                  :value="warehouse.uuid"
                >
                  {{ warehouse.name }}
                </SelectItem>
              </SelectContent>
            </Select>
            <input
              type="hidden"
              name="warehouse_uuid"
              :value="selectedWarehouseUuid"
            />
            <p v-if="errors.warehouse_uuid" class="text-destructive text-sm">
              {{ errors.warehouse_uuid }}
            </p>
          </div>

          <!-- Costo (solo en entrada) -->
          <div v-if="!isOut" class="space-y-2">
            <Label for="cost">Costo</Label>
            <Input
              id="cost"
              name="cost"
              type="number"
              step="any"
              min="0"
              :default-value="0"
            />
            <p v-if="errors.cost" class="text-destructive text-sm">
              {{ errors.cost }}
            </p>
          </div>

          <!-- Cantidad -->
          <div class="space-y-2">
            <Label for="quantity">Cantidad</Label>
            <Input
              id="quantity"
              name="quantity"
              type="number"
              step="any"
              min="0"
              :default-value="0"
            />
            <p v-if="errors.quantity" class="text-destructive text-sm">
              {{ errors.quantity }}
            </p>
          </div>

          <!-- Referencia -->
          <div class="space-y-2">
            <Label for="reference">Referencia</Label>
            <Input
              id="reference"
              name="reference"
              type="text"
              default-value=""
            />
            <p v-if="errors.reference" class="text-destructive text-sm">
              {{ errors.reference }}
            </p>
          </div>

          <!-- Error general si viene alguno adicional -->
          <div
            v-if="Object.keys(errors).length > 0"
            class="col-span-full"
          >
            <p class="text-destructive text-sm font-medium">
              {{ Object.values(errors)[0] }}
            </p>
          </div>

          <!-- Acciones -->
          <div class="col-span-full mt-2 flex justify-end space-x-3">
            <Button
              type="button"
              variant="secondary"
              @click="handleReset(reset)"
            >
              <RotateCcw class="mr-2 h-4 w-4" />
              Limpiar
            </Button>
            <Button type="submit" :disabled="processing">
              Registrar
            </Button>
          </div>
        </Form>
      </CardContent>
    </Card>
  </AppLayout>
</template>
