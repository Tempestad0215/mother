<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { useRoute } from 'ziggy-js';
import axios from 'axios';
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

import type { clientBaseI, ClientRncI } from '@/Interfaces/ClientInterface';
import type { EnumValueI } from '@/Interfaces/GeneralInterface';
import { urlRNC } from '@/Global/Helpers';

const route = useRoute();

const rncInvalid = ref(false);

const propsW = defineProps<{
  clientEdit?: clientBaseI | null;
  update: boolean;
  typeRNC: string[];
  clientType: EnumValueI[];
  clientPrice: EnumValueI[];
  clientDocument: EnumValueI[];
}>();

const emit = defineEmits<{
  (e: 'close'): void;
  (e: 'rnc-found', data: ClientRncI): void;
}>();

const form = useForm({
  uuid: '',
  type_rnc: 'B02',
  name: '',
  personal_id: '',
  phone: '',
  email: '',
  address: '',
  type: propsW.clientEdit ? propsW.clientEdit.type : 'CONTADO',
  document: propsW.clientEdit ? propsW.clientEdit.document : 'cedula',
  amount: 0,
  due_date: 0,
  balance: 0,
  consumed: 0,
  late_fee: 0,
  status: true,
  receive_email: false,
  type_price: '1',
  comment: '',
  image: '',
});

onMounted(() => {
  if (propsW.clientEdit) {
    form.uuid = propsW.clientEdit.uuid;
    form.name = propsW.clientEdit.name;
    form.document = propsW.clientEdit.document;
    form.personal_id = propsW.clientEdit.personal_id ?? '';
    form.phone = propsW.clientEdit.phone ?? '';
    form.email = propsW.clientEdit.email ?? '';
    form.address = propsW.clientEdit.address ?? '';
    form.comment = propsW.clientEdit.comment ?? '';
    form.status = propsW.clientEdit.status;
    form.type = propsW.clientEdit.type;
    form.type_price = String(propsW.clientEdit.type_price ?? 1);
  }
});

const placeholders = reactive<Record<string, string>>({
  cedula: '000-0000000-0',
  pasaporte: 'A00000000',
  rnc: '000-00000-0',
});

const selectedPlaceholder = computed(() => {
  return placeholders[form.document] || 'Ingrese número de documento';
});

const formatDocumentInput = (event: Event) => {
  const input = event.target as HTMLInputElement;
  const raw = input.value;

  if (form.document === 'cedula') {
    const digits = raw.replace(/\D/g, '').slice(0, 11);
    if (digits.length > 10) {
      form.personal_id = `${digits.slice(0, 3)}-${digits.slice(3, 10)}-${digits.slice(10)}`;
    } else if (digits.length > 3) {
      form.personal_id = `${digits.slice(0, 3)}-${digits.slice(3)}`;
    } else {
      form.personal_id = digits;
    }
  } else if (form.document === 'rnc') {
    const digits = raw.replace(/\D/g, '').slice(0, 9);
    if (digits.length > 3) {
      form.personal_id = `${digits.slice(0, 3)}-${digits.slice(3)}`;
    } else {
      form.personal_id = digits;
    }
  } else if (form.document === 'pasaporte') {
    form.personal_id = raw.replace(/[^a-zA-Z0-9]/g, '').toUpperCase().slice(0, 9);
  } else {
    form.personal_id = raw;
  }
};

const cleanDocumentValue = (val: string) => val.replace(/-/g, '').trim();

const submit = (): void => {
  const payload = {
    ...form.data(),
    personal_id: cleanDocumentValue(form.personal_id),
    type_price: Number(form.type_price),
  };

  if (propsW.update) {
    form
      .transform(() => payload)
      .patch(route('client.update', form.uuid), {
        onSuccess: () => {
          emit('close');
        },
      });
  } else {
    form
      .transform(() => payload)
      .post(route('client.store'), {
        onSuccess: () => {
          form.reset();
          emit('close');
        },
      });
  }
};

const searchRNC = async () => {
  const cleanId = cleanDocumentValue(form.personal_id);

  if (cleanId.length > 7) {
    try {
      const res = await axios.get(`${urlRNC}${cleanId}`);
      const data = res.data as ClientRncI;

      if (data.status === 'SUSPENDIDO') {
        rncInvalid.value = true;
        return;
      }

      rncInvalid.value = false;
      form.name = data.razon_social;
      emit('rnc-found', data);
    } catch {
      rncInvalid.value = true;
    }
  }
};

