<script setup lang="ts">
import { ProductTypeEnumI } from '@/Interfaces/ProductInterface';
import FRegisterWarehouse from '@/Pages/Setting/WH/FRegister.vue';
import { inject, ref } from 'vue';
import { formProductKey } from '@/Injections/InjectionKeys';
import { Dialog, Fieldset, FloatLabel, InputText } from 'primevue';

const propsW = defineProps<{
  productType: ProductTypeEnumI;
}>();

const form = inject(formProductKey)!!;
const createWarehouse = ref<boolean>(false);
</script>

<template>
  <Fieldset
    legend="Códigos de Identificación"
    class="w-full border border-slate-200 rounded-lg p-3"
  >
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
      <FloatLabel variant="on" class="w-full">
        <InputText fluid id="sku" v-model="form.sku" />
        <label for="sku">Código Externo / SKU</label>
      </FloatLabel>

      <FloatLabel variant="on" class="w-full">
        <InputText fluid id="bar_code" v-model="form.bar_code" />
        <label for="bar_code">Código de Barra</label>
      </FloatLabel>
    </div>

    <Dialog
      v-model:visible="createWarehouse"
      modal
      dismissableMask
      header="Crear Almacén"
      :breakpoints="{ '960px': '75vw', '641px': '95vw' }"
      :style="{ width: '40vw' }"
    >
      <FRegisterWarehouse :edit-ware-houses="null" :update="false" />
    </Dialog>
  </Fieldset>
</template>
