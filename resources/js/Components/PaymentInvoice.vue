<script setup lang="ts">
import { typePaymentData } from '@/Global/ShareData';
import { getMoney } from '@/Global/Helpers';
import axios from 'axios';
import { inject, onMounted } from 'vue';
import { saleKey } from '@/utils/keys';
import { PreciseCalculator } from '@/utils/Decimal';
import { useRoute } from 'ziggy-js';
import {
  Select,
  InputNumber,
  FloatLabel,
  InputGroup,
  InputGroupAddon,
  InputText,
  DataTable,
  Column,
  Button,
  useToast,
} from 'primevue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faTrashAlt } from '@fortawesome/free-solid-svg-icons';
import { Search } from '@lucide/vue';

const toast = useToast();
const route = useRoute();
const emit = defineEmits<{
  (e: 'senData'): void;
}>();

const form = inject(saleKey)!;

const creditNote = defineModel<string>('creditNote', {
  default: '',
});
const showReturn = defineModel<boolean>('showReturn', {
  default: false,
});

// Obtener los datos de las cuentas abiertas
onMounted(() => {
  form.pending = form.amount;
});

// Obtener los datos de las cuentas abiertas
const getCreditNote = async () => {
  //Si no hay suficiente caracateres
  if (creditNote?.value.length < 5) {
    form.setError('credit_notes_value', 'Por Favor, Introduzca Valores Valido');
    return false;
  }

  //Verificar si ya esta en positivo no puede colocar nota de credito
  if (form.returned > 0) {
    form.setError('credit_notes_value', 'Existe Suficiente Balance Para Cerrar La Cuenta');
    return false;
  }

  //Verificar si exsite alguna igual
  const exist: boolean = form.credit_notes.some(
    (el) => el.code == creditNote.value || el.ncf == creditNote.value
  );

  //Verificar si existe la misma nota de credito
  if (exist) {
    form.setError('credit_notes_value', 'Esta Nota De Credito, Esta Agregada');
  } else {
    //Buscar la nota de credito
    const { data } = await axios.get(route('credit-note.get', { code: creditNote.value }));

    //Verifciar los datos
    if (data.hasOwnProperty('code')) {
      //Pasar los datos al formulario
      form.credit_notes.push(data);
      //Calcular los datos
      amountCreditNote();
      //Limpiar los errores
      form.clearErrors('credit_notes_value');
      //Limpiar el campo para agreagr otros
      form.reset('credit_notes_value');
    } else {
      //Poner el mensaje de error
      form.setError('credit_notes_value', data.error);
    }
  }
};

//  Eliminar una nota de credito
const deleteCreditNote = (index: number) => {
  //Eliminar solo el dato seleccionado
  form.credit_notes.splice(index, 1);
  //Realizar el calculo
  amountCreditNote();
};

// Obtener los datos de las cuentas abiertas
const amountCreditNote = () => {
  //REalizar el cálculo de notas de credito
  form.credit_notes_amount = form.credit_notes.reduce(
    (acc, cur) => acc + Number(cur.n_available),
    0
  );

  //Datos pendientes por pagar
  form.returned = form.credit_notes_amount - form.amount;
  form.pending =
    form.credit_notes_amount - form.amount < 0 ? form.credit_notes_amount - form.amount : 0;
};

// Obtener los datos de las cuentas abiertas
const checkSale = () => {
  showReturn.value = true;
  //Verificar si se puede mostrar los datos
  if (form.close_table && form.info_sale.length > 0) {
    //REalizar calculo si existe
    amountCreditNote();
    //Mostar la ventana
    // saleDetailRef.value?.openReturn()
  } else {
    // sendData();
  }

  // Llamar el metodo para el cálculo
  returned();
};

// Enviar los datos al servidor
const returned = () => {
  let totalForPay: number = Number(PreciseCalculator.add(form.credit_notes_amount, form.received));

  //Restar la cantidad
  let returned = Number(PreciseCalculator.subtract(totalForPay, form.amount));
  form.returned = returned > 0 ? returned : 0;
  form.pending = returned > 0 ? 0 : Math.abs(returned);
};

// Al momento de perder el foco del campo de devuelta
const returnedBlur = () => {
  //Primero verifica la cantidad
  returned();

  //Verificar el cálculo
  if (form.returned < 0) {
    //Enviar el mensaje de error
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'El monto recibido no puede ser menor al Total',
    });

    return false;
  } else {
    emit('senData');
    return true;
  }
};

// Exponer los metodos para el componente padre
defineExpose({
  checkSale,
  returnedBlur,
});
</script>

<template>
  <!--Datos de la ventana-->
  <div class="fondo p-5 rounded-md min-w-120 max-w-200 h-fit mx-auto">
    <div class="flex items-center gap-3 mt-5">
      <!--Tipo de apgo-->
      <div class="flex-1">
        <FloatLabel variant="on">
          <Select
            :options="typePaymentData"
            optionLabel="name"
            optionValue="value"
            v-model="form.type_payment"
          />
          <label for="typePayment">Tipo de Pago</label>
        </FloatLabel>
      </div>
      <div class="flex-1">
        <FloatLabel variant="on">
          <InputGroup>
            <InputText v-model="creditNote" />
            <InputGroupAddon>
              <Search />
            </InputGroupAddon>
          </InputGroup>
        </FloatLabel>
      </div>
    </div>

    <!--Aplicar nota de credito-->
    <div class="mt-3">
      <DataTable :value="form.credit_notes">
        <Column header="Cod./NCF" field="code" />
        <Column header="Disponible" field="n_available" />
        <Column header="Act">
          <template #body="{ index }: { index: number }">
            <FontAwesomeIcon @click="deleteCreditNote(index)" :icon="faTrashAlt" />
          </template>
        </Column>
      </DataTable>
    </div>

    <!--Monto Recibido-->
    <div class="w-full mt-3">
      <FloatLabel variant="on">
        <InputNumber @blur="returned" v-model="form.received" />
        <label for="received">Monto Recibido</label>
      </FloatLabel>
    </div>

    <!--Monto Pendiente-->
    <div class="mt-5">
      <div class="flex justify-between py-2 text-orange-300">
        <span>Total a pagar :</span>
        <p>{{ getMoney(form.amount) }}</p>
      </div>
      <div class="flex justify-between py-2 text-orange-400">
        <span>Pendiente :</span>
        <p>{{ getMoney(form.pending) }}</p>
      </div>
      <div class="flex justify-between border-t-2 py-2 text-green-500 text-2xl">
        <span>Devuelta :</span>
        <p>{{ getMoney(form.returned) }}</p>
      </div>
    </div>

    <!-- Boton para cerrar la factura -->
    <div class="mt-3 text-right">
      <Button
        :disabled="form.processing"
        @click="returnedBlur"
        type="button"
        label="Cerrar Factura"
      />
    </div>
  </div>
</template>
