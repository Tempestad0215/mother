<script setup lang="ts">
import { inject, ref } from 'vue';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { clientBaseI } from '@/Interfaces/ClientInterface';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faSpinner } from '@fortawesome/free-solid-svg-icons';
import { usePage } from '@inertiajs/vue3';
import { Search, UserPlus } from '@lucide/vue';
import { AutoComplete, Dialog, FloatLabel, InputGroup, InputGroupAddon, InputText } from 'primevue';
import FShowClient from '@/Pages/Clients/FShowClient.vue';
import { useSaleInfo } from '@/Composable/Sales/useSaleInfo';
import { saleKey } from '@/Injections/saleKeys';

const page = usePage();

const propsW = defineProps<{
  clients: PaginationI<clientBaseI>;
}>();

const form = inject(saleKey)!;

const { searchClient, getClient, clientName, getClientRnc, hasRnc, isRefund, clientFiltered } =
  useSaleInfo();

const showClient = ref<boolean>(false);
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
              v-model="clientName"
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
            v-if="form.sequence_type !== 'B04'"
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
            @click="getClientRnc"
            class="cursor-pointer hover:bg-slate-100 transition px-3"
          >
            <Search class="w-5 h-5 text-slate-700" />
          </InputGroupAddon>
        </InputGroup>
      </div>
    </div>

    <!-- Columna 2: Estado de Carga y Comprobante Fiscal NCF -->
    <div
      v-if="!form.invoice_type"
      class="flex flex-col justify-center space-y-1 bg-slate-50 p-3 rounded-lg border border-slate-200 text-sm"
    >
      <div class="flex items-center justify-center gap-2 py-2 text-slate-500 animate-pulse">
        <span>Cargando...</span>
        <FontAwesomeIcon class="animate-spin" :icon="faSpinner" />
      </div>

      <div v-if="page.props.setting.sequence" class="space-y-1">
        <p v-if="form.sequence" class="font-medium text-slate-700">{{ form.sequence }}</p>
        <p class="text-slate-800">
          <strong class="text-slate-900">NCF:</strong>
          <span class="font-mono font-bold ml-1 text-emerald-700">{{ form.ncf || 'Sin NCF' }}</span>
        </p>
        <p v-if="form.sequence_type === 'B04'" class="truncate text-slate-800">
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
