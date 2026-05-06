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
  <Fieldset legend="Detalle">
    <div class="grid grid-cols-2 gap-3">
      <div class="flex items-center space-x-3">
        <ToggleSwitch :disabled="isService" id="has_inventoried" v-model="form.inventoried" />
        <label for="has_inventoried">Inventariar</label>
      </div>
      <div class="flex items-center space-x-3">
        <ToggleSwitch :disabled="isService" id="has_fraction" v-model="form.has_fraction" />
        <label for="has_fraction">Fraccionar</label>
      </div>
      <div class="flex items-center space-x-3">
        <ToggleSwitch id="has_status" v-model="form.status" />
        <label for="has_status">Estado</label>
      </div>
      <div class="flex items-center space-x-3">
        <ToggleSwitch id="has_itbis" v-model="form.has_tax" />
        <label for="has_itbis">Itbis</label>
      </div>
      <div class="flex items-center space-x-3">
        <ToggleSwitch id="has_special" v-model="form.has_special" />
        <label for="has_special">P. Especial</label>
      </div>
      <div class="flex items-center space-x-3">
        <ToggleSwitch id="has_promotion" v-model="form.has_promotion" />
        <label for="has_promotion">Promocion</label>
      </div>
      <div class="flex items-center space-x-3">
        <ToggleSwitch id="is_service" v-model="form.is_service" />
        <label for="is_service">Servicio</label>
      </div>
    </div>
  </Fieldset>
</template>
