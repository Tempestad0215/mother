<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import LinkHeader from "@components/LinkHeader.vue";
import InputNumber from 'primevue/inputnumber';
import InputLabel from "@components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@components/InputError.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import Fieldset from 'primevue/fieldset';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import {getMoney} from "@/Global/Helpers";
import DatePicker from 'primevue/datepicker';
import FloatLabel from 'primevue/floatlabel';

/*
Datos del formulario
 */
const form = useForm({
    from: null, //Desde
    to: null, //Hasta
    coinFirst: 0, //1
    coinSecond: 0, //5
    coinThird: 0, //10
    coinFourth: 0, //25
    coinFifth: 0, //50
    coinSixth: 0, //100
    coinSeventh: 0, //200
    coinEighth: 0, //500
    coinNinth: 0, //1000,
    coinTenth: 0, //2000
    card: 0,//TArjeta
    transfer: 0, //Transferencia
    check: 0, //Cheque
    otherIncome: 0, //Otras monedas
    expenses: 0, //Gatos
    cashWithdrawals: 0, //Retiro de la caja
    refund: 0, //Devoluciones
    otherExpenses: 0,// otros Egresos
    openingBalance: 0, //Balance Inicial
    totalCoin: 0, //Balance total
    totalOtherMoney: 0, //Otros ingresos
    totalExpenses: 0, //Gatos totales
    diff: 0, //diferencia de dinero
    totalNeto: 0, //total netoregistrado
});


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
    let coinTotal:number = form.coinFirst  + multCoin(form.coinSecond, 5) + multCoin(form.coinThird, 10) + multCoin(form.coinFourth, 25) + multCoin(form.coinFifth, 50) + multCoin(form.coinSixth, 100) + multCoin(form.coinSeventh, 200) + multCoin(form.coinEighth, 500) + multCoin(form.coinNinth, 1000) + multCoin(form.coinTenth, 2000);


    //Obtener otros ingresos
    let otherIncome:number = form.card + form.transfer + form.check + form.otherIncome;

    //Egresos
    let expenses:number = form.expenses + form.cashWithdrawals + form.refund + form.otherExpenses;

    //Pasar los datos al formulario
    form.totalCoin = coinTotal; //total de las monedas
    form.totalOtherMoney = otherIncome; //total de otros ingresos
    form.totalExpenses = expenses; //Gastos
    form.totalNeto = coinTotal + otherIncome; // Total neto
    form.diff = (form.totalNeto - form.openingBalance ) - expenses; // Calculo de diferencia

}


/**
 * Enviar los datos
 */
