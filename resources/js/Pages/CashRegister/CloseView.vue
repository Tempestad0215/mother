<script setup lang="ts">
import { CashRegisterCloseDataI } from '@/Interfaces/CashRegisterInterface';
import { useForm } from '@inertiajs/vue3';
import { SaleBreadCrumbs } from '@/Helpers/SaleHelper';
import { Card, useToast, Button, Toast, InputNumber, Divider, FloatLabel } from 'primevue';
import { computed, onMounted } from 'vue';
import AppLayout from '@layout/AppLayout.vue';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';
import { getMoney } from '@/Global/Helpers';
import { Lock, CheckCircle2, AlertTriangle, AlertCircle } from '@lucide/vue';

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

// Convierte el balance esperado de string a número para operaciones
const expectedBalanceNum = computed(() => parseFloat(propsW.cashRegister.expected_balance));

// Calcula la diferencia en tiempo real (Dinero Real - Dinero Esperado)
const difference = computed(() => {
  if (form.physical_cash === 0.0) return 0;
  return form.physical_cash - expectedBalanceNum.value;
});

// Función para enviar el cierre a Laravel
const submitClose = () => {
  if (form.physical_cash === 0.0) return;

  form.patch(route('cash-register.close.store', { cashRegister: propsW.cashRegister.uuid }), {
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'Caja Cerrada Exitosamente',
        detail: 'El arqueo de caja fue registrado correctamente.',
        life: 3000,
      });
    },
    onError: (err) => {
      toast.add({
        severity: 'error',
        summary: 'Error al Cerrar',
        detail: `No se pudo completar la solicitud: ${Object.values(err)[0]}`,
        life: 5000,
      });
    },
  });
};
</script>

