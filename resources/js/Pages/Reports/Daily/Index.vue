<script setup lang="ts">

import AppLayout from "@layout/AppLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import {reportDayI} from "@/Interfaces/Report";
import {getMoney} from "@/Global/Helpers";


/*
Propiedades de la ventana
 */
const propsW = defineProps<{
    report: reportDayI,
    from?: string
    to?: string,
    title: string

}>();



/*
Funciones
 */

/**
 * Retornar hacia atras
 */
const back = ():void => {
    router.get(route('report.index'))
}



</script>

<template>
<!--    Titulo de la ventana-->
    <Head title="Reporte Diario"/>
    <AppLayout>
        <div class="bg-gray-200 p-5 rounded-md max-w-xl">
<!--            Boton para atras-->
            <i
                @click="back()"
                class=" icon-efect fa-solid fa-circle-arrow-left">
            </i>
<!--            Titulo de la ventana-->
            <h3 class="text-3xl font-bold text-center mb-4">
                {{ propsW.title }}
            </h3>


            <fieldset class="border-2 border-gray-400 p-5 rounded-md flex flex-col gap-3 ">
                <legend class="text-xl font-bold">
                    Datos del Reporte
                </legend>
                <p v-if="propsW.from != undefined">
                    <strong>
                        Fecha Inicial:
                    </strong>
                    <span class="bg-gray-50 px-3 py-1 rounded-md">
                        {{propsW.from}}
                    </span>
                </p>

                <p v-if="propsW.to != undefined">
                    <strong>
                        Fecha Final:
                    </strong>
                    <span class="bg-gray-50 px-3 py-1 rounded-md">
                        {{propsW.to}}
                    </span>
                </p>


<!--                Sub Total-->
                <p>
                    <strong>
                        Sub Total :
                    </strong>
                    <span class="bg-gray-50 px-3 py-1 rounded-md">
                        {{ getMoney(propsW.report.sub_total)}}
                    </span>
                </p>
<!--                Descuento-->
                <p>
                    <strong>
                        Descuento :
                    </strong>
                    <span class="bg-gray-50 px-3 py-1 rounded-md">
                        {{ getMoney(propsW.report.discount)}}
                    </span>
                </p>

<!--                Impuesto-->
                <p>
                    <strong>
                        Impuesto :
                    </strong>
                    <span class="bg-gray-50 px-3 py-1 rounded-md">
                        {{ getMoney(propsW.report.tax)}}
                    </span>
                </p>
<!--                Total del reporte-->
                <p>
                    <strong>
                        Total :
                    </strong>
                    <span class="bg-gray-50 px-3 py-1 rounded-md">
                        {{ getMoney(propsW.report.amount)}}
                    </span>
                </p>

            </fieldset>
        </div>
    </AppLayout>
</template>

