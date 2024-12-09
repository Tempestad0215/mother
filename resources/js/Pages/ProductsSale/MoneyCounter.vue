<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import LinkHeader from "@components/LinkHeader.vue";
import InputLabel from "@components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@components/InputError.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {getMoney} from "@/Global/Helpers";
import axios from "axios";
import {ref, Ref} from "vue";
import ShowPdf from "@components/ShowPdf.vue";
import TextInput from "@components/TextInput.vue";



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
 * Obtener el PDF
 */
const getPdf = () => {
    axios.get(route('sale.report.get'))
        .then((data)=>{
            if (data.status === 200)
            {
                //Para mostrar el pdf
                showPdf.value = true;
                pdfUrl.value = data.data.url;

                //Resetear las varibales
                setTimeout(()=>{
                    pdfUrl.value = "";
                    showPdf.value = false;
                },1500)

            }
        }).catch(()=>{

    });
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
    //enviar los datos
    form.post(route('sale.report.store'),{
        onSuccess: async () => {

            //Para mostrar el PDF
            getPdf()

            //Limpiar el formulario
            form.reset();
        },
        onError: async (error:any) => {

        }
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

            <LinkHeader
                :active="true"
                :href="route('sale.create')">
                Ventas
            </LinkHeader>

            <LinkHeader
                :href="route('sale.show')">
                Mostrar
            </LinkHeader>

<!--            <LinkHeader-->
<!--                :href="route('sale.counter')">-->
<!--                Recuento-->
<!--            </LinkHeader>-->

        </template>
<!--        Contenido de la ventana-->
        <div class="p-5 max-h-[90vh] overflow-y-auto">

            <h3 class="title text-center">
                Cuadre de Caja
            </h3>
            <form
                @submit.prevent="submit"
                class=" grid grid-cols-2 gap-3 mt-5 overflow-y-auto" >

                <div class="col-span-full flex gap-3 p-2">
                    <FloatLabel
                        variant="on">
                        <DatePicker
                            id="datepicker-24h"
                            inputId="fromDate"
                            v-model="form.from"
                            show-icon
                            variant="filled"
                            showTime
                            hourFormat="24"
                            fluid />
                        <label
                            for="fromDate">
                            Fecha Desde
                        </label>

                    </FloatLabel>

                    <FloatLabel
                        variant="on">
                        <DatePicker
                            id="datepicker-24h"
                            inputId="toDate"
                            v-model="form.to"
                            show-icon
                            variant="filled"
                            showTime
                            hourFormat="24"
                            fluid />
                        <label
                            for="toDate">
                            Fecha Hasta
                        </label>
                    </FloatLabel>

                </div>
<!--                Tipo de monedas-->
                <div >

                    <Fieldset legend="Conteo de Papeleta">
                        <div>
                            <InputLabel for="coinOne" value="Moneda de 1" />
                            <InputGroup>
                                <TextInput
                                    v-model="form.coin_first"
                                    inputId="coinOne"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 1
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{ getMoney(multCoin(form.coin_first, 1))}}
                                </InputGroupAddon>
                            </InputGroup>

                            <InputError :message="form.errors.coin_first"/>
                        </div>
                        <div>
                            <InputLabel for="coin_second" value="Moneda de 5" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coin_second"
                                    inputId="coin_second"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 5
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coin_second, 5)) }}
                                </InputGroupAddon>
                            </InputGroup>

                            <InputError :message="form.errors.coin_second"/>
                        </div>
                        <div>
                            <InputLabel for="coin_third" value="Moneda de 10" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coin_third"
                                    inputId="coin_third"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 10
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coin_third, 10)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coin_third"/>
                        </div>
                        <div>
                            <InputLabel for="coin_fourth" value="Moneda de 25" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coin_fourth"
                                    inputId="coin_fourth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 25
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coin_fourth, 25)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coin_fourth"/>
                        </div>
                        <div>
                            <InputLabel for="coin_fifth" value="Papeleta de 50" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coin_fifth"
                                    inputId="coin_fifth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 50
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coin_fifth, 50)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coin_fifth"/>
                        </div>
                        <div>
                            <InputLabel for="coin_sixth" value="Papeleta de 100" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coin_sixth"
                                    inputId="coin_sixth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 100
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coin_sixth, 100)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coin_sixth"/>
                        </div>
                        <div>
                            <InputLabel for="coin_seventh" value="Papeleta de 200" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coin_seventh"
                                    inputId="coin_seventh"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 200
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coin_seventh, 200)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coin_seventh"/>
                        </div>
                        <div>
                            <InputLabel for="coin_eighth" value="Papeleta de 500" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coin_eighth"
                                    inputId="coin_eighth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 500
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coin_eighth, 500)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coin_eighth"/>
                        </div>
                        <div>
                            <InputLabel for="coin_ninth" value="Papeleta de 1,000" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coin_ninth"
                                    inputId="coin_ninth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 1000
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coin_ninth, 1000)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coin_ninth"/>
                        </div>
                        <div>
                            <InputLabel for="coin_tenth" value="Papeleta de 2,000" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coin_tenth"
                                    inputId="coin_tenth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 2000
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coin_tenth, 2000)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coin_tenth"/>
                        </div>

                    </Fieldset>
                </div>
