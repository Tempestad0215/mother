<script setup lang="ts">
import { Card, FloatLabel, InputNumber, Button, Toast, useToast } from 'primevue';
import { useForm, usePage } from '@inertiajs/vue3';

const toast = useToast();
const page = usePage();

const propsW = defineProps<{
  cashRegister: any;
  mustCloseOld: boolean;
}>();

const form = useForm({
  opening_balance: 0.0,
});

const submit = () => {
  form.post(route('cash-register.store'), {
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'Apertura de Caja Exitosa',
        life: 3000,
      });
      form.reset();
    },
    onError: (err) => {
      toast.add({
        severity: 'error',
        summary: 'Error Al Aperturar Caja',
        detail: 'Este Registro No Pudo Ser Completado, Detalle :' + Object.values(err)[0],
        life: 5000,
      });
    },
  });
};
</script>

<template>
  <div class="flex justify-center items-center h-screen">
    <Card class="max-w-sm flex-1">
      <template #content>
        <form @submit.prevent="submit">
          <h3 class="text-3xl font-bold text-center">Apertura de Caja</h3>
          <FloatLabel variant="on" class="mt-5">
            <InputNumber
              :minFractionDigits="2"
              :maxFractionDigits="2"
              v-model="form.opening_balance"
              fluid
            />
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
