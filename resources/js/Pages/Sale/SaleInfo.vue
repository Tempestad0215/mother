<script setup lang="ts">
import { computed, inject, ref } from 'vue';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { clientBaseI, ClientRncI } from '@/Interfaces/ClientInterface';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faSpinner } from '@fortawesome/free-solid-svg-icons';
import { saleKey } from '@/utils/keys';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { sequenceDataI } from '@/Interfaces/SettingInterface';
import { useRoute } from 'ziggy-js';
import { Search } from '@lucide/vue';
import {
  AutoComplete,
  AutoCompleteCompleteEvent,
  AutoCompleteOptionSelectEvent,
  Dialog,
  FloatLabel,
  InputGroup,
  InputGroupAddon,
  InputText,
  useToast,
} from 'primevue';
import FShowClient from '@/Pages/Clients/FShowClient.vue';
import { urlRNC } from '@/Global/Helpers';

const route = useRoute();
const page = usePage();
const toast = useToast();

const propsW = defineProps<{
  invoiceType: string;
  clients: PaginationI<clientBaseI>;
}>();

const emit = defineEmits<{
  (e: 'getSequenceType', type: string): void;
}>();

const form = inject(saleKey)!;

const showClient = ref<boolean>(false);
const clientFiltered = ref<Array<clientBaseI>>([]);
const showClientRnc = ref<boolean>(false);
const client = defineModel('client', {
  default: '',
});

const sequenceData = defineModel<sequenceDataI | null>('sequenceData', {
  default: null,
});

const hasRnc = computed(() => {
  return form.invoice_type.toUpperCase() !== 'B02';
});

// watch(
//   () => form.invoice_type,
//   (newVal) => {
//     console.log(newVal);
//   },
//   {
//     deep: true,
//   }
// );

const isRefund = computed((): boolean => {
  return form.type === 'Devolucion';
});

// Conseguir el cliente
const searchClient = async (event: AutoCompleteCompleteEvent) => {
  // Tomar el valor del campo
  const value = event.query as string;

  // Si no tiene mas de in
  if (value.length <= 0) return;

  try {
    const response = await axios.get(route('client.get.json', { search: value }));
    clientFiltered.value = response.data as Array<clientBaseI>;

    if (clientFiltered.value.length <= 0) {
      form.client_name = value;
    }
  } catch (err) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Error al obtener los datos del cliente',
      life: 3000,
    });
  }
};

/**
 * Obtener el cliente seleccionado
 * @param data
 */
const getClient = (data: AutoCompleteOptionSelectEvent) => {
  const item = data.value as clientBaseI;
  //Pasar los datos al formulario
  form.client_name = item.name;
  form.client_uuid = item.uuid;
  form.client_rnc = item.type_rnc;

  console.log(item);
  // Si es diferente a b02, colocar el comprobante
  if (form.invoice_type !== 'B02') {
    form.client_rnc = item.personal_id || '';
    showClientRnc.value = true;
  }
  // Obtener la secuencia del comprobante
  getSequence(item.type_rnc);

  //
  showClient.value = false;
};

const resetData = () => {
  client.value = '';
};

/*
 * Obtener los datos de la sequencia
 */
/**
 * Obtner los comprobantes
 * @param type
 */
async function getSequence(type: string) {
  try {
    //Verificar si existe la secuencia
    if (page.props.setting.sequence) {
      //Realizar la buqued
      const result = await axios.get(route('sequence.get', { type: type }));

      //Verificar si la secuencia es correcta
      if (result.status === 200 && typeof result.data === 'object') {
        //Pasar los datos a las variables
        sequenceData.value = result.data || null;

        emit('getSequenceType', type);
        //Obtner el tipo de secuencia

        //Asegurar de que los datos existan
        if (sequenceData.value && sequenceData.value.type && sequenceData.value.next != null) {
          form.clearErrors('ncf');
          form.ncf = sequenceData.value.type + sequenceData.value.next.toString().padStart(8, '0');
        }
        //Crear la secuencia
      } else {
        //Mensaje de error
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: 'Error al obtener la secuencia',
          life: 3000,
        });
      }
    }
  } catch (err) {
    form.ncf = '';
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Error al obtener la secuencia',
      life: 3000,
    });
  }
}

