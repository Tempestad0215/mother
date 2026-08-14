<script setup lang="ts">
import { totalSoldAmountI } from '@/Interfaces/ReportInterface';
import { saleFullI } from '@/Interfaces/SaleInterface';
import { FileSearchIcon } from '@lucide/vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted, reactive } from 'vue';
import { saleTypeOptions } from '@/Helpers/SaleHelper';
import AppLayout from '@layout/AppLayout.vue';

//Propiedadesd de la ventana
const propsW = defineProps<{
  data: saleFullI[];
  total: totalSoldAmountI;
  totalSold: number;
  from?: string | null;
  to?: string | null;
  type_payment?: string | null;
}>();

/*
Formulario
 */
const form = useForm({
  type_payment: null as string | null,
  from: new Date() as Date | null,
  to: new Date() as Date | null,
});

/*
Al momento de cargar
 */
onMounted(() => {
  //colocar la fecha del dia
  if (!propsW.from || !propsW.to) {
    console.log(propsW);
    form.from = new Date();
    form.to = new Date();
    form.type_payment = null;
  } else {
    const hasFrom = route().params.hasOwnProperty('from');
    const hasTo = route().params.hasOwnProperty('to');

    //Colocar los datos de los parametros
    form.from = hasFrom ? new Date(route().params.from) : new Date();
    form.to = hasTo ? new Date(route().params.to) : new Date();
    form.type_payment = route().params.type_payment ?? null;
  }

  //Pasar los datos recolectados
  if (propsW.total) {
    infoReport.totalSold = propsW.totalSold ?? 0;
    infoReport.cash = propsW.total.cash ?? 0;
    infoReport.card = propsW.total.card ?? 0;
    infoReport.credit = propsW.total.credit ?? 0;
    infoReport.check = propsW.total.check ?? 0;
    infoReport.transfer = propsW.total.transfer ?? 0;
    infoReport.discount_amount = propsW.total.discount ?? 0;
    infoReport.tax = propsW.total.tax ?? 0;
    infoReport.amount = propsW.total.amount - propsW.total.tax;
    infoReport.gross = propsW.total.amount ?? 0;
  }
});

/*
Datos de la ventana
 */
const infoReport = reactive({
  totalSold: 0,
  cash: 0,
  card: 0,
  credit: 0,
  check: 0,
  transfer: 0,
  discount_amount: 0,
  tax: 0,
  amount: 0,
  gross: 0,
});

/*
Funciones
 */

/*
Enviar los datos
 */
const submit = () => {
  form.get(route('report-sale.index'));
};
</script>

<template>
  <!--    Contenido de la ventana-->
  <AppLayout>
    <Card>
      <template #header>
        <div class="text-center">
          <h3 class="text-3xl font-bold">Reportes de Ventas</h3>
        </div>
      </template>

      <template #content>
        <div>
          <form @submit.prevent="submit()" class="my-3 flex gap-5">
            <FloatLabel variant="on">
              <DatePicker v-model="form.from" />
              <label for="date_start">Fecha Inicial</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <DatePicker v-model="form.to" />
              <label for="date_start">Fecha Inicial</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <InputText />
              <label for="client">Cliente</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <Select
                class="w-40"
                v-model="form.type_payment"
                option-value="value"
                option-label="name"
                :options="saleTypeOptions"
              />
              <label for="type_payment">Tipo Pago</label>
            </FloatLabel>
            <div class="flex items-center justify-center">
              <Button type="submit">
                Buscar
                <FileSearchIcon />
              </Button>
            </div>
          </form>
        </div>
        <Divider />
        <DataTable>
          <Column header="CODIGO" />
          <Column header="NCF" />
          <Column header="ITBIS" />
          <Column header="DESCUENTO" />
          <Column header="TOTAL" />
          <Column header="TIPO PAGO" />
        </DataTable>
      </template>
    </Card>
  </AppLayout>
</template>

<style scoped></style>
