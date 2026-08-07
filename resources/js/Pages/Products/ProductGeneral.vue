<script setup lang="ts">
import { ToggleSwitch, Fieldset } from 'primevue';
import { computed, inject } from 'vue';
import { formProductKey } from '@/Injections/InjectionKeys';

const form = inject(formProductKey)!!;

const isService = computed(() => {
  if (form.is_service) {
    form.inventoried = false;
    form.has_fraction = false;
    return true;
  } else {
    return false;
  }
});
</script>

<template>
  <Fieldset legend="Configuración de Estado" class="w-full border border-slate-200 rounded-lg p-3">
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-1 text-sm">
      <div class="flex items-center gap-2">
        <ToggleSwitch inputId="has_inventoried" :disabled="isService" v-model="form.inventoried" />
        <label for="has_inventoried" class="cursor-pointer font-medium text-slate-700"
          >Inventariar</label
        >
      </div>

      <div class="flex items-center gap-2">
        <ToggleSwitch inputId="has_fraction" :disabled="isService" v-model="form.has_fraction" />
        <label for="has_fraction" class="cursor-pointer font-medium text-slate-700"
          >Fraccionar</label
        >
      </div>

      <div class="flex items-center gap-2">
        <ToggleSwitch inputId="has_status" v-model="form.status" />
        <label for="has_status" class="cursor-pointer font-medium text-slate-700">Estado</label>
      </div>

      <div class="flex items-center gap-2">
        <ToggleSwitch inputId="has_itbis" v-model="form.has_tax" />
        <label for="has_itbis" class="cursor-pointer font-medium text-slate-700">ITBIS</label>
      </div>

      <div class="flex items-center gap-2">
        <ToggleSwitch inputId="has_special" v-model="form.has_special" />
        <label for="has_special" class="cursor-pointer font-medium text-slate-700"
          >P. Especial</label
        >
      </div>

      <div class="flex items-center gap-2">
        <ToggleSwitch inputId="has_promotion" v-model="form.has_promotion" />
        <label for="has_promotion" class="cursor-pointer font-medium text-slate-700"
          >Promoción</label
        >
      </div>

      <div class="flex items-center gap-2 col-span-2 sm:col-span-1">
        <ToggleSwitch inputId="is_service" v-model="form.is_service" />
        <label for="is_service" class="cursor-pointer font-medium text-slate-700"
          >Es Servicio</label
        >
      </div>
    </div>
  </Fieldset>
</template>