<!--                Datos variados-->
                <div class="grid grid-cols-2 gap-2">

                    <Fieldset legend="Otros Ingresos">

                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="tarjeta"
                                value="Tarjetas"/>
                            <InputNumber
                                @valueChange="sumCoin"
                                v-model="form.card"
                                inputId="locale-us"
                                locale="en-US"
                                :allow-empty="false"
                                :max-fraction-digits="2"
                                :minFractionDigits="2"
                                fluid />
                            <InputError :message="form.errors.card"/>
                        </div>

                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="Transferencia"
                                value="Transferencia"/>
                            <InputNumber
                                @valueChange="sumCoin"
                                v-model="form.transfer"
                                inputId="locale-us"
                                locale="en-US"
                                :allow-empty="false"
                                :max-fraction-digits="2"
                                :minFractionDigits="2"
                                fluid />
                            <InputError :message="form.errors.transfer"/>
                        </div>

                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="cheque"
                                value="Cheques"/>
                            <InputNumber
                                @valueChange="sumCoin"
                                v-model="form.check"
                                inputId="locale-us"
                                locale="en-US"
                                :allow-empty="false"
                                :max-fraction-digits="2"
                                :minFractionDigits="2"
                                fluid />
                            <InputError :message="form.errors.check"/>
                        </div>
                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="other"
                                value="Otras Monedas"/>
                            <InputNumber
                                @valueChange="sumCoin"
                                v-model="form.other_income"
                                inputId="locale-us"
                                locale="en-US"
                                :allow-empty="false"
                                :max-fraction-digits="2"
                                :minFractionDigits="2"
                                fluid />
                            <InputError :message="form.errors.other_income"/>
                        </div>
                    </Fieldset>

<!--                    Egresos-->
                    <Fieldset
                        class=""
                        legend="Egresos">

                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="expenses"
                                value="Gastos"/>
                            <InputNumber
                                @valueChange="sumCoin"
                                v-model="form.expenses"
                                inputId="locale-us"
                                locale="en-US"
                                :allow-empty="false"
                                :max-fraction-digits="2"
                                :minFractionDigits="2"
                                fluid />
                            <InputError :message="form.errors.expenses"/>
                        </div>

                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="cash_withdrawals"
                                value="Retiro de Caja"/>
                            <InputNumber
                                @valueChange="sumCoin"
                                v-model="form.cash_withdrawals"
                                inputId="locale-us"
                                locale="en-US"
                                :allow-empty="false"
                                :max-fraction-digits="2"
                                :minFractionDigits="2"
                                fluid />
                            <InputError :message="form.errors.cash_withdrawals"/>
                        </div>

                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="refund"
                                value="Devolucione"/>
                            <InputNumber
                                @valueChange="sumCoin"
                                v-model="form.refund"
                                inputId="refund"
                                locale="en-US"
                                :allow-empty="false"
                                :max-fraction-digits="2"
                                :minFractionDigits="2"
                                fluid />
                            <InputError :message="form.errors.refund"/>
                        </div>
                        <!--                        TRansferencia-->
                        <div>
                            <InputLabel
                                for="other_expenses"
                                value="Otros"/>
                            <InputNumber
                                @valueChange="sumCoin"
                                v-model="form.other_expenses"
                                inputId="other_expenses"
                                locale="en-US"
                                :allow-empty="false"
                                :max-fraction-digits="2"
                                :minFractionDigits="2"
                                fluid />
                            <InputError :message="form.errors.other_expenses"/>
                        </div>
                    </Fieldset>


<!--                    Totales de la ventnaa-->
                    <div class="col-span-full row-span-12">
                        <div class="mt-3">
                            <!--                        Saldo inicial-->
                            <Fieldset legend="Saldo Inicial" >
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.opening_balance"
                                    inputId="locale-us"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputError :message="form.errors.opening_balance"/>
                            </Fieldset>
                        </div>
                        <div class="mt-3">
                            <Fieldset
                                class="pt-3"
                                legend="Resultado" >
                                <p>
                                    <span class="inline-block min-w-[10rem]">
                                        Efectivo Total:
                                    </span>
                                        <span class="">
                                        {{getMoney(form.total_coin)}}
                                    </span>
                                </p>
                                <p>
                                    <span class="inline-block min-w-[10rem]" >
                                        Total Otros Ingresos:
                                    </span>
                                        <span>
                                        {{getMoney(form.total_other_coin)}}
                                    </span>
                                </p>
                                <p>
                                    <span class="inline-block min-w-[10rem]" >
                                        Total Egresos:
                                    </span>

                                        <span>
                                        {{getMoney(form.total_expenses)}}
                                    </span>
                                </p>
                                <p>
                                    <span class="inline-block min-w-[10rem]" >
                                        Total Neto:
                                    </span>

                                    <span>
                                        {{getMoney(form.total_neto)}}
                                    </span>
                                </p>
                                <p>
                                    <span class="inline-block min-w-[10rem]" >
                                        Diferencia:
                                    </span>

                                        <span>
                                        {{getMoney(form.diff)}}
                                    </span>
                                </p>

                            </Fieldset>

                            <div v-if="Object.keys(form.errors).length > 0">
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
                            <PrimaryButton>
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
