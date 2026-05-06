<script setup lang="ts">
import { getMoney } from '@/Global/Helpers';
import { saleKey } from '@/utils/keys';
import { inject } from 'vue';
import { Textarea, FloatLabel } from 'primevue';

const form = inject(saleKey)!;
</script>

<template>
  <!--                        Dato de la ventas-->
  <div class="flex items-center justify-between">
    <div class="flex-1 mt-3">
      <FloatLabel variant="on">
        <Textarea class="max-w-60 max-h-26" :rows="3" :cols="30" v-model.trim="form.comment" />
        <label for="comment">Comentario</label>
      </FloatLabel>
    </div>
    <!--                            Mensaje generales-->
    <div class="w-full max-w-sm ml-auto space-y-2 text-sm mt-3">
      <div class="flex justify-between">
        <span class="text-gray-600">Sub Total</span>
        <span class="font-medium">{{ getMoney(form.sub_total) }}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-600">ITBIS</span>
        <span class="font-medium">{{ getMoney(form.tax) }}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-gray-600">Descuento</span>
        <span class="font-medium text-emerald-600">-{{ getMoney(form.discount_amount) }}</span>
      </div>
      <div class="border-t pt-2 mt-2 flex justify-between text-base">
        <span class="font-semibold">Total</span>
        <span class="font-semibold text-primary-600">{{ getMoney(form.amount) }}</span>
      </div>
    </div>
    <div class="clear-both"></div>
  </div>
</template>

<style scoped></style>
