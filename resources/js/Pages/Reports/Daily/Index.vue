<script setup lang="ts">

import AppLayout from "@layout/AppLayout.vue";
import {Head} from "@inertiajs/vue3";
import {reportDayI} from "@/Interfaces/Report";
import {getMoney} from "@/Global/Helpers";


const propsW = defineProps<{
    report: reportDayI,
    from: string
    to: string,

}>()


</script>

<template>
<!--    Titulo de la ventana-->
    <Head title="Reporte Diario"/>
    <AppLayout>
        <div class="bg-gray-200 p-5 rounded-md">
            <fieldset>
                <legend class="text-xl font-bold">
                    Datos del Reporte
                </legend>
                <p>
                    <strong>
                        Fecha Inicial:
                    </strong>
                    <span>
                        {{propsW.from}}
                    </span>
                </p>

                <p>
                    <strong>
                        Fecha Inicial:
                    </strong>
                    <span>
                        {{propsW.to}}
                    </span>
                </p>


<!--                Sub Total-->
                <p>
                    <strong>
                        Sub Total :
                    </strong>
                    <span>
                        {{ getMoney(propsW.report.sub_total)}}
                    </span>
                </p>
<!--                Descuento-->
                <p>
                    <strong>
                        Descuento :
                    </strong>
                    <span>
                        {{ getMoney(propsW.report.discount)}}
                    </span>
                </p>

<!--                Impuesto-->
                <p>
                    <strong>
                        Impuesto :
                    </strong>
                    <span>
                        {{ getMoney(propsW.report.tax)}}
                    </span>
                </p>
<!--                Total del reporte-->
                <p>
                    <strong>
                        Total :
                    </strong>
                    <span>
                        {{ getMoney(propsW.report.amount)}}
                    </span>
                </p>

            </fieldset>
        </div>
    </AppLayout>
</template>

