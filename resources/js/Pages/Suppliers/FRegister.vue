<script setup lang="ts">
import { computed, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useRoute } from 'ziggy-js';
import { Eraser, Forward, Loader2 } from 'lucide-vue-next';

import { Button } from '@/Components/ui/button';
import {
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select';
import { Separator } from '@/Components/ui/separator';
import { Switch } from '@/Components/ui/switch';

import type { SupplierI } from '@/Interfaces/SupplierInterface';
import type { PaymentTypeEnumI } from '@/Interfaces/GlobalInterface';
import { getMoney } from '@/Global/Helpers';

const route = useRoute();

const propsW = defineProps<{
  supplierEdit: SupplierI | null;
  update: boolean;
  paymentTypes: PaymentTypeEnumI;
}>();

const emit = defineEmits<{
  (e: 'close'): void;
}>();

const form = useForm({
  uuid: '',
  contact: '',
  company_name: '',
  phone: '',
  email: '',
  type_payment: 'CONTADO',
  receive_email: false,
  is_recurring: false,
  payment_day: null as number | null,
  account_bank: '',
  comment: '',
  amount: 0,
  due_date: 0,
  late_fee: 0,
  consumed: 0,
});

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

const balance = computed(() => {
  return getMoney(form.amount - form.consumed);
});

const getPaymentTypes = computed(() => {
  return Object.entries(propsW.paymentTypes).map(([key, value]) => ({
    label: key,
    value: String(value),
  }));
});

const handlePaymentDayInput = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const numeric = target.value.replace(/\D/g, '');
  form.payment_day = numeric === '' ? null : Number(numeric);
};

const submit = (): void => {
  if (propsW.update) {
    form.patch(route('supplier.update', { supplier: form.uuid }), {
      onSuccess: () => {
        emit('close');
      },
    });
  } else {
    form.post(route('supplier.store'), {
      onSuccess: () => {
        form.reset();
        emit('close');
      },
    });
  }
};
</script>

<template>
  <Card class="w-full max-w-2xl mx-auto border-none shadow-none sm:border sm:shadow-sm">
    <CardHeader class="pb-2">
      <CardTitle class="text-xl sm:text-2xl font-bold text-center text-foreground">
        {{ propsW.update ? 'Actualizar' : 'Crear' }} Proveedor
      </CardTitle>
      <Separator class="mt-3" />
    </CardHeader>

    <CardContent>
      <form @submit.prevent="submit" class="w-full space-y-4">
        <!-- Rejilla de Campos de Texto -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <Label for="company_name">
              Nombre Comercial <span class="text-destructive">*</span>
            </Label>
            <Input
              id="company_name"
              v-model="form.company_name"
              placeholder="Ej. Distribuidora Central"
              required
            />
          </div>

          <div class="space-y-1.5">
            <Label for="contact">Representante / Contacto</Label>
            <Input
              id="contact"
              v-model="form.contact"
              placeholder="Nombre del contacto"
            />
          </div>

          <div class="space-y-1.5">
            <Label for="phone">Teléfono</Label>
            <Input
              id="phone"
              v-model="form.phone"
              placeholder="Ej. 809-555-0199"
            />
          </div>

          <div class="space-y-1.5">
            <Label for="email">Correo Electrónico</Label>
            <Input
              id="email"
              type="email"
              v-model="form.email"
              placeholder="correo@proveedor.com"
            />
          </div>

          <div class="space-y-1.5">
            <Label for="account_bank">Cuenta de Banco</Label>
            <Input
              id="account_bank"
              v-model="form.account_bank"
              placeholder="Número de cuenta"
            />
          </div>

          <div class="space-y-1.5">
            <Label for="payment_day">Día de pago</Label>
            <Input
              id="payment_day"
              type="number"
              min="1"
              max="31"
              :model-value="form.payment_day ?? ''"
              placeholder="1 - 31"
              @input="handlePaymentDayInput"
            />
          </div>

          <div class="space-y-1.5 md:col-span-2">
            <Label for="comment">Comentario</Label>
            <Input
              id="comment"
              v-model="form.comment"
              placeholder="Notas adicionales"
            />
          </div>
        </div>

        <Separator class="my-4" />

        <!-- Opciones y Tipo de Pago -->
        <div
          class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-muted/40 p-3 rounded-lg border"
        >
          <div class="flex flex-wrap items-center gap-6">
            <div class="flex items-center space-x-2">
              <Switch
                id="receive_email"
                :model-value="form.receive_email"
                @update:model-value="(val: boolean) => (form.receive_email = val)"
              />
              <Label for="receive_email" class="cursor-pointer">
                Recibir Correo
              </Label>
            </div>

            <div class="flex items-center space-x-2">
              <Switch
                id="is_recurring"
                :model-value="form.is_recurring"
                @update:model-value="(val: boolean) => (form.is_recurring = val)"
              />
              <Label for="is_recurring" class="cursor-pointer">
                Pago Recurrente
              </Label>
            </div>
          </div>

          <div class="space-y-1.5 w-full sm:w-56">
            <Label for="type_payment">Condición de Pago</Label>
            <Select v-model="form.type_payment">
              <SelectTrigger id="type_payment" class="w-full">
                <SelectValue placeholder="Condición de Pago" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="item in getPaymentTypes"
                  :key="item.value"
                  :value="item.value"
                >
                  {{ item.label }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>
        </div>

        <!-- Botones de Acción -->
        <div class="pt-4 flex flex-col-reverse sm:flex-row justify-end gap-3">
          <Button
            type="reset"
            variant="outline"
            class="w-full sm:w-auto border-amber-500 text-amber-600 hover:bg-amber-50 hover:text-amber-700 dark:hover:bg-amber-950/30"
            @click="form.reset()"
          >
            <Eraser class="w-4 h-4 mr-2" />
            Limpiar
          </Button>

          <Button
            type="submit"
            :disabled="form.processing"
            class="w-full sm:w-auto"
          >
            <Loader2 v-if="form.processing" class="w-4 h-4 mr-2 animate-spin" />
            <Forward v-else class="w-4 h-4 mr-2" />
            {{ propsW.update ? 'Actualizar' : 'Registrar' }}
          </Button>
        </div>
      </form>
    </CardContent>
  </Card>
</template>
