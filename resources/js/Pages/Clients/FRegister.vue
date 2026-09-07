<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue';
import { clientBaseI, ClientRncI } from '@/Interfaces/ClientInterface';
import { useForm } from '@inertiajs/vue3';
import { useRoute } from 'ziggy-js';
import {
  Button,
  Card,
  Divider,
  FloatLabel,
  InputMask,
  InputText,
  Select,
  ToggleSwitch,
  useConfirm,
  useToast,
} from 'primevue';
import { Eraser, Forward } from '@lucide/vue';
import axios from 'axios';
import { urlRNC } from '@/Global/Helpers';
import { EnumValueI } from '@/Interfaces/GeneralInterface';

const route = useRoute();
const toast = useToast();
const confirm = useConfirm();

const rncInvalid = ref(false);

const propsW = defineProps<{
  clientEdit?: clientBaseI | null;
  update: boolean;
  typeRNC: string[];
  clientType: EnumValueI[];
  clientPrice: EnumValueI[];
  clientDocument: EnumValueI[];
}>();

const emit = defineEmits(['close']);

onMounted(() => {
  if (propsW.clientEdit) {
    form.uuid = propsW.clientEdit.uuid;
    form.name = propsW.clientEdit.name;
    form.document = propsW.clientEdit.document;
    form.personal_id = propsW.clientEdit.personal_id ? propsW.clientEdit.personal_id : '';
    form.phone = propsW.clientEdit.phone ? propsW.clientEdit.phone : '';
    form.email = propsW.clientEdit.email ? propsW.clientEdit.email : '';
    form.address = propsW.clientEdit.address ? propsW.clientEdit.address : '';
    form.comment = propsW.clientEdit?.comment || '';
    form.status = propsW.clientEdit.status;
    form.type = propsW.clientEdit.type;
    form.type_price = propsW.clientEdit.type_price;
  }
});

const masks = reactive<Record<string, string>>({
  cedula: '999-9999999-9',
  pasaporte: 'A99999999',
  rnc: '999-999999',
});

const selectedMask = computed(() => {
  return masks[form.document] || '';
});

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
  type_price: 1,
  comment: '',
  image: '',
});

const submit = (): void => {
  if (propsW.update) {
    form.patch(route('client.update', form.uuid), {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Actualizado',
          detail: 'Registro Actualizado Correctamente',
          life: 3000,
        });
        emit('close');
      },
      onError: (er) => {
        console.log(er);
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error Al Intentar Actualizar Los Datos, Detalle ${Object.values(er)[0]}`,
          life: 5000,
        });
      },
    });
  } else {
    form.post(route('client.store'), {
      onSuccess: () => {
        form.reset();
        toast.add({
          severity: 'success',
          summary: 'Registro Creado',
          detail: 'Registro Creado Correctamente',
          life: 3000,
        });
        emit('close');
      },
      onError: (er) => {
        console.log(er);
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error, Detalle ${Object.values(er)[0]}`,
          life: 5000,
        });
      },
    });
  }
};

const searchRNC = async () => {
  if (form.personal_id.length > 7) {
    try {
      const res = await axios.get(`${urlRNC}${form.personal_id}`);
      const data = res.data as ClientRncI;

      if (data.status === 'SUSPENDIDO') {
        rncInvalid.value = true;
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: 'El RNC es inactivo, No Puede Ser Usado.',
          life: 5000,
        });
      } else {
        confirm.require({
          message: 'Este Cliente Tiene RNC Disponible, Desea Continuar?',
          header: 'Confirmación',
          icon: 'pi pi-exclamation-triangle',
          rejectProps: {
            label: 'Cancelar',
            severity: 'secondary',
            outlined: true,
          },
          acceptProps: {
            label: 'Utilizar',
          },
          accept: () => {
            rncInvalid.value = false;
            form.name = data.razon_social;
          },
        });
      }
    } catch (e) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Ha Ocurrido Un Error Al Buscar El RNC, Por Favor Intente Nuevamente.',
        life: 5000,
      });
    }
  }
};
</script>

