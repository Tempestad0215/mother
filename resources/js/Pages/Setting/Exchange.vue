<script setup lang="ts">
import PrimaryButton from "@components/PrimaryButton.vue";
import {onMounted, ref} from "vue";
import axios from "axios";
import {currencyDayI, currencyI, monthDayI} from "@/Interfaces/Currency";
import {getYear, moneyConfig, month} from "@/Global/Helpers";
import Swal from "sweetalert2";
import {useForm} from "@inertiajs/vue3";
import {Money} from "v-money3";


/**
 * datos de la ventana
 */
const currencies = ref<currencyI | null>(null);

/*
formulario
 */
const form = useForm({
    currentMonth: new Date().getMonth()  + 1 as number,
    currentMonthName: "",
    currentYear: 0,
    rate_info: [] as currencyDayI[],
});


onMounted(()=>{
    //Revisar la tasa del dia
    check();

    form.currentMonth = new Date().getMonth() + 1;
    //Pasar los datos al formulario
    form.currentYear = new Date().getFullYear();
    form.currentMonthName = month[form.currentMonth - 1 ].name;


    days();
});


/*
funciones
 */
const getPart = (value:string) => {
    return value.slice(0,3)+'...';
}


/**
 * verificar si existe la tasa del dia
 */
const check = ()=>{
    axios.get(route('currency.check'))
        .then((res)=>{
            currencies.value = res.data.currencies;
        }).catch((err)=>{
        Swal.fire({
            title: "Error",
            text: "Mensaje : "+err.message,
            icon: "question",
            timer: 3500,
        });
    });
}


const getMonthDay = (monthValue:number, year:number):monthDayI => {
    //Verificar si el mes es bisiesto
    if (year % 4 === 0 || (year % 100 !== 0 && year % 400 === 0)) {
        // Verifiar si el mes es febrero
        if (month[monthValue].name === 'Febrero')
        {
            return {
                name: month[monthValue].name,
                day: month[monthValue].day + 1,
            }
        }
    }
    return {
        name: month[monthValue].name,
        day: month[monthValue].day,
    }
}

/**
 * Obtener los dais
 */
const days = ()=>{
    //Obtener los dias por mes y año
    const monthDay:monthDayI = getMonthDay(form.currentMonth - 1, form.currentYear);

    form.reset('rate_info');

    // Agregar los numeros a los dias
    for(let i:number =1; i <= monthDay.day; i++){
        form.rate_info.push({
            day: i,
            eur: 0,
            us: 0
        });
    }



}

</script>

<template>
    <form class="bg-blue-300 p-5 rounded-md max-h-[40rem] overflow-y-auto">
        <div class="pt-3 flex justify-between">
            <select
                v-model="form.currentMonth"
                class="inputGeneral py-1"
                @change="days">
                <option
                    v-for="(item, index) in month"
                    :key="index"
                    :value="index + 1">
                    {{item.name}}
                </option>
            </select>


            <select
                v-model="form.currentYear"
                class="inputGeneral py-1"
                @change="days">
                <option
                    v-for="(item, index) in getYear()"
                    :key="index"
                    :value="item">
                    {{item}}
                </option>
            </select>
        </div>
        <table class="styleTable table-auto w-full mt-3">
            <thead>
                <tr>
                    <th >{{getPart(form.currentMonthName)}}</th>
                    <th>EUR</th>
                    <th>US</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(item, index) in form.rate_info"
                    :key="index">
                    <td>{{item.day}}</td>
                    <td>
                        <Money
                            class="inputGeneral bg-transparent border-0"
                            v-model="item.eur"
                            v-bind="moneyConfig"/>
                    </td>
                    <td>
                        <Money
                            class="inputGeneral bg-transparent border-0"
                            v-model="item.us"
                            v-bind="moneyConfig"/>
                    </td>
                </tr>
            </tbody>
        </table>
        <!--  Boton para enviar -->
        <div class="mt-3 text-right">
            <PrimaryButton>
                Actualizar
            </PrimaryButton>
        </div>

    </form>

</template>