<template>
  <AppLayout>
    <div class="w-full px-2 sm:px-4 py-4 max-w-2xl mx-auto">
      <Card class="shadow-sm rounded-lg border border-slate-200">
        <template #header>
          <div class="p-3 pb-0">
            <BreadCrumbComponent :itemOptions="SaleBreadCrumbs" />
          </div>
          <Divider class="my-2" />
        </template>

        <template #content>
          <form @submit.prevent="submitClose" class="space-y-5">
            <!-- Encabezado del Arqueo -->
            <div class="text-center space-y-1">
              <h3 class="text-xl sm:text-2xl font-bold text-slate-800">Arqueo y Cierre de Caja</h3>
              <p class="text-xs sm:text-sm text-slate-500 font-mono">
                ID de Caja: {{ propsW.cashRegister.uuid.slice(0, 8) }}...
              </p>
            </div>

            <Divider class="my-2" />

            <!-- Desglose de Operaciones de Efectivo -->
            <div
              class="bg-slate-50 p-3 sm:p-4 rounded-lg border border-slate-200 space-y-2 text-xs sm:text-sm"
            >
              <div class="flex justify-between items-center text-slate-600">
                <span>(+) Fondo de Apertura:</span>
                <span class="font-mono font-medium">{{
                  getMoney(parseFloat(propsW.cashRegister.opening_balance))
                }}</span>
              </div>
              <div class="flex justify-between items-center text-slate-600">
                <span>(+) Ventas Efectivo (Contado):</span>
                <span class="font-mono font-semibold text-emerald-600">
                  +{{ getMoney(parseFloat(propsW.cashRegister.total_contado)) }}
                </span>
              </div>
              <div class="flex justify-between items-center text-slate-600">
                <span>(+) Entradas Manuales (Income):</span>
                <span class="font-mono font-semibold text-emerald-600">
                  +{{ getMoney(parseFloat(propsW.cashRegister.total_income)) }}
                </span>
              </div>
              <div class="flex justify-between items-center text-slate-600">
                <span>(-) Gastos de Caja:</span>
                <span class="font-mono font-semibold text-rose-600">
                  -{{ getMoney(parseFloat(propsW.cashRegister.total_expense)) }}
                </span>
              </div>
              <div class="flex justify-between items-center text-slate-600">
                <span>(-) Entregas a Bóveda:</span>
                <span class="font-mono font-semibold text-rose-600">
                  -{{ getMoney(parseFloat(propsW.cashRegister.total_vault_deposit)) }}
                </span>
              </div>

              <Divider class="my-2 border-slate-200" />

              <div
                class="flex justify-between items-center text-sm sm:text-base font-bold text-slate-800 pt-1"
              >
                <span>Efectivo Esperado en Gaveta:</span>
                <span class="font-mono text-emerald-700 text-base sm:text-lg">
                  {{ getMoney(parseFloat(propsW.cashRegister.expected_balance) ?? 0) }}
                </span>
              </div>
            </div>

            <!-- Totales de Control No Efectivo -->
            <div class="space-y-2">
              <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                Totales de Control (No Efectivo)
              </h4>
              <div
                class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200 text-xs sm:text-sm"
              >
                <div class="flex justify-between items-center">
                  <span class="text-slate-600">Tarjetas:</span>
                  <span class="font-semibold font-mono text-slate-800">
                    {{ getMoney(parseFloat(propsW.cashRegister.total_tarjeta)) ?? 0 }}
                  </span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-slate-600">Transferencias:</span>
                  <span class="font-semibold font-mono text-slate-800">
                    {{ getMoney(parseFloat(propsW.cashRegister.total_transferencia) ?? 0) }}
                  </span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-slate-600">Créditos Emitidos:</span>
                  <span class="font-semibold font-mono text-slate-800">
                    {{ getMoney(parseFloat(propsW.cashRegister.total_credito) ?? 0) }}
                  </span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-slate-600">Cheques:</span>
                  <span class="font-semibold font-mono text-slate-800">
                    {{ getMoney(parseFloat(propsW.cashRegister.total_cheque) ?? 0) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Input Dinero Físico y Estado del Cuadre -->
            <div class="space-y-3 pt-2">
              <FloatLabel variant="on" class="w-full">
                <InputNumber
                  id="physical_cash"
                  v-model="form.physical_cash"
                  currency="DOP"
                  locale="en-US"
                  :minFractionDigits="2"
                  :maxFractionDigits="2"
                  fluid
                  class="w-full font-mono font-bold"
                  required
                />
                <label for="physical_cash">¿Cuánto Efectivo Físico Contaste?</label>
              </FloatLabel>

              <!-- Alertas de Diferencia -->
              <div v-if="form.physical_cash !== 0.0" class="pt-1">
                <div
                  v-if="difference === 0"
                  class="p-3 bg-emerald-50 text-emerald-700 rounded-lg font-medium text-xs sm:text-sm flex items-center justify-center gap-2 border border-emerald-200"
                >
                  <CheckCircle2 class="w-5 h-5 shrink-0" />
                  <span>¡Caja cuadrada a la perfección!</span>
                </div>

                <div
                  v-else-if="difference > 0"
                  class="p-3 bg-amber-50 text-amber-800 rounded-lg text-xs sm:text-sm flex justify-between items-center border border-amber-200"
                >
                  <span class="flex items-center gap-1.5">
                    <AlertTriangle class="w-4 h-4 shrink-0 text-amber-600" />
                    Dinero de más (Sobrante):
                  </span>
                  <span class="font-bold font-mono text-amber-900"
                    >+{{ getMoney(difference) }}</span
                  >
                </div>

                <div
                  v-else
                  class="p-3 bg-rose-50 text-rose-800 rounded-lg text-xs sm:text-sm flex justify-between items-center border border-rose-200"
                >
                  <span class="flex items-center gap-1.5">
                    <AlertCircle class="w-4 h-4 shrink-0 text-rose-600" />
                    Falta dinero en la caja (Faltante):
                  </span>
                  <span class="font-bold font-mono text-rose-900">{{ getMoney(difference) }}</span>
                </div>
              </div>
            </div>

            <!-- Botón para Registrar Cierre -->
            <div class="pt-3">
              <Button
                type="submit"
                label="Efectuar Cierre de Caja"
                class="w-full h-11 text-base font-semibold bg-emerald-600 hover:bg-emerald-700 border-none"
                :disabled="form.physical_cash === 0 || form.processing"
              >
                <template #icon>
                  <Lock class="w-5 h-5 mr-2" />
                </template>
              </Button>
            </div>
          </form>
        </template>
      </Card>
    </div>
    <Toast />
  </AppLayout>
</template>
