<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import { sequenceDataI } from '@/Interfaces/SettingInterface';
import { onMounted, reactive } from 'vue';
import { useRoute } from 'ziggy-js';
import {
  Breadcrumb,
  Button,
  Card,
  Column,
  DataTable,
  DatePicker,
  FloatLabel,
  InputMask,
  InputNumber,
  Select,
  useConfirm,
  useToast,
} from 'primevue';
import { itemsSettings } from '@/Helpers/SettingHelpers';

const route = useRoute();
const toast = useToast();
const confirm = useConfirm();
/*
Propiedades
 */
const propsW = defineProps<{
  sequenceType: string[];
  sequencesData: sequenceDataI[];
  sequenceEdit?: sequenceDataI;
}>();

const state = reactive({
  first_error: '',
});

/*
Al momentod de cargar
 */
onMounted(() => {
  //Verificar si existe la secuencia para editar
  if (propsW.sequenceEdit) {
    form.uuid = propsW.sequenceEdit.uuid;
    form.code = propsW.sequenceEdit.code;
    form.type = propsW.sequenceEdit.type;
    form.from = propsW.sequenceEdit.from;
    form.to = propsW.sequenceEdit.to;
    form.next = propsW.sequenceEdit.next;
    form.advise = propsW.sequenceEdit.advise;
    form.num_request = propsW.sequenceEdit.num_request;
    form.num_authorization = propsW.sequenceEdit.num_authorization;
    form.date_request = propsW.sequenceEdit.date_request
      ? new Date(propsW.sequenceEdit.date_request)
      : new Date();
    form.date_expire = propsW.sequenceEdit.date_expire
      ? new Date(propsW.sequenceEdit.date_expire)
      : new Date();
  }
});

/*
Formulario
 */
const form = useForm({
  uuid: '',
  code: '',
  type: 'B01',
  from: 1,
  next: 0,
  to: 0,
  advise: 0,
  num_request: '',
  num_authorization: '',
  date_request: null as Date | null,
  date_expire: null as Date | null,
  status: true,
  general: '',
});

/**
 * Enviar los datos
 */
const submit = (): void => {
  form.post(route('sequence.store'), {
    onSuccess: () => {
      //Mensjae de exito
      // successHttp('Registro Guiardado Correctamente');
      toast.add({
        severity: 'success',
        summary: 'Registro Exitoso',
        life: 3500,
      });
      //Limpiar el formulario
      form.reset();
      state.first_error = '';
    },
    onFinish: () => {
      Object.entries(form.errors).forEach(([key, value]) => {
        if (!state.first_error) {
          state.first_error = `El Campo ${key}, Mensaje: ${value}`;
        }
      });
    },
  });
};

/**
 * Editar las secuncia
 */
const edit = (uuid: string): void => {
  router.get(route('sequence.edit', { sequence: uuid }));
};

/**
 * Eliminar la secuencia
 */
const destroy = (uuid: string): void => {
  confirm.require({
    message: 'Desea Eliminar este registro, los cambios son irreversibles?',
    header: 'Confirmation',
    icon: 'pi pi-exclamation-triangle',
    rejectProps: {
      label: 'Cancelar',
      severity: 'secondary',
      outlined: true,
    },
    acceptProps: {
      label: 'Eliminar',
    },
    accept: () => {
      router.delete(route('sequence.destroy', { sequence: uuid }), {
        onSuccess: () => {
          toast.add({
            severity: 'success',
            summary: 'Eliminado ',
            detail: 'El Registro Eliminado Correctamente.',
            life: 3000,
          });
        },
        onError: () => {
          toast.add({
            severity: 'error',
            summary: 'Ha Surgido un Error',
            detail: 'No se pudo eliminar el registro.',
          });
        },
      });
    },
  });
};
</script>

<template>
  <!--  Contenido general-->
  <AppLayout>
    <Card>
      <template #title>
        <div>
          <Breadcrumb :model="itemsSettings" />
        </div>
      </template>
      <template #content>
        <div class="fondo p-5 rounded-md max-w-295 mx-auto grid grid-cols-3 gap-3">
          <div class="col-span-2">
            <DataTable :value="propsW.sequencesData">
              <Column header="CODIGO" field="code" />
              <Column header="TIPO" field="type" />
              <Column header="INICIO" field="from" />
              <Column header="FINAL" field="to" />
              <Column header="SIG." field="next" />
              <Column header="NOT." field="advise" />
              <Column header="ACT">
                <template #body="{ data }: { data: sequenceDataI }">
                  <i @click="edit(data.uuid)" class="icon-efect fa-solid fa-pen-to-square"></i>
                  <i @click="destroy(data.uuid)" class="ml-3 icon-efect fa-solid fa-trash"></i>
                </template>
              </Column>
            </DataTable>
            <!--            Tabla de las secuencias registrada-->
          </div>

          <form @submit.prevent="submit" class="space-y-3">
            <h3 class="text-2xl font-bold text-center">Registro de Secuencia</h3>
            <!--                    Tipo de sequencia-->
            <div>
              <FloatLabel variant="on">
                <Select
                  fluid
                  placeholder="Seleccione"
                  v-model="form.type"
                  :options="propsW.sequenceType"
                />
                <label for="sequence_type">Tipo de Secuencia</label>
              </FloatLabel>
            </div>

            <!-- From-->
            <div>
              <FloatLabel variant="on">
                <InputNumber fluid v-model="form.from" />
                <label for="from">Inicio</label>
              </FloatLabel>
            </div>

            <!-- From-->
            <div>
              <FloatLabel variant="on">
                <InputNumber fluid v-model="form.to" />
                <label for="to">Final</label>
              </FloatLabel>
            </div>

            <!-- From-->
            <div>
              <FloatLabel variant="on">
                <InputNumber :min="form.from + 1" fluid v-model="form.advise" />
                <label for="advise">Avisar Faltando</label>
              </FloatLabel>
            </div>

            <!-- From-->
            <div>
              <FloatLabel variant="on">
                <InputMask mask="9999?99999999" fluid v-model="form.num_request" />
                <label for="requirest">N. Solicitud</label>
              </FloatLabel>
            </div>

            <!-- From-->
            <div>
              <FloatLabel variant="on">
                <InputMask mask="9999?99999999" fluid v-model="form.num_authorization" />
                <label for="authorizacion">N. Aprobacion</label>
              </FloatLabel>
            </div>
            <!-- From-->
            <div>
              <FloatLabel variant="on">
                <DatePicker fluid v-model="form.date_expire" />
                <label for="date_expire">Fecha Vencimiento</label>
              </FloatLabel>
            </div>
            <!-- From-->
            <div>
              <FloatLabel variant="on">
                <DatePicker fluid v-model="form.date_request" />
                <label for="date_authorization">Fecha de Aprobacion</label>
              </FloatLabel>
            </div>

            <!--                Botones para enviar-->
            <div class="mt-5 text-right space-x-3">
              <Button :disabled="form.processing" type="reset" severity="warn" label="Limpiar" />
              <Button :disabled="form.processing" type="submit" label="Registrar" />
            </div>
          </form>
        </div>
      </template>
    </Card>
    <!--        Conteneido de la ventana-->
  </AppLayout>
</template>
