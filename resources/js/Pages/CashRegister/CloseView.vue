<script setup lang="ts">
import { Button, Card, FloatLabel, InputNumber, Toast } from 'primevue';

const propsW = defineProps<{
  cashRegister: any;
  mustCloseOld: boolean;
}>();
</script>

<template>
  <div class="flex justify-center items-center h-screen">
    <Card class="max-w-sm flex-1">
      <template #content>
        <form>
          <h3 class="text-3xl font-bold text-center">Cierre de Caja</h3>
          <FloatLabel variant="on" class="mt-5">
            <InputNumber :minFractionDigits="2" :maxFractionDigits="2" fluid />
            <label for="opening_balance">Balance Inicial</label>
          </FloatLabel>
          <div class="mt-3">
            <Button type="submit" label="Registrar" class="w-full" />
          </div>
        </form>
      </template>
    </Card>
  </div>
  <Toast />
</template>

<style scoped></style>
