<script setup lang="ts">
import { computed, onMounted } from 'vue';
import {
  Breadcrumb,
  Button,
  Card,
  Divider,
  FloatLabel,
  InputNumber,
  Toast,
  useToast,
} from 'primevue';
import { CashRegisterCloseDataI } from '@/Interfaces/CashRegisterInterface';
import { useForm } from '@inertiajs/vue3';
import { SaleBreadCrumbs } from '@/Helpers/SaleHelper';

const toast = useToast();

const propsW = defineProps<{
  cashRegister: CashRegisterCloseDataI;
}>();

const form = useForm({
  physical_cash: 0.0,
  expected_balance: 0.0,
});

onMounted(() => {
  form.expected_balance = parseFloat(propsW.cashRegister.expected_balance);
});

// 🧮 Convierte el balance esperado de string a número para operaciones en el Front
const expectedBalanceNum = computed(() => parseFloat(propsW.cashRegister.expected_balance));

// ⚖️ Calcula la diferencia en tiempo real (Dinero Real - Dinero Esperado)
const difference = computed(() => {
  if (form.physical_cash === 0.0) return 0;
  return form.physical_cash - expectedBalanceNum.value;
});

// 🚀 Función para enviar el cierre a Laravel
const submitClose = () => {
  if (form.physical_cash === 0.0) return;

  form.patch(route('cash-register.close.store', { cashRegister: propsW.cashRegister.uuid }), {
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'Caja Cerrada Exitosamente',
        life: 3000,
      });
    },
    onError: (err) => {
      toast.add({
        severity: 'error',
        summary: 'Error Al Cerar',
        detail: 'Este Registro No Pudo Ser Completado, Detalle :' + Object.values(err)[0],
        life: 5000,
      });
    },
  });

  // Aquí disparas tu post de Inertia o axios enviando 'physicalCash.value'
  console.log('Enviando cierre con efectivo físico:', form.physical_cash);
};
</script>

<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-50 p-4">
    <Card class="w-full max-w-xl shadow-lg">
      <template #header>
        <Breadcrumb :model="SaleBreadCrumbs" />
      </template>
      <template #content>
        <form @submit.prevent="submitClose" class="space-y-5">
          <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-800">Arqueo y Cierre de Caja</h3>
            <p class="text-sm text-gray-500">
              ID de Caja: {{ propsW.cashRegister.uuid.slice(0, 8) }}...
            </p>
          </div>

          <Divider type="dashed" />

          <div class="bg-slate-100 p-4 rounded-lg space-y-2">
            <div class="flex justify-between text-sm text-gray-600">
              <span>(+) Fondo de Apertura:</span>
              <span class="font-mono">${{ propsW.cashRegister.opening_balance }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
              <span>(+) Ventas Efectivo (Contado):</span>
              <span class="font-mono text-green-600"
                >+${{ propsW.cashRegister.total_contado }}</span
              >
            </div>
            <div class="flex justify-between text-sm text-gray-600">
              <span>(+) Entradas Manuales (Income):</span>
              <span class="font-mono text-green-600">+${{ propsW.cashRegister.total_income }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
              <span>(-) Gastos de Caja:</span>
              <span class="font-mono text-red-600">-${{ propsW.cashRegister.total_expense }}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
              <span>(-) Entregas a Bóveda:</span>
              <span class="font-mono text-red-600"
                >-${{ propsW.cashRegister.total_vault_deposit }}</span
              >
            </div>

            <Divider />

            <div class="flex justify-between text-lg font-bold text-gray-800">
              <span>🎯 Efectivo Esperado en Gaveta:</span>
              <span class="font-mono text-blue-700"
                >${{ propsW.cashRegister.expected_balance }}</span
              >
            </div>
          </div>

          <div>
            <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
              Totales de Control (No Efectivo)
            </h4>
            <div class="grid grid-cols-2 gap-3 bg-gray-50 p-3 rounded-md border text-sm">
              <div class="flex justify-between">
                <span class="text-gray-500">Tarjetas:</span>
                <span class="font-semibold font-mono"
                  >${{ propsW.cashRegister.total_tarjeta }}</span
                >
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Transferencias:</span>
                <span class="font-semibold font-mono"
                  >${{ propsW.cashRegister.total_transferencia }}</span
                >
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Créditos Emitidos:</span>
                <span class="font-semibold font-mono"
                  >${{ propsW.cashRegister.total_credito }}</span
                >
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Cheques:</span>
                <span class="font-semibold font-mono">${{ propsW.cashRegister.total_cheque }}</span>
              </div>
            </div>
          </div>

          <div class="space-y-3 mt-5">
            <FloatLabel variant="on" class="w-full">
              <InputNumber
                id="physical_cash"
                v-model="form.physical_cash"
                :minFractionDigits="2"
                :maxFractionDigits="2"
                fluid
                class="text-lg font-mono font-bold"
                required
              />
              <label for="physical_cash">¿Cuánto Efectivo Físico Contaste?</label>
            </FloatLabel>

            <div v-if="form.physical_cash !== 0.0">
              <div
                v-if="difference === 0"
                class="p-3 bg-emerald-50 text-emerald-700 text-center rounded-md font-medium text-sm"
              >
                ¡Caja cuadrada a la perfección! 🎉
              </div>
              <div
                v-else-if="difference > 0"
                class="p-3 bg-amber-50 text-amber-700 rounded-md text-sm flex justify-between"
              >
                <span>⚠️ Hay dinero de más (Sobrante):</span>
                <span class="font-bold font-mono">+${{ difference.toFixed(2) }}</span>
              </div>
              <div
                v-else
                class="p-3 bg-rose-50 text-rose-700 rounded-md text-sm flex justify-between"
              >
                <span>🚨 Falta dinero en la caja (Faltante):</span>
                <span class="font-bold font-mono">${{ difference.toFixed(2) }}</span>
              </div>
            </div>
          </div>

          <div class="pt-2">
            <Button
              type="submit"
              label="Efectuar Cierre de Caja"
              icon="pi pi-lock"
              class="w-full p-button-lg"
              :disabled="form.physical_cash === 0"
            />
          </div>
        </form>
      </template>
    </Card>
  </div>
  <Toast />
</template>
