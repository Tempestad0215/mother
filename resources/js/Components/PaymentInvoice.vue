<script setup lang="ts">

import {typePaymentData} from "@/Global/ShareData";
import {getMoney, moneyConfig} from "@/Global/Helpers";
import TextInput from "@components/TextInput.vue";
import InputError from "@components/InputError.vue";
import InputLabel from "@components/InputLabel.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {InertiaForm} from "@inertiajs/vue3";
import {creditNotesSaleI} from "@/Interfaces/Sale";
import axios from "axios";
import {Money} from "v-money3";
import {onMounted} from "vue";



const propsW = defineProps<{
    form: InertiaForm<any>,
}>();


onMounted(()=>{
    console.log(typeof  creditNotes);
})


//Valores para sincronizar a la vez
const typePayment = defineModel<string>('typePayment');
const creditNotes = defineModel<creditNotesSaleI[]>('creditNotes',{
    default: () => []
});
const creditNote = defineModel<string>('creditNote',{
    default: ""
});


//Emitir los eventos
const emit = defineEmits<{
    (e: 'amountCreditNote'):void;
    (e: 'returnedBlur'):void;
    (e: 'returned'):void;
    (e: 'senData'):void
}>()



/*
 * Buscar la notas de credito para pagar la factura
 */
const getCreditNote = async () => {

    //Si no hay suficiente caracateres
    if (creditNote?.value.length < 5) {
        propsW.form.setError('credit_notes_value', 'Por Favor, Introduzca Valores Valido');
        return false;
    }

    //Verificar si ya esta en positivo no puede colocar nota de credito
    if (propsW.form.returned > 0)
    {
        propsW.form.setError('credit_notes_value','Existe Suficiente Balance Para Cerrar La Cuenta');
        return false;
    }

    //Verificar si exsite alguna igual
    const exist:boolean = creditNotes.value.some((el) => el.code == creditNote.value || el.ncf == creditNote.value);

    //Verificar si existe la misma nota de credito
    if (exist)
    {
        propsW.form.setError('credit_notes_value','Esta Nota De Credito, Esta Agregada');

    }else{
        //Buscar la nota de credito
        const {data} = await axios.get(route('credit-note.get',{code: creditNote.value}));

        //Verifciar los datos
        if (data.hasOwnProperty('code'))
        {
            //Pasar los datos al formulario
            creditNotes.value.push(data);
            //Calcular los datos
            emit('amountCreditNote')
            //Limpiar los errores
            propsW.form.clearErrors('credit_notes_value');
            //Limpiar el campo para agreagr otros
            propsW.form.reset('credit_notes_value');

        }else{
            //Poner el mensaje de error
            propsW.form.setError('credit_notes_value',data.error);
        }
    }

}


/*
 * Eliminar la nota de credito
 */
const deleteCreditNote = (index:number) => {
    //Eliminar solo el dato seleccionado
    propsW.form.credit_notes.splice(index, 1);
    //Realizar el calculo
    emit('amountCreditNote');
}




</script>

<template>
    <!--Datos de la ventana-->
    <div
        class="bg-gray-200 p-5 rounded-md min-w-[40rem]  max-w-[60px]  h-fit mx-auto">
        <h3 class="text-2xl text-center">
            Datos de pagos
        </h3>

        <!--Tipo de apgo-->
        <div class="mt-3">
            <InputLabel
                for="typePayment"
                value="Tipo Pago" />
            <select
                autofocus
                v-model="typePayment"
                id="typePayment"
                class="rounded-md border-gray-300 w-full">
                <option
                    v-for="(item, index) in typePaymentData" :key="index"
                    :value="item.value">
                    {{item.name}}
                </option>
            </select>
        </div>
        <!--                        Aplicar nota de credito-->
        <div class=" mt-3">
            <InputLabel
                for="credit_notes"
                value="Notas Creditos"/>
            <div class="relative">
                <TextInput
                    class="w-[calc(100%-3rem)]"
                    v-model.trim="creditNote"
                    type="search"/>
                <i
                    @click="getCreditNote"
                    class=" bg-gray-400 hover:text-white duration-300 ease-linear rounded-md text-2xl p-2 absolute right-0 flex items-center inset-y-0 fa-solid fa-magnifying-glass"></i>
            </div>
            <!--                            Mensaje de error-->
            <InputError :message="form.errors.credit_notes_value"/>
            <!--                            Mostrar las notas de creditos asociada a esa venta-->
            <table class="table-auto w-full mt-3">
                <caption class="font-bold text-3xl">
                    Notas De Credito
                </caption>
                <thead class="text-left">
                    <tr class="border-2 border-b-gray-800">
                        <th>Cod./NCF</th>
                        <th>Disponible</th>
                        <th class="w-1/12" >Act</th>
                    </tr>
                </thead>
                <!--                                Cuerpod de los datos-->
                <tbody>
                <tr
                    v-for="(item, index) in creditNotes" :key="index">
                    <td>{{item.code}}</td>
                    <td>{{ getMoney(item.n_available)}}</td>
                    <td class="text-center w-1/12">
                        <i
                            @click="deleteCreditNote(index)"
                            class=" icon-efect fa-solid fa-trash"></i></td>
                </tr>
                <tr class=" border-t-2 border-gray-800">
                    <th>Total :</th>
                    <th colspan="2" >{{getMoney(form.credit_notes_amount)}}</th>
                </tr>
                </tbody>
            </table>
        </div>


        <!--                      Monto Recibido-->
        <div class="w-full mt-3">
            <InputLabel
                for="received"
                value="Recibido" />
            <money
                class="inputGeneral w-full"
                @blur="emit('returnedBlur')"
                v-model="form.received"
                v-bind="moneyConfig"  />

<!--            <TextInput-->

<!--                class="w-full"-->
<!--                type="number"-->
<!--                v-model.trim="form.received"/>-->
        </div>

<!--        Mensaje de error-->
        <div>
            <InputError :message="form.errors.info_sale"/>
        </div>

        <!--                        Datos pendiente para cobrar-->
        <div class="mt-3 text-3xl">
            Pendiente...: {{getMoney(form.pending)}}
        </div>
        <!--                        Datos Para devuelta-->
        <div class="mt-3 text-3xl">
            Devuelta......: {{getMoney(form.returned)}}
        </div>

        <!--                        Boton para cerrar la factura-->
        <div class="mt-3 text-right">
            <PrimaryButton
                :disabled="form.processing"
                @click="emit('senData')">
                Cerrar Factura
            </PrimaryButton>
        </div>

        <!--                        Mensaje de error-->
        <div class="mt-3">
            <InputError :message="form.errors.returned"/>
        </div>


    </div>
</template>

<style scoped>

</style>
