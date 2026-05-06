<script setup lang="ts">
import TextInput from '@components/TextInput.vue';
import { computed, inject, ref } from 'vue';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { clientBaseI } from '@/Interfaces/ClientInterface';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faMagnifyingGlass, faSpinner } from '@fortawesome/free-solid-svg-icons';
import { saleKey } from '@/utils/keys';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { sequenceDataI } from '@/Interfaces/SettingInterface';
import { useRoute } from 'ziggy-js';
import { FloatLabel, InputText, AutoComplete, InputGroup, InputGroupAddon, Dialog } from 'primevue';
import FShowClient from '@/Pages/Clients/FShowClient.vue';

const route = useRoute();
const page = usePage();

const propsW = defineProps<{
  invoiceType: string;
  clients: PaginationI<clientBaseI>;
}>();

const emit = defineEmits<{
  (e: 'getSequenceType', type: string): void;
}>();

const form = inject(saleKey)!;

const showClient = ref<boolean>(false);
// const showClientRnc = ref<boolean>(false)

const sequenceData = defineModel<sequenceDataI | null>('sequenceData', {
  default: null,
});

const hasRnc = computed(() => {
  return form.invoice_type.trim().toUpperCase() !== 'B02';
});

// function getClient(item:clientBaseI){
// 	//Pasar los datos al formulario
// 	form.client_name = item.name;
// 	form.client_id = item.id;
// 	form.client_rnc = item.type_rnc;
//
//
// 	// Si es diferente a b02, colocar el comprobante
// 	if (form.invoice_type !== "B02")
// 	{
// 		form.client_rnc = item.personal_id || "";
// 		showClientRnc.value = true;
// 	}
// 	// Obtener la secuencia del comprobante
// 	getSequence(item.type_rnc);
//
// 	//
// 	showClient.value = false;
//
// }

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
        form.setError('sequence', 'Este Comprobante No Puedo Ser');
      }
    }
  } catch (err) {
    form.ncf = '';
    form.setError('ncf', 'No Existe NCF Disponible, Para Esta Serie');
  }
}

/*
 * Conseguirel RNC del cliente
 */
async function getRncClient() {
  //Verificar tis tiene menos de la longitud deseada
  if (form.client_rnc.length < 7) {
    //Poner el mensaje cuando sea menos de la longitud real
    form.setError('client_rnc', 'El RNC debes contener al menos 8 caracter');
  } else {
    //Obtener el resultado de los
    // const result = await getRncHelper(form.client_rnc);
    //
    // //Verificar el estado del RNC
    // if (result === "SUSPENDIDO")
    // {
    // 	form.setError("client_rnc", "Este Contribuyente Esta Suspendido, Por Favor Elegir Otro");
    //
    // }else if (result === "ERROR")
    // {
    // 	form.setError("client_rnc", "Este Contribuyente No Pudo Ser Encontrado")
    //
    // }else if (result === "CANCELLED")
    // {
    // 	form.setError("client_rnc", "Este Contribuyente Esta Cancelado");
    // }else{
    // 	//Formatear el json
    // 	const info:rncClientI = result;
    //
    // 	//Poner cada dato en su lugar
    // 	form.client_name = info.razon_social;
    // 	form.client_rnc_status = info.status;
    //
    // 	// Limpiar el formulario
    // 	form.clearErrors()
    // }
  }
}

defineExpose({
  getSequence,
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
              <AutoComplete />
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
      <div v-if="hasRnc">
        <FloatLabel variant="on">
          <InputText />
          <label for="rnc">RNC</label>
        </FloatLabel>
        <div class="relative">
          <InputGroup>
            <FloatLabel variant="on">
              <TextInput v-model="form.client_rnc" class="w-full pr-8" type="search" />
              <label for="client_rnc">RNC</label>
            </FloatLabel>
            <InputGroupAddon>
              <FontAwesomeIcon
                @click="getRncClient"
                class="absolute flex items-end p-2 top-0 right-0"
                :icon="faMagnifyingGlass"
              />
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

    <fieldset v-else class="field block rounded-md">
      <legend>
        {{ form.sequence_type }}
      </legend>
      <p class=""><strong>NCF :</strong> {{ form.ncf }}</p>
      <p v-if="invoiceType === 'B04'" class="truncate">
        <strong>NCF M. :</strong> {{ form.ncf_m }}
      </p>
    </fieldset>

    <!--Numero de comprobantes-->
    <fieldset v-if="hasRnc" class="field block rounded-md">
      <legend>Datos Tributario</legend>
      <p><strong>RNC :</strong> {{ form.client_rnc }}</p>
      <p class="max-w-75 truncate">
        <strong>Razon Social :</strong>
        {{ form.client_name }}
      </p>
    </fieldset>
  </div>
  <Dialog class="w-250" header="Listado de Cliente" v-model:visible="showClient" modal>
    <FShowClient :other-component="true" :client-data="propsW.clients" />
  </Dialog>
</template>
