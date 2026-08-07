<script setup lang="ts">
import { getMoney } from '@/Global/Helpers';
import { saleKey } from '@/utils/keys';
import { inject } from 'vue';
import { Textarea, FloatLabel } from 'primevue';

const form = inject(saleKey)!;
</script>

<template>
  <div class="flex flex-col md:flex-row items-stretch md:items-end justify-between gap-4 pt-2">
    <!-- Campo de Comentarios / Observaciones -->
    <div class="w-full md:w-80">
      <FloatLabel variant="on" class="w-full">
        <Textarea
          id="comment"
          class="w-full min-h-[80px] max-h-[120px]"
          rows="3"
          v-model.trim="form.comment"
          autoResize
        />
        <label for="comment">Comentario u Observaciones</label>
      </FloatLabel>
    </div>

    <!-- Desglose de Totales -->
    <div
      class="w-full md:w-80 bg-slate-50 text-slate-800 rounded-xl p-4 shadow-sm border border-slate-200 text-sm space-y-2"
    >
      <div class="flex justify-between items-center">
        <span class="text-slate-600 font-medium">Sub Total:</span>
        <span class="font-semibold text-slate-700">{{ getMoney(form.sub_total) }}</span>
      </div>

      <div class="flex justify-between items-center">
        <span class="text-slate-600 font-medium">ITBIS:</span>
        <span class="font-semibold text-blue-600">{{ getMoney(form.tax) }}</span>
      </div>

      <div class="flex justify-between items-center">
        <span class="text-slate-600 font-medium">Descuento:</span>
        <span class="font-semibold text-emerald-600">-{{ getMoney(form.discount_amount) }}</span>
      </div>

      <div class="border-t border-slate-200 pt-2 mt-2 flex justify-between items-center text-base">
        <span class="font-bold text-slate-900">Total:</span>
        <span class="font-bold text-lg text-emerald-700">{{ getMoney(form.amount) }}</span>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
