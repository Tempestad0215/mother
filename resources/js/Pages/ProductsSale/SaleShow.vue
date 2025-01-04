<script setup lang="ts">
import {Head, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import FormSearch from "@components/FormSearch.vue";
import {saleI, salePaginationI} from "@/Interfaces/Sale";
import {getMoney, printPdf} from "@/Global/Helpers";
import Pagination from "@components/Pagination.vue";
import InputError from "@components/InputError.vue";
import TabLink from "@components/TabLink.vue";
import Swal from "sweetalert2";


/*
Datos de la pagina
 */
const page = usePage();


/*
 * Propiedades de la ventana
 */
const propsW = defineProps<{
    sales: salePaginationI,
}>();

/*
 * Datos del formulario
 */
const form = useForm({
    search: "",
    perPage: 15,
    general: "",
});


/*
Funciones
 */

//Enviar los datos
const submit = () => {
    form.get('',{
        preserveScroll: true,
        preserveState: true
    });
}

/**
 * Para imprimir las facturas
 * @param item
 */
const printFact =  (item:saleI) => {
    if (item.close_table)
    {
        printPdf(route('invoice.belt.sale',{sale: item.uuid}));
    }else{
        Swal.fire({
            title: "Error",
            text: "Esta orden no esta cerrada",
            icon: "info",
            timer: 3000,
            position: "center"
        });
    }
}


</script>

<template>
    <Head title="Mostrar Ventas"/>
    <AppLayout>
        <template #header>
            <TabLink
                :href="route('sale.create')">
                Ventas
            </TabLink>
            <TabLink
                :active="true"
                :href="route('sale.show')">
                Mostrar
            </TabLink>
        </template>

        <div
            class="bg-blue-300 max-w-[1180px] rounded-md p-5 mx-auto overflow-hidden">
<!--          Mensajes  -->
<!--            Contenido-->
            <div class="flex justify-between items-center">
                <form
                    @submit.prevent="submit">
                    <FormSearch
                        v-model:per-page="form.perPage"
                        v-model:search="form.search"/>
                </form>
                <h3 class="text-3xl font-bold">
                    Ventas
                </h3>
            </div>

            <table
                class=" mt-3 styleTable w-full table-auto">
                <thead >
                    <tr class=" border-b-2 border-gray-800 text-left">
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
                    <tr
                        class="hoverTable"
                        v-for="(item,index) in propsW.sales.data" :key="index">
                        <td>{{item.client_name}}</td>
                        <td>{{ getMoney(item.tax)}}</td>
                        <td>{{getMoney(item.sub_total)}}</td>
                        <td>{{getMoney(item.amount)}}</td>
                        <td>{{item.close_table ? 'Cerrada' : 'Abierta'}}</td>
                        <td>
                            <i
                                title="Imprimir"
                                @click="printFact(item)"
                                class=" icon-efect fa-solid fa-print"></i>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div>
                <InputError :message="page.props.errors.general"/>
            </div>


            <Pagination
                :current-page="propsW.sales.current_page"
                :total-page="propsW.sales.to"
                :prev="propsW.sales.prev_page_url
                    ? propsW.sales.prev_page_url+'&perPage='+form.perPage
                    :''"
                :next=" propsW.sales.prev_page_url
                    ? propsW.sales.next_page_url+'&perPage='+form.perPage
                    : ''"/>

            <!--           Mensajke de error-->
            <InputError :message="page.props.errors.comment"/>

        </div>




    </AppLayout>

</template>
