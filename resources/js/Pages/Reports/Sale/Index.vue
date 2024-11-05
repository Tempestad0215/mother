<script setup lang="ts">

import AppLayout from "@layout/AppLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import DateRange from "@components/DateRange.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import InputLabel from "@components/InputLabel.vue";
import {onMounted, reactive, ref} from "vue";
import {getMoney, moneyConfig, setHour} from "@/Global/Helpers";
import InputError from "@components/InputError.vue";
import {totalSoldAmountI} from "@/Interfaces/Report";
import { saleFullI} from "@/Interfaces/Sale";
import {Money} from "v-money3";
import DatePicker from 'primevue/datepicker';


const propsW = defineProps<{
    data: saleFullI[];
    total: totalSoldAmountI;
    totalSold: number,
    from: string | null;
    to: string | null;
    typePayment: string | null;
}>()

const to = ref();


/*
Formulario
 */
const form = useForm({
    typePayment:"",
    from: "",
    to: "",

});


/*
Al momento de cargar
 */
onMounted(()=>{

    //Poner en los valores que lleva
    // if (propsW.from || propsW.to)
    // {
    //     form.from = propsW.from || "";
    //     form.to = propsW.to || "";
    //     form.typePayment = propsW.typePayment || "";
    // }else{
    //     form.from = setHour(1,0,0,0);
    //     form.to = setHour(12,0,0,0);
    // }

    if (propsW.total)
    {

        infoReport.totalSold = <any>propsW.totalSold;
        infoReport.contado = <any>propsW.total.contado;
        infoReport.tarjeta = <any>propsW.total.tarjeta;
        infoReport.credito = <any>propsW.total.credito;
        infoReport.cheque = <any>propsW.total.cheque;
        infoReport.transferencia = <any>propsW.total.transferencia;
        infoReport.discount_amount = <any>propsW.total.discount;
        infoReport.tax = <any>propsW.total.tax;
        infoReport.amount =  (propsW.total.amount - propsW.total.tax).toFixed(2);
        infoReport.gross = <any>propsW.total.amount;

    }

});


/*
Datos de la ventana
 */
const infoReport = reactive({
   totalSold: "",
   contado: "",
   tarjeta: "",
   credito: "",
    cheque: "",
    transferencia:"",
    discount_amount: "",
    tax: "",
    amount: "",
    gross: ""
});


/*
Funciones
 */

/*
Enviar los datos
 */
const submit = () => {
    form.get(route('report-sale.index'));
}


/**
 * Devoler los parametros
 */
// const getParams = () => {
//     //Tomar los datos del parametros
//     let from = new URL(window.location.href)
//     //Para tomar los parametros
//     let params = new URLSearchParams(from.search);
//
//
//     //Devolver los datos
//     return {
//         from: params.get("from"),
//         to: params.get("to"),
//     }
//
//
// }
</script>

<template>
<!--    Titulo de la ventana-->
    <Head title="Reporte Ventas"/>


<!--    Contenido de la ventana-->
    <AppLayout>
        <div class=" p-5 ">
            <h3 class="title text-center">
                Reportes de Ventas
            </h3>
            <form
                @submit.prevent="submit"
                class="mt-5">

                <DatePicker v-model="to" />

                <div class="flex items-end justify-between">
                    <!--                Rango dee fecha-->
                    <DateRange
                        v-model:from-value="form.from"
                        v-model:to-value="form.to"
                        />

<!--                    Tipo de pago-->
                    <div>
                        <InputLabel for="type_payment" value="Tipo de pago"/>
                        <!--                    Colocar filtrar por tipo de pago-->
                        <select
                            v-model="form.typePayment"
                            class="rounded-md border-gray-300">
                            <option value="">Todo</option>
                            <option value="contado" >Contado</option>
                            <option value="credito">Credito</option>
                            <option value="cheque" >Cheque</option>
                            <option value="tranferencia">Tranferencia</option>
                            <option value="anticipo">Anticipo</option>
                        </select>
                        <InputError :message="form.errors.typePayment"/>
                    </div>


                    <!--                Boton para buscar los datos de ventas-->
                    <div>
                        <PrimaryButton class="">
                            Consultar
                            <i class=" ml-3 icon-efect fa-solid fa-magnifying-glass"></i>
                        </PrimaryButton>
                    </div>

                </div>

