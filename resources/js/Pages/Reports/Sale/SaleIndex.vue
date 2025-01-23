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
import {route} from "../../../../../vendor/tightenco/ziggy";
import {Money} from "v-money3";



//Propiedadesd de la ventana
const propsW = defineProps<{
    data: saleFullI[];
    total: totalSoldAmountI;
    totalSold: number,
    from? : string | null;
    to?: string | null;
    type_payment?: string | null;
}>();



/*
Formulario
 */
const form = useForm({
    type_payment:"*",
    from: "",
    to: "",
});


/*
Al momento de cargar
 */
onMounted(()=>{


    //colocar la fecha del dia
    if (!propsW.from || !propsW.to) {
        form.from = setHour(1,0,0,0);
        form.to = setHour(23,59,0,0);
        form.type_payment = "*";
    }else{
        //Colocar los datos de los parametros
        form.from = route().params.from;
        form.to = route().params.to;
        form.type_payment = route().params.type_payment;
    }

    //Pasar los datos recolectado
    if (propsW.total)
    {
        infoReport.totalSold = propsW.totalSold ?? 0;
        infoReport.cash = propsW.total.cash ?? 0;
        infoReport.card = propsW.total.card ?? 0;
        infoReport.credit = propsW.total.credit ?? 0;
        infoReport.check = propsW.total.check ?? 0;
        infoReport.transfer = propsW.total.transfer ?? 0;
        infoReport.discount_amount = propsW.total.discount ?? 0;
        infoReport.tax = propsW.total.tax ?? 0;
        infoReport.amount =  propsW.total.amount - propsW.total.tax;
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
    transfer:0,
    discount_amount: 0,
    tax: 0,
    amount: 0,
    gross: 0
});

// Tipo de pago
const typeOption = ref([
    {
        name:"Todo",
        value:""
    },
    {
        name:"cash",
        value:"cash"
    },
    {
        name:"credit",
        value:"credit"
    },
    {
        name:"check",
        value:"check"
    },
    {
        name:"transfer",
        value:"transfer"
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

</script>

<template>
<!--    Titulo de la ventana-->
    <Head title="Reporte Ventas"/>


<!--    Contenido de la ventana-->
    <AppLayout>
        <div class=" bg-blue-300 rounded-md p-5 max-w-[70rem] mx-auto ">
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
                        <select
                            class="inputGeneral py-1"
                            v-model="form.type_payment">
                            <option selected disabled value="*">-- Tipo Pago --</option>
                            <option
                                v-for="(item, index) in typeOption"
                                :key="index"
                                :value="item.value">
                                {{item.name}}
                            </option>
                        </select>
                        <InputError :message="form.errors.type_payment"/>
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
                        <Money
                            class="inputGeneral"
                            readonly
                            v-model="infoReport.totalSold"
                            v-bind="moneyConfig"/>
                    </div>
                    <!--Tipo de pago cash-->
                    <div>
                        <InputLabel for="cash" value="Efectivo"/>
                        <Money
                            class="inputGeneral"
                            readonly
                            v-model="infoReport.cash"
                            v-bind="moneyConfig"/>
                    </div>
                    <!--Tipo de pago card-->
                    <div>
                        <InputLabel for="tarjeta" value="Tarjetas"/>
                        <Money
                            class="inputGeneral"
                            readonly
                            v-model="infoReport.card"
                            v-bind="moneyConfig"/>
                    </div>
                    <!--Tipo de pago credit-->
                    <div>
                        <InputLabel for="credito" value="Creditos"/>
                        <Money
                            class="inputGeneral"
                            readonly
                            v-model="infoReport.credit"
                            v-bind="moneyConfig"/>
                    </div>
                    <!--Tipo de pago en check-->
                    <div>
                        <InputLabel for="check" value="Cheques"/>
                        <Money
                            class="inputGeneral"
                            readonly
                            v-model="infoReport.check"
                            v-bind="moneyConfig"/>
                    </div>
                    <!--Tipo de pago en transfer-->
                    <div>
                        <InputLabel for="transfer" value="Transferencia"/>
                        <Money
                            class="inputGeneral"
                            readonly
                            v-model="infoReport.transfer"
                            v-bind="moneyConfig"/>
                    </div>
                    <!-- Decuento Total Aplicado-->
                    <div>
                        <InputLabel for="discount" value="Descuento Total"/>
                        <Money
                            class="inputGeneral"
                            v-model="infoReport.discount_amount"
                            v-bind="moneyConfig"/>
                    </div>
                    <!--Itbis Total vendido-->
                    <div>
                        <InputLabel for="tax" value="ITBIS Total"/>
                        <Money
                            class="inputGeneral"
                            readonly
                            v-model="infoReport.tax"
                            v-bind="moneyConfig"/>
                    </div>
                    <!--Balance total vendido en el rango de fecha-->
                    <div>
                        <InputLabel for="amount" value="Balance Total"/>
                        <Money
                            class="inputGeneral"
                            readonly
                            v-model="infoReport.amount"
                            v-bind="moneyConfig"/>
                    </div>

                    <!--Balance total vendido en el rango de fecha-->
                    <div>
                        <InputLabel for="gross" value="Balance Neto"/>
                        <Money
                            class="inputGeneral"
                            readonly
                            v-model="infoReport.gross"
                            v-bind="moneyConfig"/>
                    </div>
                </fieldset>

                <div class="max-h-[20rem] overflow-y-auto">
                    <!--                Listado de produtos vendido-->
                    <table
                        class=" styleTable table-auto w-full mt-10">
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
