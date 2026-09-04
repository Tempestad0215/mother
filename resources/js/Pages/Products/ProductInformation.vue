<script setup lang="ts">
import { inject, ref } from 'vue';
import { categoryBaseI } from '@/Interfaces/CategoriesInterface';
import { SupplierI } from '@/Interfaces/SupplierInterface';
import { useRoute } from 'ziggy-js';
import { formProductKey, productDataKey } from '@/Injections/InjectionKeys';
import { AutoComplete, Button, Dialog, FloatLabel, InputText, Select, useToast } from 'primevue';
import { router } from '@inertiajs/vue3';
import FRegisterCategory from '@/Pages/Categories/FRegister.vue';
import FRegisterSupplier from '@/Pages/Suppliers/FRegister.vue';
import { PaymentTypeEnumI } from '@/Interfaces/GlobalInterface';
import { CirclePlus, Printer } from '@lucide/vue';
import axios from 'axios';

const route = useRoute();
const toast = useToast();

const propsW = defineProps<{
  categories: categoryBaseI[];
  suppliers: SupplierI[];
  paymentTypes: PaymentTypeEnumI;
  code?: string;
  update: boolean;
}>();

const form = inject(formProductKey)!!;
const productDataOption = inject(productDataKey);
const createCategory = ref(false);
const createSupplier = ref(false);
const loadingLabel = ref(false);

const searchProduct = () => {
  router.get(
    route('product.index', { search: form.name }),
    {},
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  );
};

const printLabel = async () => {
  if (propsW.code) {
    loadingLabel.value = true;
    try {
      const response = await axios.get(route('product.get-label', { code: propsW.code }), {
        responseType: 'blob',
      });
      const blob = new Blob([response.data], { type: 'application/pdf' });
      const url = window.URL.createObjectURL(blob);
      const iframe = document.createElement('iframe');
      iframe.style.display = 'none';
      iframe.src = url;
      document.body.appendChild(iframe);

      iframe.onload = () => {
        iframe.contentWindow?.focus();
        iframe.contentWindow?.print();
        setTimeout(() => {
          window.URL.revokeObjectURL(url);
          document.body.removeChild(iframe);
        }, 3000);
      };
    } catch (_) {
      toast.add({
        summary: 'Error',
        detail: 'No se pudo imprimir el label del producto',
        severity: 'error',
        life: 3000,
      });
    } finally {
      loadingLabel.value = false;
    }
  }
};
</script>

<template>
  <div class="space-y-4">
    <!-- Barra Superior de Acciones (Botones Crear / Imprimir) -->
    <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3">
      <div class="flex w-30">
        <Button
          v-if="propsW.update"
          :loading="loadingLabel"
          type="button"
          @click="printLabel()"
          severity="secondary"
          outlined
          class="w-20! px-5 xs sm:text-sm"
          title="Imprimir Label"
        >
          <span class="w-30">Etiqueta</span>
        </Button>
      </div>

      <div class="flex flex-col sm:flex-row gap-2">
        <Button
          type="button"
          @click="createCategory = true"
          label="Nueva Categoría"
          severity="success"
          outlined
          class="h-9 text-xs sm:text-sm"
        >
          <template #icon>
            <CirclePlus class="w-4 h-4 mr-1" />
          </template>
        </Button>

        <Button
          type="button"
          @click="createSupplier = true"
          label="Nuevo Proveedor"
          severity="info"
          outlined
          class="h-9 text-xs sm:text-sm"
        >
          <template #icon>
            <CirclePlus class="w-4 h-4 mr-1" />
          </template>
        </Button>
      </div>
    </div>

    <!-- Campos de Texto y Selects -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
      <FloatLabel variant="on" class="w-full">
        <AutoComplete
          fluid
          :suggestions="productDataOption"
          option-label="name"
          @valueChange="searchProduct"
          id="name"
          v-model="form.name"
          required
        />
        <label for="name">Nombre del Producto <span class="text-red-500">*</span></label>
      </FloatLabel>

      <FloatLabel variant="on" class="w-full">
        <InputText fluid id="description" v-model="form.description" />
        <label for="description">Descripción</label>
      </FloatLabel>

      <FloatLabel variant="on" class="w-full">
        <Select
          fluid
          v-model="form.category_uuid"
          option-value="uuid"
          id="category"
          option-label="name"
          :options="categories"
        />
        <label for="category">Categoría</label>
      </FloatLabel>

      <FloatLabel variant="on" class="w-full">
        <Select
          fluid
          v-model="form.supplier_uuid"
          option-value="uuid"
          id="supplier"
          option-label="company_name"
          :options="suppliers"
        />
        <label for="supplier">Proveedor</label>
      </FloatLabel>
    </div>
  </div>

  <!-- Diálogos Auxiliares -->
  <Dialog
    v-model:visible="createCategory"
    modal
    dismissableMask
    header="Nueva Categoría"
    :breakpoints="{ '960px': '75vw', '641px': '95vw' }"
    :style="{ width: '40vw' }"
  >
    <FRegisterCategory :category-edit="null" @close="createCategory = false" />
  </Dialog>

  <Dialog
    v-model:visible="createSupplier"
    modal
    dismissableMask
    header="Nuevo Proveedor"
    :breakpoints="{ '960px': '75vw', '641px': '95vw' }"
    :style="{ width: '45vw' }"
  >
    <FRegisterSupplier
      :paymentTypes="paymentTypes"
      :update="false"
      :supplierEdit="null"
      @close="createSupplier = false"
    />
  </Dialog>
</template>
