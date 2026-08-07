<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { SupplierI } from '@/Interfaces/SupplierInterface';
import { computed, onMounted } from 'vue';
import { getMoney } from '@/Global/Helpers';
import { useRoute } from 'ziggy-js';
import { PaymentTypeEnumI } from '@/Interfaces/GlobalInterface';
import {
  Select,
  Card,
  FloatLabel,
  InputText,
  ToggleSwitch,
  Button,
  useToast,
  InputNumber,
  Divider,
} from 'primevue';
import { Eraser, Forward } from '@lucide/vue';

const route = useRoute();
const toast = useToast();

const propsW = defineProps<{
  supplierEdit: SupplierI | null;
  update: boolean;
  paymentTypes: PaymentTypeEnumI;
}>();

const emit = defineEmits(['close']);

/*
Al momento de cargar
 */
onMounted(() => {
  if (propsW.supplierEdit) {
    form.uuid = propsW.supplierEdit.uuid;
    form.contact = propsW.supplierEdit.contact ?? '';
    form.company_name = propsW.supplierEdit.company_name;
    form.phone = propsW.supplierEdit.phone ?? '';
    form.email = propsW.supplierEdit.email ?? '';
    form.comment = propsW.supplierEdit.comment ?? '';
  }
});

/*
Formulario
 */
const form = useForm({
  uuid: '',
  contact: '',
  company_name: '',
  phone: '',
  email: '',
  type_payment: 'CONTADO',
  receive_email: false,
  is_recurring: false,
  payment_day: null,
  account_bank: '',
  comment: '',
  amount: 0,
  due_date: 0,
  late_fee: 0,
  consumed: 0,
});

/*
Propiedades computadas
 */
const balance = computed(() => {
  return getMoney(form.amount - form.consumed);
});

const getPaymentTypes = computed(() => {
  return Object.entries(propsW.paymentTypes).map(([key, value]) => ({
    label: key,
    value: value,
  }));
});

/**
 * Enviar los datos
 */
const submit = () => {
  if (propsW.update) {
    form.patch(route('supplier.update', { supplier: form.uuid }), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Registro Actualizado',
          detail: 'Proveedor actualizado correctamente.',
          life: 3000,
        });
        emit('close');
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error al actualizar',
          detail: `Detalle: ${Object.values(err)[0]}`,
          life: 5000,
        });
      },
    });
  } else {
    form.post(route('supplier.store'), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Registro Creado',
          detail: 'Proveedor creado correctamente.',
          life: 3000,
        });
        form.reset();
        emit('close');
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error al crear',
          detail: `Detalle: ${Object.values(err)[0]}`,
          life: 5000,
        });
      },
    });
  }
};
</script>

<template>
  <Card class="w-full max-w-2xl mx-auto border-none shadow-none sm:shadow-sm">
    <template #header>
      <h3 class="text-xl sm:text-2xl font-bold text-center text-slate-800">
        {{ propsW.update ? 'Actualizar' : 'Crear' }} Proveedor
      </h3>
      <Divider class="my-3" />
    </template>

    <template #content>
      <form @submit.prevent="submit" class="w-full space-y-4">
        <!-- Rejilla de Campos de Texto -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <FloatLabel variant="on" class="w-full">
            <InputText id="company_name" class="w-full" v-model="form.company_name" required />
            <label for="company_name">Nombre Comercial <span class="text-red-500">*</span></label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full">
            <InputText id="contact" class="w-full" v-model="form.contact" />
            <label for="contact">Representante / Contacto</label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full">
            <InputText id="phone" class="w-full" v-model="form.phone" />
            <label for="phone">Teléfono</label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full">
            <InputText type="email" id="email" class="w-full" v-model="form.email" />
            <label for="email">Correo Electrónico</label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full">
            <InputText id="account_bank" class="w-full" v-model="form.account_bank" />
            <label for="account_bank">Cuenta de Banco</label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full">
            <InputNumber
              id="payment_day"
              class="w-full"
              inputClass="w-full"
              v-model="form.payment_day"
            />
            <label for="payment_day">Día de pago</label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full md:col-span-2">
            <InputText id="comment" class="w-full" v-model="form.comment" />
            <label for="comment">Comentario</label>
          </FloatLabel>
        </div>

        <Divider class="my-4" />

        <!-- Opciones y Tipo de Pago -->
        <div
          class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-slate-50 p-3 rounded-lg border border-slate-200"
        >
          <div class="flex flex-wrap items-center gap-6">
            <div class="flex items-center gap-2">
              <ToggleSwitch inputId="receive_email" v-model="form.receive_email" />
              <label for="receive_email" class="text-sm font-medium text-slate-700 cursor-pointer">
                Recibir Correo
              </label>
            </div>

            <div class="flex items-center gap-2">
              <ToggleSwitch inputId="is_recurring" v-model="form.is_recurring" />
              <label for="is_recurring" class="text-sm font-medium text-slate-700 cursor-pointer">
                Pago Recurrente
              </label>
            </div>
          </div>

          <FloatLabel variant="on" class="w-full sm:w-56">
            <Select
              id="type_payment"
              class="w-full"
              v-model="form.type_payment"
              option-label="label"
              option-value="value"
              :options="getPaymentTypes"
            />
            <label for="type_payment">Condición de Pago</label>
          </FloatLabel>
        </div>

        <!-- Botones de Acción -->
        <div class="pt-4 flex flex-col-reverse sm:flex-row justify-end gap-3">
          <Button
            @click="form.reset()"
            type="reset"
            severity="warn"
            label="Limpiar"
            class="w-full sm:w-auto h-10"
            outlined
          >
            <template #icon>
              <Eraser class="w-4 h-4 mr-1" />
            </template>
          </Button>

          <Button
            type="submit"
            :label="propsW.update ? 'Actualizar' : 'Registrar'"
            :loading="form.processing"
            class="w-full sm:w-auto h-10"
          >
            <template #icon>
              <Forward class="w-4 h-4 mr-1" />
            </template>
          </Button>
        </div>
      </form>
    </template>
  </Card>
</template>
