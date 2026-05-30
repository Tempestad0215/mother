<script setup lang="ts">
import TextInput from '@components/TextInput.vue';
import InputLabel from '@components/InputLabel.vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@components/InputError.vue';
import PrimaryButton from '@components/PrimaryButton.vue';
import { ref } from 'vue';
import { useRoute } from 'ziggy-js';
import { Button, FloatLabel, InputText, Select, Tag } from 'primevue';

const route = useRoute();
/*
Propiedades de la ventana
 */
const propsW = defineProps<{
  error?: string;
}>();

/*
fomulario
 */
const form = useForm({
  type: true,
  saleCode: '',
  general: '',
});

/*
Enviar el evento para emitir
 */
const emit = defineEmits<{
  (e: 'closeFormReturn'): void;
  (e: 'hasError'): void;
}>();

/*
Data de la ventana
 */
const options = ref([
  {
    name: 'Consultar',
    value: true,
  },
  {
    name: 'Seleccionar',
    value: false,
  },
]);

/*
Funciones
 */
const submit = () => {
  if (form.type) {
  } else {
    //Enviar los datos
    form.get(route('credit-note.index'), {
      preserveState: true,
      onError: () => {
        emit('hasError');
      },
      onSuccess: () => {
        emit('closeFormReturn');
      },
    });
  }
};
</script>

<template>
  <div class="fondo p-5 rounded-lg">
    <form @submit.prevent="submit">
      <!--            Si es consulta o para selccionar-->
      <div class="">
        <!--                Titulo-->
        <InputLabel class="flex" for="askReturn" value="Tipo de Consulta" />
        <div class="flex justify-center">
          <Select
            fluid
            v-model="form.type"
            :options="options"
            option-label="name"
            option-value="value"
          />
        </div>
      </div>

      <div class="mt-5">
        <!--           Etiqueta de la ventana-->
        <FloatLabel variant="on">
          <InputText fluid v-model="form.saleCode" />
          <label for="code">Codigo de Factura</label>
        </FloatLabel>
        <Tag v-if="form.errors.saleCode" severity="danger" :value="form.errors.saleCode" />
      </div>

      <!--            Boton de enviar-->
      <div class="mt-5 text-right">
        <Button>Buscar</Button>
      </div>
    </form>
  </div>
</template>
