<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import FormSearch from '@components/FormSearch.vue';
import { saleI, salePaginationI } from '@/Interfaces/SaleInterface';
import { getMoney, printPdf } from '@/Global/Helpers';
import InputError from '@components/InputError.vue';
import TabLink from '@components/TabLink.vue';
import { useRoute } from 'ziggy-js';

const route = useRoute();
/*
Datos de la pagina
 */
const page = usePage();

/*
 * Propiedades de la ventana
 */
const propsW = defineProps<{
  sales: salePaginationI;
}>();

/*
 * Datos del formulario
 */
const form = useForm({
  search: '',
  per_page: 30,
  field: 'client',
});

/*
Funciones
 */

//Enviar los datos
const submit = () => {
  form.get('', {
    preserveScroll: true,
    preserveState: true,
  });
};

/**
 * Para imprimir las facturas
 * @param item
 */
const printFact = (item: saleI) => {
  if (item.close_table) {
    printPdf(route('invoice.belt.sale', { sale: item.id }));
  } else {
    // Swal.fire({
    //     title: "Error",
    //     text: "Esta orden no esta cerrada",
    //     icon: "info",
    //     timer: 3000,
    //     position: "center"
    // });
  }
};
</script>

<template>
  <Head title="Mostrar Ventas" />
  <AppLayout>
    <template #header>
      <TabLink :href="route('sale.create')"> Registrar </TabLink>
      <TabLink :active="true" :href="route('sale.show')"> Mostrar </TabLink>
      <TabLink :href="route('credit-note.show')"> N. Credito </TabLink>
      <TabLink :href="route('sale.close')"> Cierre </TabLink>
    </template>

    <div class="fondo rounded-md p-5 overflow-hidden">
      <!--          Mensajes  -->
      <!--            Contenido-->
      <div class="flex justify-between items-center">
        <form @submit.prevent="submit">
          <FormSearch v-model:per-page="form.per_page" v-model:search="form.search" />
        </form>
        <h3 class="text-3xl font-bold">Ventas</h3>
      </div>

      <table class="mt-3 styleTable w-full table-auto">
        <thead>
          <tr class="border-b-2 border-gray-800 text-left">
            <th>Cliente</th>
            <th>Itbis</th>
            <th>Sub Total</th>
            <th>Total</th>
            <th>Mesa A/C</th>
            <th>Act</th>
            <!--                        <th v-if="page.props.auth.user.role === 'admin'">Act</th>-->
          </tr>
        </thead>
        <tbody>
          <tr class="hoverTable" v-for="(item, index) in propsW.sales.data" :key="index">
            <td>{{ item.client_name }}</td>
            <td>{{ getMoney(item.tax) }}</td>
            <td>{{ getMoney(item.sub_total) }}</td>
            <td>{{ getMoney(item.amount) }}</td>
            <td>{{ item.close_table ? 'Cerrada' : 'Abierta' }}</td>
            <td>
              <i title="Imprimir" @click="printFact(item)" class="icon-efect fa-solid fa-print"></i>
            </td>
          </tr>
        </tbody>
      </table>

      <div>
        <InputError :message="page.props.errors.general" />
      </div>

      <!--            PAginacion de la ventana-->
      <!--      <Pagination-->
      <!--        :pag=""-->
      <!--        :field="form.field"-->
      <!--        :per-page="form.per_page"-->
      <!--        :search="form.search"-->
      <!--        :current-page="propsW.sales.current_page"-->
      <!--        :total-page="propsW.sales.to"-->
      <!--        :prev="propsW.sales.prev_page_url"-->
      <!--        :next="propsW.sales.prev_page_url"-->
      <!--      />-->

      <!--           Mensajke de error-->
      <InputError :message="page.props.errors.comment" />
    </div>
  </AppLayout>
</template>