<!--                Datos del reporte-->
                <fieldset class="grid grid-cols-4 gap-4 mt-5 border-2 border-gray-800 rounded-md p-5">
                    <legend>
                        Datos De Ventas
                    </legend>

                    <!--                    Productos vendid-->
                    <div>
                        <InputLabel for="productSold" value="Productos Vendidos"/>
                        <money
                            class="inputGeneral w-full"
                            v-model.number="infoReport.totalSold"
                            v-bind="moneyConfig"
                            readonly/>
<!--                        <TextInput-->
<!--                            v-model="data"-->
<!--                            :v-bind="moneyConfig"-->
<!--                            class="w-full"-->
<!--                            readonly/>-->
                    </div>
                    <!--Tipo de pago contado-->
                    <div>
                        <InputLabel for="productSold" value="Contado"/>
                        <money
                            class="inputGeneral w-full"
                            v-model.number="infoReport.contado"
                            v-bind="moneyConfig"
                            readonly/>
                    </div>
                    <!--Tipo de pago tarjeta-->
                    <div>
                        <InputLabel for="productSold" value="Tarjeta"/>
                        <money
                            class="inputGeneral w-full"
                            v-model.number="infoReport.tarjeta"
                            v-bind="moneyConfig"
                            readonly/>
                    </div>
                    <!--Tipo de pago credito-->
                    <div>
                        <InputLabel for="productSold" value="Credito"/>
                        <money
                            class="inputGeneral w-full"
                            v-model.number="infoReport.credito"
                            v-bind="moneyConfig"
                            readonly/>
                    </div>
                    <!--Tipo de pago en cheque-->
                    <div>
                        <InputLabel for="productSold" value="Cheque"/>
                        <money
                            class="inputGeneral w-full"
                            v-model.number="infoReport.cheque"
                            v-bind="moneyConfig"
                            readonly/>
                    </div>
                    <!--Tipo de pago en Transferencia-->
                    <div>
                        <InputLabel for="productSold" value="Transferencia"/>
                        <money
                            class="inputGeneral w-full "
                            v-model.number="infoReport.transferencia"
                            v-bind="moneyConfig"
                            readonly/>
                    </div>
                    <!-- Decuento Total Aplicado-->
                    <div>
                        <InputLabel for="productSold" value="Descuento Total"/>
                        <money
                            class="inputGeneral w-full"
                            v-model.number="infoReport.discount_amount"
                            v-bind="moneyConfig"
                            readonly/>
                    </div>
                    <!--Itbis Total vendido-->
                    <div>
                        <InputLabel for="productSold" value="ITBIS Total"/>
                        <money
                            class="inputGeneral w-full"
                            v-model.number="infoReport.tax"
                            v-bind="moneyConfig"
                            readonly/>
                    </div>
                    <!--Balance total vendido en el rango de fecha-->
                    <div>
                        <InputLabel for="productSold" value="Balance Total"/>
                        <money
                            class="inputGeneral w-full"
                            v-model.number="infoReport.amount"
                            v-bind="moneyConfig"
                            readonly/>
                    </div>

                    <!--Balance total vendido en el rango de fecha-->
                    <div>
                        <InputLabel for="productSold" value="Balance Neto"/>
                        <money
                            class="inputGeneral w-full"
                            v-model.number="infoReport.gross"
                            v-bind="moneyConfig"
                            readonly/>
                    </div>
                </fieldset>

                <div class="max-h-[20rem] overflow-y-auto">
                    <!--                Listado de produtos vendido-->
                    <table
                        class="table-auto w-full mt-10">
                        <caption class="text-xl italic underline">
                            Listado de Ventas
                        </caption>
                        <thead
                            class=" sticky top-0 border-2 border-collapse border-black">
                        <tr>
                            <th>Code</th>
                            <th>NCF</th>
                            <th>ITBIS</th>
                            <th>Descuento</th>
                            <th>Total</th>
                            <th>Tipo Pago</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(item, index) in propsW.data" :key="index">
                            <td>{{item.code}}</td>
                            <td>{{item.ncf || 'N/A'}}</td>
                            <td >{{ getMoney(item.tax)}}</td>
                            <td >{{ getMoney(item.discount_amount)}}</td>
                            <td >{{ getMoney(item.amount)}}</td>
                            <td
                                class="uppercase" >
                                {{item.type_payment}}
                            </td>
                        </tr>
                        </tbody>

                    </table>
                </div>


            </form>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
