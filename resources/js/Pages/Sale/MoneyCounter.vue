<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@components/InputError.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {getMoney, moneyConfig} from "@/Global/Helpers";
import axios from "axios";
import {ref, Ref} from "vue";
import ShowPdf from "@components/ShowPdf.vue";
import {Money} from "v-money3";
import TabLink from "@components/TabLink.vue";
import {successHttp} from "@/Global/Alert";



/*
Datos del formulario
 */
const form = useForm({
    from: null, //Desde
    to: null, //Hasta
    coin_first: 0, //1
    coin_second: 0, //5
    coin_third: 0, //10
    coin_fourth: 0, //25
    coin_fifth: 0, //50
    coin_sixth: 0, //100
    coin_seventh: 0, //200
    coin_eighth: 0, //500
    coin_ninth: 0, //1000,
    coin_tenth: 0, //2000
    card: 0,//TArjeta
    transfer: 0, //Transferencia
    check: 0, //Cheque
    other_income: 0, //Otras monedas
    expenses: 0, //Gatos
    cash_withdrawals: 0, //Retiro de la caja
    refund: 0, //Devoluciones
    other_expenses: 0,// otros Egresos
    opening_balance: 0, //Balance Inicial
    total_coin: 0, //Balance total
    total_other_coin: 0, //Otros ingresos
    total_expenses: 0, //Gatos totales
    diff: 0, //diferencia de dinero
    total_neto: 0, //total netoregistrado
});


/*
Datos de la ventana
 */
const pdfUrl:Ref<string> = ref("");
const showPdf:Ref<boolean> = ref(false);
const processing = ref<boolean>(false);
const errorValue = ref<Record<string, any> | null>(null);


/*
Funciones
 */

/**
 * Multiplicar el valor de cada datos
 * @param value
 * @param factor
 */
const multCoin = (value:number, factor:number):number => {
    return value * factor;
}




/**
 * Crear la sumatoria
 */
const sumCoin = () => {
    //Total de todas las monedas
    let coinTotal:number = form.coin_first  + multCoin(form.coin_second, 5) + multCoin(form.coin_third, 10) + multCoin(form.coin_fourth, 25) + multCoin(form.coin_fifth, 50) + multCoin(form.coin_sixth, 100) + multCoin(form.coin_seventh, 200) + multCoin(form.coin_eighth, 500) + multCoin(form.coin_ninth, 1000) + multCoin(form.coin_tenth, 2000);


    //Obtener otros ingresos
    let other_income:number = form.card + form.transfer + form.check + form.other_income;

    //Egresos
    let expenses:number = form.expenses + form.cash_withdrawals + form.refund + form.other_expenses;

    //Pasar los datos al formulario
    form.total_coin = coinTotal; //total de las monedas
    form.total_other_coin = other_income; //total de otros ingresos
    form.total_expenses = expenses; //Gastos
    form.total_neto = coinTotal + other_income; // Total neto
    form.diff = (form.total_neto - form.opening_balance ) - expenses; // Calculo de diferencia

}


/**
 * Enviar los datos
 */
const submit = () => {
    processing.value = true;
    //enviar los datos
    axios.post(route('sale.report.store'),form)
        .then((res)=> {
            processing.value = false;
            successHttp('Registrado Creado Correctamente');
        }).catch((err)=> {

            errorValue.value = err.response.data.errors;
        console.log(err)
            processing.value = false;
    });
}


/**
 * Mensaje de error del pdf
 */
const getErrorPdf = () => {

}


</script>

<template>
    <AppLayout
        title="Recuento de Moneda">
        <template #header >
            <TabLink
                :href="route('sale.create')">
                Registrar
            </TabLink>
            <TabLink
                :href="route('sale.show')">
                Mostrar
            </TabLink>
            <TabLink
                :href="route('credit-note.show')">
                N. Credito
            </TabLink>
            <TabLink
                :href="route('sale.close')">
                Cierre
            </TabLink>
            <TabLink
                :active="true"
                :href="route('sale.counter')">
                Conteo
            </TabLink>
        </template>

