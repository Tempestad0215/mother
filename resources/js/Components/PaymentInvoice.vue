<script setup lang="ts">
import { typePaymentData } from '@/Global/ShareData';
import { getMoney } from '@/Global/Helpers';
import { inject, onMounted, ref } from 'vue';
import { PreciseCalculator } from '@/utils/Decimal';
import { useRoute } from 'ziggy-js';
import {
  Button,
  Column,
  DataTable,
  FloatLabel,
  InputGroup,
  InputGroupAddon,
  InputNumber,
  InputText,
  Select,
  useToast,
} from 'primevue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faTrashAlt } from '@fortawesome/free-solid-svg-icons';
import { Search } from '@lucide/vue';
import { CreditNoteBalance } from '@/Interfaces/CreditNoteInterface';
import { saleKey } from '@/Injections/saleKeys';
import { useSaleCreditNote } from '@/Composable/Sales/useSaleCreditNote';

const toast = useToast();
const route = useRoute();
const emit = defineEmits<{
  (e: 'senData'): void;
  (e: 'sendCreditData', data: CreditNoteBalance): void;
}>();

const form = inject(saleKey)!;
const loadingCreditNote = ref(false);

const { amountCreditNote, getCreditNoteInfo, deleteCreditNote } = useSaleCreditNote();
const code = ref('');

// Obtener los datos de las cuentas abiertas
onMounted(() => {
  form.pending = form.amount;
});

// Obtener los datos de las cuentas abiertas
const checkSale = () => {
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

// Obtener los datos de las cuentas abiertas

// Exponer los metodos para el componente padre
defineExpose({
  checkSale,
  returnedBlur,
});

const getCreditNote = async () => {
  await getCreditNoteInfo(code.value);
};
</script>

<template>
  <!--Datos de la ventana-->
  <div class="fondo p-5 rounded-md min-w-120 max-w-300 h-fit mx-auto">
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
        <form @submit.prevent="getCreditNote" action="">
          <FloatLabel variant="on">
            <InputGroup>
              <InputText v-model="code" />
              <InputGroupAddon>
                <Button @click="getCreditNote" :loading="loadingCreditNote">
                  <Search />
                </Button>
              </InputGroupAddon>
            </InputGroup>
          </FloatLabel>
        </form>
      </div>
    </div>

    <!--Aplicar nota de credito-->
    <div class="mt-3">
      <DataTable :value="form.credit_notes">
        <Column header="Cod./NCF" field="code" />
        <Column
          header="B. Inicial"
          :field="(data: CreditNoteBalance) => `${getMoney(parseFloat(data.n_available))}`"
        />
        <Column
          header="B. Disponible"
          :field="(data: CreditNoteBalance) => `${getMoney(data.n_available_new)}`"
        />
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
