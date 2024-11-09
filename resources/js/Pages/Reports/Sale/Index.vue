<script setup lang="ts">

import AppLayout from "@layout/AppLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import DateRange from "@components/DateRange.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import InputLabel from "@components/InputLabel.vue";
import {onMounted, reactive, ref} from "vue";
import {getMoney, setHour} from "@/Global/Helpers";
import InputError from "@components/InputError.vue";
import {totalSoldAmountI} from "@/Interfaces/Report";
import { saleFullI} from "@/Interfaces/Sale";
import InputNumber from 'primevue/inputnumber';
import {route} from "../../../../../vendor/tightenco/ziggy";
import Select from 'primevue/select';



//Propiedadesd de la ventana
const propsW = defineProps<{
    data: saleFullI[];
    total: totalSoldAmountI;
    totalSold: number,
    from: string | null;
    to: string | null;
    typePayment: string | null;
}>();



/*
Formulario
 */
const form = useForm({
    typePayment:"*",
    from: "",
    to: "",
});


/*
Al momento de cargar
 */
onMounted(()=>{

    if (!propsW.from || !propsW.to) {
        form.from = setHour(1,0,0,0);
        form.to = setHour(23,59,0,0);
    }else{
        //Colocar los datos de los parametros
        form.from = route().params.from;
        form.to = route().params.to;
        form.typePayment = route().params.typePayment;
    }


    //Pasar los datos recolectado
    if (propsW.total)
    {
        infoReport.totalSold = propsW.totalSold;
        infoReport.contado = propsW.total.contado;
        infoReport.tarjeta = propsW.total.tarjeta;
        infoReport.credito = propsW.total.credito;
        infoReport.cheque = propsW.total.cheque;
        infoReport.transferencia = propsW.total.transferencia;
        infoReport.discount_amount = propsW.total.discount;
        infoReport.tax = propsW.total.tax;
        infoReport.amount =  propsW.total.amount - propsW.total.tax;
        infoReport.gross = propsW.total.amount;

    }

});


/*
Datos de la ventana
 */
const infoReport = reactive({
    totalSold: 0,
    contado: 0,
    tarjeta: 0,
    credito: 0,
    cheque: 0,
    transferencia:0,
    discount_amount: 0,
    tax: 0,
    amount: 0,
    gross: 0
});
const typeOption = ref([
    {
        name:"Todo",
        value:""
    },
    {
        name:"Contado",
        value:"contado"
    },
    {
        name:"Credito",
        value:"credito"
    },
    {
        name:"Cheque",
        value:"cheque"
    },
    {
        name:"Transferencia",
        value:"transferencia"
    },
    {
        name:"Anticipo",
        value:"anticipo"
    },
])

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

                <div class="flex items-end justify-between">
                    <!--                Rango dee fecha-->
                    <DateRange
                        v-model:from="form.from"
                        v-model:to="form.to"
                        />

<!--                    Tipo de pago-->
                    <div>
                        <InputLabel for="type_payment" value="Tipo de pago"/>
                        <!--                    Colocar filtrar por tipo de pago-->
                        <Select
                            v-model="form.typePayment"
                            :options="typeOption"
                            optionLabel="name"
                            option-value="value"
                            placeholder="Selecciona El Tipo"
                            class="w-[15rem]"
                            fluid/>
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
                        <InputNumber
                            v-model="infoReport.totalSold"
                            inputId="locale-us"
                            locale="en-US"
                            :allow-empty="false"
                            :minFractionDigits="2"
                            :max-fraction-digits="2"
                            fluid />
                    </div>
                    <!--Tipo de pago contado-->
                    <div>
                        <InputLabel for="productSold" value="Contado"/>
                        <InputNumber
                            v-model="infoReport.contado"
                            inputId="locale-us"
                            locale="en-US"
                            :allow-empty="false"
                            :minFractionDigits="2"
                            :max-fraction-digits="2"
                            fluid />
                    </div>
                    <!--Tipo de pago tarjeta-->
                    <div>
                        <InputLabel for="productSold" value="Tarjeta"/>
                        <InputNumber
                            v-model="infoReport.tarjeta"
                            inputId="locale-us"
                            locale="en-US"
                            :allow-empty="false"
                            :minFractionDigits="2"
                            :max-fraction-digits="2"
                            fluid />
                    </div>
                    <!--Tipo de pago credito-->
                    <div>
                        <InputLabel for="productSold" value="Credito"/>
                        <InputNumber
                            v-model="infoReport.credito"
                            inputId="locale-us"
                            locale="en-US"
                            :allow-empty="false"
                            :minFractionDigits="2"
                            :max-fraction-digits="2"
                            fluid />
                    </div>
                    <!--Tipo de pago en cheque-->
                    <div>
                        <InputLabel for="productSold" value="Cheque"/>
                        <InputNumber
                            v-model="infoReport.cheque"
                            inputId="locale-us"
                            locale="en-US"
                            :allow-empty="false"
                            :minFractionDigits="2"
                            :max-fraction-digits="2"
                            fluid />
                    </div>
                    <!--Tipo de pago en Transferencia-->
                    <div>
                        <InputLabel for="productSold" value="Transferencia"/>
                        <InputNumber
                            v-model="infoReport.transferencia"
                            inputId="locale-us"
                            locale="en-US"
                            :allow-empty="false"
                            :minFractionDigits="2"
                            :max-fraction-digits="2"
                            fluid />
                    </div>
                    <!-- Decuento Total Aplicado-->
                    <div>
                        <InputLabel for="productSold" value="Descuento Total"/>
                        <InputNumber
                            v-model="infoReport.discount_amount"
                            inputId="locale-us"
                            locale="en-US"
                            :allow-empty="false"
                            :minFractionDigits="2"
                            :max-fraction-digits="2"
                            fluid />
                    </div>
                    <!--Itbis Total vendido-->
                    <div>
                        <InputLabel for="productSold" value="ITBIS Total"/>
                        <InputNumber
                            v-model="infoReport.tax"
                            inputId="locale-us"
                            locale="en-US"
                            :allow-empty="false"
                            :minFractionDigits="2"
                            :max-fraction-digits="2"
                            fluid />
                    </div>
                    <!--Balance total vendido en el rango de fecha-->
                    <div>
                        <InputLabel for="productSold" value="Balance Total"/>
                        <InputNumber
                            v-model="infoReport.amount"
                            inputId="locale-us"
                            locale="en-US"
                            :allow-empty="false"
                            :minFractionDigits="2"
                            :max-fraction-digits="2"
                            fluid />
                    </div>

                    <!--Balance total vendido en el rango de fecha-->
                    <div>
                        <InputLabel for="productSold" value="Balance Neto"/>
                        <InputNumber
                            v-model="infoReport.gross"
                            inputId="locale-us"
                            locale="en-US"
                            :allow-empty="false"
                            :minFractionDigits="2"
                            :max-fraction-digits="2"
                            fluid />
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