<!--        Contenido de la ventana-->
        <div class="p-5 fondo rounded-md overflow-y-auto">

            <h3 class="title text-center">
                Cuadre de Caja
            </h3>
            <div v-if="errorValue">
                <ol
                    v-for="(item, index) in errorValue"
                    :key="index">
                    <li class="text-red-800" >{{item[0]}}</li>
                </ol>
            </div>
            <form
                @submit.prevent="submit"
                class=" grid grid-cols-2 gap-3 mt-5 overflow-y-auto" >
                <fieldset class="field block">
                    <legend>
                        Efectivo
                    </legend>
                    <!-- Tipo de monedas -->
                    <div>
                        <div class="">
                            <InputLabel for="coinOne" value="Moneda de 1" />
                            <div class="relative">
                                <Money
                                    @keyup="sumCoin"
                                    class="inputGeneral"
                                    v-model.number="form.coin_first"
                                    v-bind="moneyConfig"/>
                                <span class="text-gray-50">
                                   X 1  = {{ getMoney(multCoin(form.coin_first, 1))}}
                                </span>
                            </div>
                            <InputError :message="form.errors.coin_first"/>
                        </div>
                        <div>
                            <InputLabel for="coin_second" value="Moneda de 5" />
                            <div class="relative">
                                <Money
                                    @keyup="sumCoin"
                                    class="inputGeneral"
                                    v-model.number="form.coin_second"
                                    v-bind="moneyConfig"/>
                                <span class="text-gray-50">
                               X 1  = {{ getMoney(multCoin(form.coin_second, 5))}}
                            </span>
                            </div>
                            <InputError :message="form.errors.coin_second"/>
                        </div>
                        <div>
                            <InputLabel for="coin_third" value="Moneda de 10" />
                            <div class="relative">
                                <Money
                                    @keyup="sumCoin"
                                    class="inputGeneral"
                                    v-model.number="form.coin_third"
                                    v-bind="moneyConfig"/>
                                <span class="text-gray-50">
                               X 1  = {{ getMoney(multCoin(form.coin_third, 10))}}
                            </span>
                            </div>
                            <InputError :message="form.errors.coin_third"/>
                        </div>
                        <div>
                            <InputLabel for="coin_fourth" value="Moneda de 25" />
                            <div class="relative">
                                <Money
                                    @keyup="sumCoin"
                                    class="inputGeneral"
                                    v-model.number="form.coin_fourth"
                                    v-bind="moneyConfig"/>
                                <span class="text-gray-50">
                               X 1  = {{ getMoney(multCoin(form.coin_fourth, 25))}}
                            </span>
                            </div>
                            <InputError :message="form.errors.coin_fourth"/>
                        </div>
                        <div>
                            <InputLabel for="coin_fifth" value="Papeleta de 50" />
                            <div class="relative">
                                <Money
                                    @keyup="sumCoin"
                                    class="inputGeneral"
                                    v-model.number="form.coin_fifth"
                                    v-bind="moneyConfig"/>
                                <span class="text-gray-50">
                               X 1  = {{ getMoney(multCoin(form.coin_fifth, 50))}}
                            </span>
                            </div>
                            <InputError :message="form.errors.coin_fifth"/>
                        </div>
                        <div>
                            <InputLabel for="coin_sixth" value="Papeleta de 100" />
                            <div class="relative">
                                <Money
                                    @keyup="sumCoin"
                                    class="inputGeneral"
                                    v-model.number="form.coin_sixth"
                                    v-bind="moneyConfig"/>
                                <span class="text-gray-50">
                               X 1  = {{ getMoney(multCoin(form.coin_sixth, 100))}}
                            </span>
                            </div>
                            <InputError :message="form.errors.coin_sixth"/>
                        </div>
                        <div>
                            <InputLabel for="coin_seventh" value="Papeleta de 200" />
                            <div class="relative">
                                <Money
                                    @keyup="sumCoin"
                                    class="inputGeneral"
                                    v-model.number="form.coin_seventh"
                                    v-bind="moneyConfig"/>
                                <span class="text-gray-50">
                               X 1  = {{ getMoney(multCoin(form.coin_seventh, 200))}}
                            </span>
                            </div>
                            <InputError :message="form.errors.coin_seventh"/>
                        </div>
                        <div>
                            <InputLabel for="coin_eighth" value="Papeleta de 500" />
                            <div class="relative">
                                <Money
                                    @keyup="sumCoin"
                                    class="inputGeneral"
                                    v-model.number="form.coin_eighth"
                                    v-bind="moneyConfig"/>
                                <span class="text-gray-50">
                               X 1  = {{ getMoney(multCoin(form.coin_eighth, 500))}}
                            </span>
                            </div>
                            <InputError :message="form.errors.coin_eighth"/>
                        </div>
                        <div>
                            <InputLabel for="coin_ninth" value="Papeleta de 1,000" />
                            <div class="relative">
                                <Money
                                    @keyup="sumCoin"
                                    class="inputGeneral"
                                    v-model.number="form.coin_ninth"
                                    v-bind="moneyConfig"/>
                                <span class="text-gray-50">
                               X 1  = {{ getMoney(multCoin(form.coin_ninth, 1000))}}
                            </span>
                            </div>
                            <InputError :message="form.errors.coin_ninth"/>
                        </div>
                        <div>
                            <InputLabel for="coin_tenth" value="Papeleta de 2,000" />
                            <div class="relative">
                                <Money
                                    class="inputGeneral"
                                    v-model.number="form.coin_tenth"
                                    v-bind="moneyConfig"/>
                                <span class="text-gray-50">
                               X 1  = {{ getMoney(multCoin(form.coin_tenth, 2000))}}
                            </span>
                            </div>
                            <InputError :message="form.errors.coin_tenth"/>
                        </div>
                    </div>
                </fieldset>

