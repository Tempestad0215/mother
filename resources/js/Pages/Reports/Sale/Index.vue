<script setup lang="ts">

import AppLayout from "@layout/AppLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import DateRange from "@components/DateRange.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import {onMounted} from "vue";
import {setHour} from "@/Global/Helpers";
import InputError from "@components/InputError.vue";


const propsW = defineProps<{
    data: Array<any>;
}>()

/*
Formulario
 */
const form = useForm({
    type:"",
    typePayment:"",
    from:"",
    to:"",

});


/*
Al momento de cargar
 */
onMounted(()=>{

    console.log(getParams().from);
    //Poner la hora
    form.from = setHour(1,0,0,0);
    form.to = setHour(12,0,0,0);

});


/*
Funciones
 */


const submit = () => {
    form.get(route('report-sale.index'),{
        preserveState: true,
    });
}


/**
 * Devoler los parametros
 */
const getParams = () => {
    //Tomar los datos del parametros
    let from = new URL(window.location.href)
    //Para tomar los parametros
    let params = new URLSearchParams(from.search);


    //Devolver los datos
    return {
        from: params.get("from"),
        to: params.get("to"),
    }


}



</script>

<template>
<!--    Titulo de la ventana-->
    <Head title="Reporte Ventas"/>


<!--    Contenido de la ventana-->
    <AppLayout>
        <div class="">
            <h3 class="title text-center">
                Reportes de Ventas
            </h3>
            <form
                @submit="submit"
                class="mt-5">

                <div class="flex items-end justify-between">
                    <!--                Rango dee fecha-->
                    <DateRange
                        v-model:from-value="form.from"
                        v-model:to-value="form.to"
                        />

<!--                    Tipo de pago-->
                    <div>
                        <InputLabel for="type_payment" value="Tipo de pago"/>
                        <!--                    Colocar filtrar por tipo de pago-->
                        <select
                            v-model="form.typePayment"
                            class="rounded-md border-gray-300">
                            <option value="" selected>Todo</option>
                            <option value="contado" >Contado</option>
                            <option value="credito">Credito</option>
                            <option value="cheque" >Cheque</option>
                            <option value="tranferencia">Tranferencia</option>
                            <option value="anticipo">Anticipo</option>
                        </select>
                        <InputError :message="form.errors.typePayment"/>
                    </div>

<!--                    Tipo de ventas-->
                    <div>
                        <InputLabel for="type" value="Tipo de pago"/>
                        <select
                            v-model="form.type"
                            class="rounded-md border-gray-300">
                            <option value="" selected>Todo</option>
                            <option value="contado" >Contado</option>
                        </select>
                        <InputError :message="form.errors.type"/>
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
                        <TextInput
                            class="w-full"
                            readonly/>
                    </div>
                    <!--Tipo de pago contado-->
                    <div>
                        <InputLabel for="productSold" value="Contado"/>
                        <TextInput
                            class="w-full"
                            readonly/>
                    </div>
                    <!--Tipo de pago tarjeta-->
                    <div>
                        <InputLabel for="productSold" value="Tarjeta"/>
                        <TextInput
                            class="w-full"
                            readonly/>
                    </div>
                    <!--Tipo de pago credito-->
                    <div>
                        <InputLabel for="productSold" value="Credito"/>
                        <TextInput
                            class="w-full"
                            readonly/>
                    </div>
                    <!--Tipo de pago en cheque-->
                    <div>
                        <InputLabel for="productSold" value="Cheque"/>
                        <TextInput
                            class="w-full"
                            readonly/>
                    </div>
                    <!-- Decuento Total Aplicado-->
                    <div>
                        <InputLabel for="productSold" value="Descuento Total"/>
                        <TextInput
                            class="w-full"
                            readonly/>
                    </div>
                    <!--Itbis Total vendido-->
                    <div>
                        <InputLabel for="productSold" value="ITBIS Total"/>
                        <TextInput
                            class="w-full"
                            readonly/>
                    </div>
                    <!--Balance total vendido en el rango de fecha-->
                    <div>
                        <InputLabel for="productSold" value="Balance Total"/>
                        <TextInput
                            class="w-full"
                            readonly/>
                    </div>
                </fieldset>


<!--                Listado de produtos vendido-->
                <table class="table-auto w-full mt-10">
                    <caption class="text-xl italic underline">
                        Listado de Ventas
                    </caption>
                    <thead class="border-2 border-collapse border-black">
                        <tr>
                            <th>Code</th>
                            <th>NCF</th>
                            <th>ITBIS</th>
                            <th>Descuento</th>
                            <th>Total</th>
                            <th>Tipo</th>
                            <th>Tipo Pago</th>

                        </tr>
                    </thead>

                </table>

            </form>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
