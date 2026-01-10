<script setup lang="ts">
import PrimaryButton from "@components/PrimaryButton.vue";
import {onMounted, ref} from "vue";
import axios from "axios";
import {currencyDayI, currencyI, monthDayI} from "@/Interfaces/CurrencyInterface";
import {getYear, moneyConfig, month} from "@/Global/Helpers";
import {useForm} from "@inertiajs/vue3";
import {Money} from "v-money3";
import {useRoute} from "ziggy-js";



const route = useRoute()
/**
 * datos de la ventana
 */
const currencies = ref<currencyI | null>(null);

/*
formulario
 */
const form = useForm({
    month: new Date().getMonth()  + 1 as number,
    mont_name: "",
    year: 0,
    rate_info: [] as currencyDayI[],
});

/*
Evento para emitir
 */
const emit = defineEmits<{
    (e:'closeWindow'):void
}>();


onMounted(()=>{
    form.month = new Date().getMonth() + 1;
    //Pasar los datos al formulario
    form.year = new Date().getFullYear();
    form.mont_name = month[form.month - 1 ].name;

    days();
});


/*
funciones
 */
const getPart = (value:string) => {
    return value.slice(0,3)+'...';
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
    const monthDay:monthDayI = getMonthDay(form.month - 1, form.year);

    //Para cargar los datos de la moneda de cambio
    axios.get(route('currency.get.exchange', {month: form.month , year: form.year}))
    .then((res) => {
        if(res.status === 200 && res.data.message === 'Datos Obtenido Correctamente')
        {

            form.rate_info = res.data.data.rate_info;
        }else{

            form.reset('rate_info');
            // Agregar los numeros a los dias
            for(let i:number =1; i <= monthDay.day; i++){
                form.rate_info.push({
                    day: i,
                    eur: 0,
                    usd: 0,
                    dop: 0,
                });
            }
        }
    })
    .catch(() => {

    });
}

/**
 * Guardar los datos
 */
const submit = () => {
    axios.post(route('currency.exchange.store'), form)
    .then((res)=>{

        //Si el mensaje es de existo
        if(res.status === 200)
        {
            //Mensaje de exito

            emit('closeWindow');
        }
    })
    .catch(()=>{
        // Mensaje de error

    });
}

</script>

<template>
    <form
        @submit.prevent="submit"
        class="bg-blue-300 p-5 rounded-md max-h-[40rem] overflow-y-auto">
        <div class="pt-3 flex justify-between">
            <select
                v-model="form.month"
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
                v-model="form.year"
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
                    <th >{{getPart(form.mont_name)}}</th>
                    <th>EUR</th>
                    <th>USD</th>
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
                            v-model="item.usd"
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