const handleReset = () => {
  rncInvalid.value = false;
  form.reset();
};
</script>

<template>
  <Card class="w-full max-w-4xl mx-auto border-none shadow-none sm:border sm:shadow-sm">
    <CardHeader class="pb-2">
      <CardTitle class="text-xl sm:text-2xl font-bold text-center text-foreground">
        {{ propsW.update ? 'Actualizar' : 'Crear' }} Cliente
      </CardTitle>
      <Separator class="mt-3" />
    </CardHeader>

    <CardContent>
      <form @submit.prevent="submit" @reset.prevent="handleReset" class="space-y-5">
        <!-- Controles Superiores: Selects Adaptativos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div class="space-y-1.5">
            <Label for="type">Tipo Cliente</Label>
            <Select v-model="form.type">
              <SelectTrigger id="type" class="w-full">
                <SelectValue placeholder="Tipo de Cliente" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="item in propsW.clientType"
                  :key="String(item.value)"
                  :value="String(item.value)"
                >
                  {{ item.label }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div class="space-y-1.5">
            <Label for="type_price">Precio</Label>
            <Select v-model="form.type_price">
              <SelectTrigger id="type_price" class="w-full">
                <SelectValue placeholder="Precio de Ventas" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="item in propsW.clientPrice"
                  :key="String(item.value)"
                  :value="String(item.value)"
                >
                  {{ item.label }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <div class="space-y-1.5">
            <Label for="document">Documento</Label>
            <Select v-model="form.document">
              <SelectTrigger id="document" class="w-full">
                <SelectValue placeholder="Documento" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="item in propsW.clientDocument"
                  :key="String(item.value)"
                  :value="String(item.value)"
                >
                  {{ item.label }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>
        </div>

        <!-- Switches de estado y email -->
        <div class="flex flex-wrap items-center justify-start sm:justify-end gap-6 pt-1 px-1">
          <div class="flex items-center space-x-2">
            <Switch
              id="receive_email"
              :model-value="form.receive_email"
              @update:model-value="(val: boolean) => (form.receive_email = val)"
            />
            <Label for="receive_email" class="cursor-pointer">
              Recibir Email
            </Label>
          </div>

          <div class="flex items-center space-x-2">
            <Switch
              id="status"
              :model-value="form.status"
              @update:model-value="(val: boolean) => (form.status = val)"
            />
            <Label for="status" class="cursor-pointer">
              Estado Activo
            </Label>
          </div>
        </div>

        <Separator />

        <!-- Campos de Texto -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="space-y-1.5">
            <Label for="name">
              Nombre Completo <span class="text-destructive">*</span>
            </Label>
            <Input
              id="name"
              v-model="form.name"
              placeholder="Ej. Juan Pérez"
              required
            />
          </div>

          <div class="space-y-1.5">
            <Label for="personal_id">Identificación / RNC</Label>
            <Input
              id="personal_id"
              :model-value="form.personal_id"
              :placeholder="selectedPlaceholder"
              :class="{
                'border-destructive focus-visible:ring-destructive': rncInvalid,
              }"
              @input="formatDocumentInput"
              @blur="searchRNC"
            />
          </div>

          <div class="space-y-1.5">
            <Label for="phone">Teléfono</Label>
            <Input
              id="phone"
              v-model="form.phone"
              placeholder="Ej. 809-555-0101"
            />
          </div>

          <div class="space-y-1.5">
            <Label for="email">Correo Electrónico</Label>
            <Input
              id="email"
              type="email"
              v-model="form.email"
              placeholder="correo@ejemplo.com"
            />
          </div>

          <div class="space-y-1.5 md:col-span-2">
            <Label for="address">Dirección</Label>
            <Input
              id="address"
              v-model="form.address"
              placeholder="Calle, sector, ciudad..."
            />
          </div>

          <div class="space-y-1.5 md:col-span-2">
            <Label for="comment">Comentario</Label>
            <Input
              id="comment"
              v-model="form.comment"
              placeholder="Notas adicionales sobre el cliente"
            />
          </div>
        </div>

        <!-- Botones de Acción -->
        <div class="mt-6 flex flex-col-reverse sm:flex-row justify-end gap-3 pt-2">
          <Button
            type="reset"
            variant="outline"
            class="w-full sm:w-auto border-amber-500 text-amber-600 hover:bg-amber-50 hover:text-amber-700 dark:hover:bg-amber-950/30"
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