<template>
  <Card class="w-full max-w-4xl mx-auto border-none shadow-none sm:shadow-sm">
    <template #header>
      <h3 class="text-xl sm:text-2xl font-bold text-center text-slate-800">
        {{ propsW.update ? 'Actualizar' : 'Crear' }} Cliente
      </h3>
      <Divider class="my-3" />
    </template>

    <template #content>
      <form @submit.prevent="submit" class="space-y-4">
        <!-- Controles Superiores: Selects y Switches Adaptativos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 items-center">
          <FloatLabel variant="on" class="w-full">
            <Select
              id="type"
              class="w-full"
              placeholder="Tipo de Cliente"
              v-model="form.type"
              :options="propsW.clientType"
              option-label="label"
              option-value="value"
            />
            <label for="type">Tipo Cliente</label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full">
            <Select
              id="type_price"
              class="w-full"
              placeholder="Precio de Ventas"
              v-model="form.type_price"
              :options="propsW.clientPrice"
              option-label="label"
              option-value="value"
            />
            <label for="type_price">Precio</label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full">
            <Select
              id="document"
              class="w-full"
              placeholder="Documento"
              v-model="form.document"
              :options="propsW.clientDocument"
              option-label="label"
              option-value="value"
            />
            <label for="document">Documento</label>
          </FloatLabel>
        </div>

        <!-- Switches de estado y email -->
        <div class="flex flex-wrap items-center justify-start sm:justify-end gap-6 pt-2 px-1">
          <div class="flex items-center gap-2">
            <ToggleSwitch inputId="receive_email" v-model="form.receive_email" />
            <label for="receive_email" class="text-sm font-medium text-slate-700 cursor-pointer">
              Recibir Email
            </label>
          </div>
          <div class="flex items-center gap-2">
            <ToggleSwitch inputId="status" v-model="form.status" />
            <label for="status" class="text-sm font-medium text-slate-700 cursor-pointer">
              Estado Activo
            </label>
          </div>
        </div>

        <Divider class="my-4" />

        <!-- Campos de Texto: 1 columna en móvil / 2 columnas en pantallas medianas -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <FloatLabel variant="on" class="w-full">
            <InputText id="name" class="w-full" v-model="form.name" required />
            <label for="name">Nombre Completo <span class="text-red-500">*</span></label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full">
            <InputMask
              @blur="searchRNC"
              :invalid="rncInvalid"
              class="w-full"
              unmask
              id="personal_id"
              v-model="form.personal_id"
              :mask="selectedMask"
            />
            <label for="personal_id">Identificación / RNC</label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full">
            <InputText id="phone" class="w-full" v-model="form.phone" />
            <label for="phone">Teléfono</label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full">
            <InputText type="email" id="email" class="w-full" v-model="form.email" />
            <label for="email">Correo Electrónico</label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full md:col-span-2">
            <InputText id="address" class="w-full" v-model="form.address" />
            <label for="address">Dirección</label>
          </FloatLabel>

          <FloatLabel variant="on" class="w-full md:col-span-2">
            <InputText id="comment" class="w-full" v-model="form.comment" />
            <label for="comment">Comentario</label>
          </FloatLabel>
        </div>

        <!-- Botones de Acción -->
        <div class="mt-6 flex flex-col-reverse sm:flex-row justify-end gap-3 pt-2">
          <Button severity="warn" type="reset" label="Limpiar" class="w-full sm:w-auto" outlined>
            <template #icon>
              <Eraser class="w-4 h-4 mr-1" />
            </template>
          </Button>

          <Button
            :label="propsW.update ? 'Actualizar' : 'Registrar'"
            type="submit"
            :loading="form.processing"
            class="w-full sm:w-auto"
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
