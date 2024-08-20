<script setup lang="ts">

import AppLayout from "@layout/AppLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import TextInput from "@components/TextInput.vue";
import InputLabel from "@components/InputLabel.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import axios from "axios";
import {ref} from "vue";
import {getMoney} from "@/Global/Helpers";


/**
 * Dato del formulario
 */
const form = useForm({
  from: "",
  to:""
});


/**
 * Datos para rellenar
 */
const info = ref({
   tax: 0,
   sub_total: 0,
   amount: 0
});





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

<!--            <fieldset class=" border-2 border-gray-800 rounded-md p-3 space-x-3">-->
<!--                <legend>-->
<!--                    Reportes de Ventas-->
<!--                </legend>-->

<!--                <SecondaryButton>-->
<!--                    Ventas Diaria X Fecha-->
<!--                </SecondaryButton>-->

<!--&lt;!&ndash;                <SecondaryButton >&ndash;&gt;-->
<!--&lt;!&ndash;                    Ventas Semanal&ndash;&gt;-->
<!--&lt;!&ndash;                </SecondaryButton>&ndash;&gt;-->

<!--&lt;!&ndash;                <SecondaryButton >&ndash;&gt;-->
<!--&lt;!&ndash;                    Ventas Mensual&ndash;&gt;-->
<!--&lt;!&ndash;                </SecondaryButton>&ndash;&gt;-->

<!--&lt;!&ndash;                <SecondaryButton >&ndash;&gt;-->
<!--&lt;!&ndash;                    Ventas Anual&ndash;&gt;-->
<!--&lt;!&ndash;                </SecondaryButton>&ndash;&gt;-->
<!--            </fieldset>-->


<!--            <fieldset class=" border-2 border-gray-800 rounded-md p-3 space-x-3 mt-4">-->
<!--                <legend>-->
<!--                    Reporte de Inventario-->
<!--                </legend>-->

<!--                <SecondaryButton >-->
<!--                    Productos Alm. -10-->
<!--                </SecondaryButton>-->


<!--            </fieldset>-->

            <form
                @submit.prevent="submit()">
                <fieldset class="flex">
                    <legend>
                        Reporte de venta
                    </legend>

                    <div>
                        <InputLabel for="from" value="Desde"/>
                        <TextInput
                            type="datetime-local"
                            v-model="form.from"/>
                    </div>

                    <div class="ml-5">
                        <InputLabel for="to" value="Hasta"/>
                        <TextInput
                            type="datetime-local"
                            v-model="form.to"/>
                    </div>

                </fieldset>

                <fieldset
                    v-if="info.tax > 0"
                    class="flex justify-between">
                    <legend>
                        Resultado
                    </legend>
                    <div>
                        <p class="font-bold">
                            Itbis Total:
                        </p>
                        <span>
                            {{getMoney(info.tax)}}
                        </span>
                    </div>
                    <div>
                        <p class="font-bold">
                            Sub Total:
                        </p>
                        <span>
                            {{getMoney(info.sub_total)}}
                        </span>
                    </div>
                    <div>
                        <p class="font-bold">
                            Total:
                        </p>
                        <span>
                            {{getMoney(info.amount)}}
                        </span>
                    </div>
                </fieldset>

                <div class="mt-5">
                    <PrimaryButton>
                        Reporte
                    </PrimaryButton>
                </div>

            </form>

        </div>
    </AppLayout>

</template>

<style scoped>

</style>