<!--                Datos variados-->
                <div class="grid grid-cols-2 gap-2">

                    <fieldset class="field block">
                        <legend>Otros Ingresos</legend>

                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="tarjeta"
                                value="Tarjetas"/>
                            <Money
                                class="inputGeneral"
                                @keyup="sumCoin"
                                v-model.number="form.card"
                                v-bind="moneyConfig"/>
                            <InputError :message="form.errors.card"/>
                        </div>

                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="Transferencia"
                                value="Transferencia"/>
                            <Money
                                @keyup="sumCoin"
                                class="inputGeneral"
                                v-model.number="form.transfer"
                                v-bind="moneyConfig"/>
                            <InputError :message="form.errors.transfer"/>
                        </div>

                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="cheque"
                                value="Cheques"/>
                            <Money
                                @keyup="sumCoin"
                                class="inputGeneral"
                                v-model.number="form.check"
                                v-bind="moneyConfig"/>
                            <InputError :message="form.errors.check"/>
                        </div>
                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="other"
                                value="Otras Monedas"/>
                            <Money
                                @keyup="sumCoin"
                                class="inputGeneral"
                                v-model.number="form.other_income"
                                v-bind="moneyConfig"/>
                            <InputError :message="form.errors.other_income"/>
                        </div>
                    </fieldset>

<!--                    Egresos-->
                    <fieldset
                        class="field block">
                        <legend>Gastos</legend>
                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="expenses"
                                value="Gastos"/>
                            <Money
                                @keyup="sumCoin"
                                class="inputGeneral"
                                v-model.number="form.expenses"
                                v-bind="moneyConfig"/>
                            <InputError :message="form.errors.expenses"/>
                        </div>

                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="cash_withdrawals"
                                value="Retiro de Caja"/>
                            <Money
                                @keyup="sumCoin"
                                class="inputGeneral"
                                v-model.number="form.cash_withdrawals"
                                v-bind="moneyConfig"/>
                            <InputError :message="form.errors.cash_withdrawals"/>
                        </div>

                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="refund"
                                value="Devolucione"/>
                            <Money
                                @keyup="sumCoin"
                                class="inputGeneral"
                                v-model.number="form.refund"
                                v-bind="moneyConfig"/>
                            <InputError :message="form.errors.refund"/>
                        </div>
                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="other_expenses"
                                value="Otros"/>
                            <Money
                                @keyup="sumCoin"
                                class="inputGeneral"
                                v-model.number="form.other_expenses"
                                v-bind="moneyConfig"/>
                            <InputError :message="form.errors.other_expenses"/>
                        </div>
                    </fieldset>


<!--                    Totales de la ventnaa-->
                    <div class="col-span-full row-span-12">
                        <div class="mt-3">
                            <!--                        Saldo inicial-->
                            <fieldset class="field" >
                                <legend>Saldo Inicial</legend>
                                <Money
                                    @keyup="sumCoin"
                                    class="inputGeneral"
                                    v-model.number="form.opening_balance"
                                    v-bind="moneyConfig"/>
                                <InputError :message="form.errors.opening_balance"/>
                            </fieldset>
                        </div>
                        <div class="mt-3">
                            <fieldset
                                class="pt-3 field">
                                <legend>Resultado</legend>
                                <div>
                                    <span class="text-gray-50">
                                        Efectivo Total:
                                    </span>
                                    <div class=" bg-white px-2 py-1 rounded-md">
                                        {{getMoney(form.total_coin)}}
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-block min-w-[10rem] text-gray-50" >
                                        Total Otros Ingresos:
                                    </span>
                                    <div class=" bg-white px-2 py-1 rounded-md">
                                        {{getMoney(form.total_other_coin)}}
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-block min-w-[10rem] text-gray-50" >
                                        Total Egresos:
                                    </span>
                                    <div class=" bg-white px-2 py-1 rounded-md">
                                        {{getMoney(form.total_expenses)}}
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-block min-w-[10rem] text-gray-50" >
                                        Total Neto:
                                    </span>
                                    <div class=" bg-white px-2 py-1 rounded-md">
                                        {{getMoney(form.total_neto)}}
                                    </div>
                                </div>
                                <div>
                                    <span class="inline-block min-w-[10rem] text-gray-50" >
                                        Diferencia:
                                    </span>
                                    <div class=" bg-white px-2 py-1 rounded-md">
                                        {{getMoney(form.diff)}}
                                    </div>
                                </div>

                            </fieldset>


                            <div
                                v-if="Object.keys(form.errors).length > 0">
                                <ol >
                                    <li v-for="error in form.errors" :key="error"
                                        class="text-red-500">
                                        {{error}}
                                    </li>
                                </ol>
                            </div>
                        </div>

<!--                        Boton para enviar los datos-->
                        <div class="mt-5 text-right col-span-full">
                            <PrimaryButton
                                :disabled="processing">
                                Imprimir
                            </PrimaryButton>
                        </div>

                    </div>

                </div>
            </form>



            <ShowPdf
                @send-error="getErrorPdf"
                v-if="showPdf"
                :pdf="pdfUrl">
            </ShowPdf>


        </div>

    </AppLayout>
</template>
