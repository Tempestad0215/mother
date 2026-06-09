<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { inject, ref } from 'vue';
import { useRoute } from 'ziggy-js';
import {
  Button,
  FloatLabel,
  InputText,
  Tab,
  TabList,
  TabPanel,
  TabPanels,
  Tabs,
  Tag,
  useToast,
} from 'primevue';
import axios from 'axios';
import { saleDataI } from '@/Interfaces/SaleInterface';
import { formProductKey } from '@/Injections/InjectionKeys';
import { saleKey } from '@/utils/keys';

const toast = useToast();
const route = useRoute();

// Definir las props del componente
const propsW = defineProps<{
  error?: string;
}>();

// Datos de la ventana
const formInject = inject(saleKey)!;
const loadingData = ref(false);

// Formulario
const form = useForm({
  type: true,
  saleCode: '',
  general: '',
});

// Enviar el evento para emitir
const formGet = useForm({
  saleCode: '',
});

/*
Enviar el evento para emitir
 */
const emit = defineEmits<{
  (e: 'closeFormReturn', isReturn: boolean): void;
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
  console.log('enviado', form.type);
  if (form.type) {
  } else {
    //Enviar los datos
    form.get(route('credit-note.index'), {
      preserveState: true,
      onError: () => {
        emit('hasError');
      },
      onSuccess: () => {
        emit('closeFormReturn', true);
      },
    });
  }
};

// Obtener los datos de las cuentas abiertas
const saleGet = async () => {
  try {
    // Verificar si el error es el mismo para mostrar la ventana
    loadingData.value = true;

    // Validar que el campo no este vacio
    if (!formGet.saleCode) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'El campo no puede estar vacio',
        life: 3000,
      });
      return;
    }

    // Obtener los datos de la venta
    const res = await axios.get(route('sale.refund', { code: formGet.saleCode }));

    // Emitir el evento con los datos
    Object.assign(formInject, {
      ...res.data,
      type: 'Devolucion',
      close_table: true,
    });

    emit('closeFormReturn', true);
  } catch (error) {
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'No se encontro la venta',
      life: 3000,
    });
  } finally {
    loadingData.value = false;
  }
};
</script>

<template>
  <div class="fondo p-5 rounded-lg">
    <Tabs value="0">
      <TabList>
        <Tab value="0">Seleccionar</Tab>
        <Tab value="1">Consultar</Tab>
      </TabList>
      <TabPanels>
        <TabPanel value="0">
          <form @submit.prevent="saleGet">
            <div class="mt-5">
              <!-- Etiqueta de la ventana-->
              <FloatLabel variant="on">
                <InputText fluid v-model="formGet.saleCode" />
                <label for="code">Codigo de Factura</label>
              </FloatLabel>
              <Tag v-if="form.errors.saleCode" severity="danger" :value="form.errors.saleCode" />
            </div>
            <div class="mt-3 text-right">
              <Button :disabled="loadingData" type="submit">Buscar</Button>
            </div>
          </form>
        </TabPanel>
        <TabPanel value="1">
          <form>
            <div class="mt-5">
              <!-- Etiqueta de la ventana-->
              <FloatLabel variant="on">
                <InputText fluid v-model="form.saleCode" />
                <label for="code">Codigo de Factura</label>
              </FloatLabel>
              <Tag v-if="form.errors.saleCode" severity="danger" :value="form.errors.saleCode" />
            </div>
            <div class="mt-3 text-right">
              <Button type="submit">Buscar</Button>
            </div>
          </form>
        </TabPanel>
      </TabPanels>
    </Tabs>
  </div>
</template>
