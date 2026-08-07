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
import { Search, UserPlus } from '@lucide/vue';
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

const isRefund = computed((): boolean => {
  return form.type === 'Devolucion';
});

// Conseguir el cliente
const searchClient = async (event: AutoCompleteCompleteEvent) => {
  const value = event.query as string;
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
 */
const getClient = (data: AutoCompleteOptionSelectEvent) => {
  const item = data.value as clientBaseI;
  form.client_name = item.name;
  form.client_uuid = item.uuid;
  form.client_rnc = item.type_rnc;

  if (form.invoice_type !== 'B02') {
    form.client_rnc = item.personal_id || '';
    showClientRnc.value = true;
  }

  getSequence(item.type_rnc);
  showClient.value = false;
};

const resetData = () => {
  client.value = '';
};

/**
 * Obtener los comprobantes / secuencia
 */
async function getSequence(type: string) {
  try {
    if (page.props.setting.sequence) {
      const result = await axios.get(route('sequence.get', { type: type }));

      if (result.status === 200 && typeof result.data === 'object') {
        sequenceData.value = result.data || null;

        emit('getSequenceType', type);

        if (sequenceData.value && sequenceData.value.type && sequenceData.value.next != null) {
          form.clearErrors('ncf');
          form.ncf = sequenceData.value.type + sequenceData.value.next.toString().padStart(8, '0');
        }
      } else {
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

/**
 * Conseguir el RNC del cliente
 */
const getRncClient = async () => {
  if (form.client_rnc.length < 7) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'El RNC debe tener al menos 7 caracteres',
      life: 3000,
    });
  } else {
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
          summary: 'Éxito',
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
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-start">
    <!-- Columna 1: Búsqueda de Cliente y RNC -->
    <div class="space-y-3 w-full">
      <div class="w-full">
        <InputGroup class="w-full">
          <FloatLabel variant="on" class="w-full">
            <AutoComplete
              :disabled="isRefund"
              v-model="client"
              @option-select="getClient"
              :suggestions="clientFiltered"
              optionLabel="name"
              @complete="searchClient"
              fluid
              class="w-full"
            />
            <label for="client">Cliente</label>
          </FloatLabel>
          <InputGroupAddon
            v-if="propsW.invoiceType !== 'B04'"
            title="Buscar Cliente"
            @click="showClient = !showClient"
            class="cursor-pointer hover:bg-slate-100 transition px-3"
          >
            <UserPlus class="w-5 h-5 text-slate-700" />
          </InputGroupAddon>
        </InputGroup>
      </div>

      <!-- RNC del cliente -->
      <div v-if="hasRnc" class="w-full">
        <InputGroup class="w-full">
          <FloatLabel variant="on" class="w-full">
            <InputText v-model="form.client_rnc" class="w-full" type="search" />
            <label for="client_rnc">RNC / Cédula</label>
          </FloatLabel>
          <InputGroupAddon
            @click="getRncClient"
            class="cursor-pointer hover:bg-slate-100 transition px-3"
          >
            <Search class="w-5 h-5 text-slate-700" />
          </InputGroupAddon>
        </InputGroup>
      </div>
    </div>

    <!-- Columna 2: Estado de Carga y Comprobante Fiscal NCF -->
    <div
      class="flex flex-col justify-center space-y-1 bg-slate-50 p-3 rounded-lg border border-slate-200 text-sm"
    >
      <div
        v-if="!form.invoice_type"
        class="flex items-center justify-center gap-2 py-2 text-slate-500 animate-pulse"
      >
        <span>Cargando...</span>
        <FontAwesomeIcon class="animate-spin" :icon="faSpinner" />
      </div>

      <div v-if="page.props.setting.sequence" class="space-y-1">
        <p v-if="form.sequence" class="font-medium text-slate-700">{{ form.sequence }}</p>
        <p class="text-slate-800">
          <strong class="text-slate-900">NCF:</strong>
          <span class="font-mono font-bold ml-1 text-emerald-700">{{ form.ncf || 'Sin NCF' }}</span>
        </p>
        <p v-if="invoiceType === 'B04'" class="truncate text-slate-800">
          <strong class="text-slate-900">NCF Modificado:</strong>
          <span class="font-mono ml-1">{{ form.ncf_m || 'N/A' }}</span>
        </p>
      </div>
    </div>

    <!-- Columna 3: Datos Tributarios del Cliente -->
    <div v-if="hasRnc" class="bg-slate-50 p-3 rounded-lg border border-slate-200 text-sm space-y-1">
      <h3 class="font-bold text-slate-800 border-b border-slate-200 pb-1 mb-1">
        Datos Tributarios
      </h3>
      <p class="text-slate-700">
        <strong class="text-slate-900">RNC/Cédula:</strong> {{ form.client_rnc || 'N/A' }}
      </p>
      <p class="text-slate-700 truncate" :title="form.client_name">
        <strong class="text-slate-900">Razón Social:</strong> {{ form.client_name || 'N/A' }}
      </p>
    </div>
  </div>

  <!-- Diálogo Modal de Selección de Cliente -->
  <Dialog
    v-model:visible="showClient"
    modal
    dismissableMask
    header="Listado de Clientes"
    :breakpoints="{ '960px': '85vw', '641px': '95vw' }"
    :style="{ width: '60vw' }"
    class="p-dialog-responsive mx-2 sm:mx-0"
  >
    <div class="py-2">
      <FShowClient :other-component="true" :client-data="propsW.clients" />
    </div>
  </Dialog>
</template>

<style scoped>
:deep(.p-dialog-content) {
  padding: 1rem;
  max-height: 80vh;
  overflow-y: auto;
}

@media (max-width: 640px) {
  :deep(.p-dialog-content) {
    padding: 0.75rem;
    max-height: 85vh;
  }
}
</style>