const submit = () => {
    //enviar los datos
    form.post(route('sale.counterPost'));
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
        <div class="p-5">
            <h3 class="title text-center">
                Cuadre de Caja
            </h3>
            <form
                @submit.prevent="submit"
                class=" text-sm grid grid-cols-2 gap-3 mt-5 overflow-y-auto" >

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
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coinFirst"
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
                                    = {{ getMoney(multCoin(form.coinFirst, 1))}}
                                </InputGroupAddon>
                            </InputGroup>

                            <InputError :message="form.errors.coinFirst"/>
                        </div>
                        <div>
                            <InputLabel for="coinSecond" value="Moneda de 5" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coinSecond"
                                    inputId="coinSecond"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 5
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coinSecond, 5)) }}
                                </InputGroupAddon>
                            </InputGroup>

                            <InputError :message="form.errors.coinSecond"/>
                        </div>
                        <div>
                            <InputLabel for="coinThird" value="Moneda de 10" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coinThird"
                                    inputId="coinThird"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 10
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coinThird, 10)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coinThird"/>
                        </div>
                        <div>
                            <InputLabel for="coinFourth" value="Moneda de 25" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coinFourth"
                                    inputId="coinFourth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 25
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coinFourth, 25)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coinFourth"/>
                        </div>
                        <div>
                            <InputLabel for="coinFifth" value="Papeleta de 50" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coinFifth"
                                    inputId="coinFifth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 50
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coinFifth, 50)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coinFifth"/>
                        </div>
                        <div>
                            <InputLabel for="coinSixth" value="Papeleta de 100" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coinSixth"
                                    inputId="coinSixth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 100
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coinSixth, 100)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coinSixth"/>
                        </div>
                        <div>
                            <InputLabel for="coinSeventh" value="Papeleta de 200" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coinSeventh"
                                    inputId="coinSeventh"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 200
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coinSeventh, 200)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coinSeventh"/>
                        </div>
                        <div>
                            <InputLabel for="coinEighth" value="Papeleta de 500" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coinEighth"
                                    inputId="coinEighth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 500
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coinEighth, 500)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coinEighth"/>
                        </div>
                        <div>
                            <InputLabel for="coinNinth" value="Papeleta de 1,000" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coinNinth"
                                    inputId="coinNinth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 1000
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coinNinth, 1000)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coinNinth"/>
                        </div>
                        <div>
                            <InputLabel for="coinTenth" value="Papeleta de 2,000" />
                            <InputGroup>
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.coinTenth"
                                    inputId="coinTenth"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputGroupAddon class="w-[5rem]" >
                                    x 2000
                                </InputGroupAddon>
                                <InputGroupAddon class="w-[12rem]">
                                    = {{getMoney(multCoin(form.coinTenth, 2000)) }}
                                </InputGroupAddon>
                            </InputGroup>
                            <InputError :message="form.errors.coinTenth"/>
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
                                v-model="form.otherIncome"
                                inputId="locale-us"
                                locale="en-US"
                                :allow-empty="false"
                                :max-fraction-digits="2"
                                :minFractionDigits="2"
                                fluid />
                            <InputError :message="form.errors.otherIncome"/>
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
                                for="cashWithdrawals"
                                value="Retiro de Caja"/>
                            <InputNumber
                                @valueChange="sumCoin"
                                v-model="form.cashWithdrawals"
                                inputId="locale-us"
                                locale="en-US"
                                :allow-empty="false"
                                :max-fraction-digits="2"
                                :minFractionDigits="2"
                                fluid />
                            <InputError :message="form.errors.cashWithdrawals"/>
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
                                for="otherExpenses"
                                value="Otros"/>
                            <InputNumber
                                @valueChange="sumCoin"
                                v-model="form.otherExpenses"
                                inputId="otherExpenses"
                                locale="en-US"
                                :allow-empty="false"
                                :max-fraction-digits="2"
                                :minFractionDigits="2"
                                fluid />
                            <InputError :message="form.errors.otherExpenses"/>
                        </div>
                    </Fieldset>


<!--                    Totales de la ventnaa-->
                    <div class="col-span-full row-span-12">
                        <div class="mt-3">
                            <!--                        Saldo inicial-->
                            <Fieldset legend="Saldo Inicial" >
                                <InputNumber
                                    @valueChange="sumCoin"
                                    v-model="form.openingBalance"
                                    inputId="locale-us"
                                    locale="en-US"
                                    :allow-empty="false"
                                    :max-fraction-digits="2"
                                    :minFractionDigits="2"
                                    fluid />
                                <InputError :message="form.errors.openingBalance"/>
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
                                        {{getMoney(form.totalCoin)}}
                                    </span>
                                </p>
                                <p>
                                    <span class="inline-block min-w-[10rem]" >
                                        Total Otros Ingresos:
                                    </span>
                                        <span>
                                        {{getMoney(form.totalOtherMoney)}}
                                    </span>
                                </p>
                                <p>
                                    <span class="inline-block min-w-[10rem]" >
                                        Total Egresos:
                                    </span>

                                        <span>
                                        {{getMoney(form.totalExpenses)}}
                                    </span>
                                </p>
                                <p>
                                    <span class="inline-block min-w-[10rem]" >
                                        Total Neto:
                                    </span>

                                    <span>
                                        {{getMoney(form.totalNeto)}}
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
        </div>

    </AppLayout>
</template>
