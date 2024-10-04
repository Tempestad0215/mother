<script setup lang="ts">

import AppLayout from "@layout/AppLayout.vue";
import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import axios from "axios";
import {onMounted, onUpdated, ref} from "vue";
import SecondaryButton from "@components/SecondaryButton.vue";
import Swal from "sweetalert2";
import InputError from "@components/InputError.vue";


const {errors} = usePage().props;

/**
 * Dato del formulario
 */
const form = useForm({
    from: "",
    to:"",
    general: ""

});


/**
 * Datos para rellenar
 */
const info = ref({
   tax: 0,
   sub_total: 0,
   amount: 0
});


/*
Al momento de cargar
 */
onMounted(()=>{

});


onUpdated(()=>{

});






/*
Funciones
 */


/**
 * Ventas pro rango de fecha
 */
const getDayly = () => {
    Swal.fire({
        title: "Reporte Diario X Fecha",
        width: 600,
        html: `
            <div class="flex gap-3" >
                <input type="datetime-local" id="daily-date-start" class="rounded-md border-gray-200" >
                <input type="datetime-local" id="daily-date-end" class="rounded-md border-gray-200">
            </div>

        `,
        showCancelButton: true,
        allowOutsideClick: false,
        confirmButtonText: "Buscar",
    }).then((result) => {
        if (result.isConfirmed)
        {
            //Tomar los datos de los input
            let from:string = (document.getElementById("daily-date-start") as HTMLInputElement).value;
            let to:string = (document.getElementById("daily-date-end") as HTMLInputElement).value;

            //Buscar los datos par el reporte
            router.get(route('report.day.date'),{
                from: from,
                to: to
            },{
                preserveScroll: true,
                preserveState: true,
                onError:() => {
                    //Limp
                    setTimeout(()=>{
                        form.clearErrors();
                    },5000);

                }
            });
        }
    });

    //Hora de inicio
    setHour("daily-date-start", 0,0,0,0);
    //Hora de Final
    setHour("daily-date-end", 23,59,59,0);
}

/**
 * Ventas del dia
 */
const getDay = () => {
    // Crear el reporte
    router.get(route('report.day'));
}



/**
 * Poner la hora por el id en inicio y final
 * @param id
 * @param h
 * @param m
 * @param s
 * @param ms
 */
const setHour = (id:string, h:number, m:number, s:number, ms:number) => {
    //Tomar la fecha del dia
    const now = new Date();

    //Fecha de inicio
    const date = new Date(now);
    //colocar la hora
    date.setHours(h,m,s,ms);

    //Formatear la fecha
    //Obtener el input para poner la fecha
    (document.getElementById(id) as HTMLInputElement).value = getDateInUtc4(date);
}


/**
 * Convertir los datos
 * @param date
 */
const getDateInUtc4 = (date:Date):string => {
    date.setHours(date.getHours() - 4);

    //Convertir
    const isoString = date.toISOString();
    //Devolver los datos
    return isoString.slice(0,16);
}



const stockLow = () => {
    //Buscar los producto menos a 10
    router.get(route('report.product.low'));
}





/**
 * Funciones
 */
const submit = () => {
    axios.post(route('report.getDailyByDate'),{
        from: form.from,
        to: form.to,
    })
        .then((res) => {
            info.value.tax = res.data.tax;
            info.value.sub_total = res.data.sub_total;
            info.value.amount = res.data.amount;
        })
        .catch(() => {

        })

}

</script>

<template>
    <Head title="Reportes"/>
    <AppLayout>
        <template #header >

        </template>

        <div class="bg-gray-200 rounded-md p-5">

            <fieldset class=" border-2 border-gray-800 rounded-md p-3 space-x-3">
                <legend class="px-3">
                    Reportes de Ventas
                </legend>

                <SecondaryButton
                    @click="getDayly" >
                    Ventas Diaria X Fecha
                </SecondaryButton>
                <SecondaryButton
                    @click="getDay" >
                    Ventas Del Dia
                </SecondaryButton>

<!--                <SecondaryButton >-->
<!--                    Ventas Semanal-->
<!--                </SecondaryButton>-->

<!--                <SecondaryButton >-->
<!--                    Ventas Mensual-->
<!--                </SecondaryButton>-->

<!--                <SecondaryButton >-->
<!--                    Ventas Anual-->
<!--                </SecondaryButton>-->
            </fieldset>


            <fieldset class=" border-2 border-gray-800 rounded-md p-3 space-x-3 mt-4">
                <legend class="px-3">
                    Reporte de Inventario
                </legend>

                <SecondaryButton
                    @click="stockLow">
                    Productos Alm. -10
                </SecondaryButton>


            </fieldset>

<!--            Monstar los errore-->
            <div>
                <InputError :message="errors.general"/>
            </div>

<!--            Datos del formulario-->
<!--            <form-->
<!--                @submit.prevent="submit()">-->
<!--                <fieldset class="flex">-->
<!--                    <legend>-->
<!--                        Reporte de venta-->
<!--                    </legend>-->

<!--                    <div>-->
<!--                        <InputLabel for="from" value="Desde"/>-->
<!--                        <TextInput-->
<!--                            type="datetime-local"-->
<!--                            v-model="form.from"/>-->
<!--                    </div>-->

<!--                    <div class="ml-5">-->
<!--                        <InputLabel for="to" value="Hasta"/>-->
<!--                        <TextInput-->
<!--                            type="datetime-local"-->
<!--                            v-model="form.to"/>-->
<!--                    </div>-->

<!--                </fieldset>-->

<!--                <fieldset-->
<!--                    v-if="info.tax > 0"-->
<!--                    class="flex justify-between">-->
<!--                    <legend>-->
<!--                        Resultado-->
<!--                    </legend>-->
<!--                    <div>-->
<!--                        <p class="font-bold">-->
<!--                            Itbis Total:-->
<!--                        </p>-->
<!--                        <span>-->
<!--                            {{getMoney(info.tax)}}-->
<!--                        </span>-->
<!--                    </div>-->
<!--                    <div>-->
<!--                        <p class="font-bold">-->
<!--                            Sub Total:-->
<!--                        </p>-->
<!--                        <span>-->
<!--                            {{getMoney(info.sub_total)}}-->
<!--                        </span>-->
<!--                    </div>-->
<!--                    <div>-->
<!--                        <p class="font-bold">-->
<!--                            Total:-->
<!--                        </p>-->
<!--                        <span>-->
<!--                            {{getMoney(info.amount)}}-->
<!--                        </span>-->
<!--                    </div>-->
<!--                </fieldset>-->

<!--                <div class="mt-5">-->
<!--                    <PrimaryButton>-->
<!--                        Reporte-->
<!--                    </PrimaryButton>-->
<!--                </div>-->

<!--            </form>-->

        </div>
    </AppLayout>

</template>

<style scoped>

</style>