/*
 * Conseguirel RNC del cliente
 */
const getRncClient = async () => {
  //Verificar tis tiene menos de la longitud deseada
  if (form.client_rnc.length < 7) {
    //Poner el mensaje cuando sea menos de la longitud real
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'El RNC debe tener al menos 7 caracteres',
      life: 3000,
    });
  } else {
    //Obtener el resultado de los
    try {
      const dataClean = form.client_rnc.trim();

      const result = await axios.get(`${urlRNC}${dataClean}`);

      const data = result.data as ClientRncI;

      if (data.status === 'SUSPENDIDO') {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: 'Este Contribuyente Esta Suspendido, Por Favor Elegir Otro',
          life: 3000,
        });
      } else {
        client.value = data.razon_social;
        form.client_name = data.razon_social;
        form.client_social = data.razon_social;
        form.client_rnc_status = data.status;

        toast.add({
          severity: 'success',
          summary: 'Exito',
          detail: 'RNC Cargado Correctamente',
          life: 3000,
        });
      }
    } catch (err) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Error al obtener el RNC',
        life: 3000,
      });
    }
  }
};

defineExpose({
  getSequence,
  resetData,
});
</script>

<template>
  <div class="grid grid-cols-3 gap-2">
    <div class="">
      <!--                                Botones para buscar datos-->
      <div class="space-x-5 items-center w-full">
        <div class="relative">
          <InputGroup>
            <FloatLabel variant="on">
              <AutoComplete
                :disabled="isRefund"
                v-model="client"
                @option-select="getClient"
                :suggestions="clientFiltered"
                optionLabel="name"
                @complete="searchClient"
              />
              <label for="client">Cliente</label>
            </FloatLabel>
            <InputGroupAddon>
              <i
                v-if="propsW.invoiceType !== 'B04'"
                title="Buscar Cliente"
                @click="showClient = !showClient"
                class="icon-efect text-2xl pr-3 fa-solid fa-magnifying-glass-plus"
              ></i>
            </InputGroupAddon>
          </InputGroup>
        </div>
      </div>

      <!--RNC del cliente-->
      <div v-if="hasRnc" class="mt-3">
        <div class="relative">
          <InputGroup>
            <FloatLabel variant="on">
              <InputText v-model="form.client_rnc" class="w-full pr-8" type="search" />
              <label for="client_rnc">RNC</label>
            </FloatLabel>
            <InputGroupAddon @click="getRncClient">
              <Search />
            </InputGroupAddon>
          </InputGroup>
        </div>
      </div>
    </div>

    <!--                            Mensaje cargando-->
    <div v-if="!form.invoice_type" class="grid grid-cols-1 w-full justify-items-center">
      <div class="animate-pulse text-gray-50">
        Cargando... <FontAwesomeIcon class="animate-spin" :icon="faSpinner" />
      </div>
    </div>

    <div v-if="page.props.setting.sequence">
      <p>{{ form.sequence }}</p>
      <p class=""><strong>NCF :</strong> {{ form.ncf }}</p>
      <p v-if="invoiceType === 'B04'" class="truncate">
        <strong>NCF M. :</strong> {{ form.ncf_m }}
      </p>
    </div>

    <!--Numero de comprobantes-->
    <div v-if="hasRnc" class="border rounded-lg p-2">
      <h3 class="font-bold text-center">Datos Tributario</h3>
      <p><strong>RNC :</strong> {{ form.client_rnc }}</p>
      <p class="max-w-75 truncate">
        <strong>Razon Social :</strong>
        {{ form.client_name }}
      </p>
    </div>
  </div>
  <Dialog class="w-250" header="Listado de Cliente" v-model:visible="showClient" modal>
    <FShowClient :other-component="true" :client-data="propsW.clients" />
  </Dialog>
</template>
