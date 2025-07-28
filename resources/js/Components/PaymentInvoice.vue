<script setup lang="ts">
import {typePaymentData} from "@/Global/ShareData";
import {getMoney, moneyConfig} from "@/Global/Helpers";
import TextInput from "@components/TextInput.vue";
import InputLabel from "@components/InputLabel.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import axios from "axios";
import {inject, onMounted} from "vue";
import {Money} from "v-money3";
import {saleKey} from "@/utils/keys";
import ErrorComponent from "@components/ErrorComponent.vue";




onMounted(()=>{
});


const form = inject(saleKey)!

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
        form.setError('credit_notes_value', 'Por Favor, Introduzca Valores Valido');
        return false;
    }

    //Verificar si ya esta en positivo no puede colocar nota de credito
    if (form.returned > 0)
    {
        form.setError('credit_notes_value','Existe Suficiente Balance Para Cerrar La Cuenta');
        return false;
    }

    //Verificar si exsite alguna igual
    const exist:boolean = form.credit_notes.some((el) => el.code == creditNote.value || el.ncf == creditNote.value);

    //Verificar si existe la misma nota de credito
    if (exist)
    {
        form.setError('credit_notes_value','Esta Nota De Credito, Esta Agregada');

    }else{
        //Buscar la nota de credito
        const {data} = await axios.get(route('credit-note.get',{code: creditNote.value}));

        //Verifciar los datos
        if (data.hasOwnProperty('code'))
        {
            //Pasar los datos al formulario
            form.credit_notes.push(data);
            //Calcular los datos
            emit('amountCreditNote')
            //Limpiar los errores
            form.clearErrors('credit_notes_value');
            //Limpiar el campo para agreagr otros
            form.reset('credit_notes_value');

        }else{
            //Poner el mensaje de error
            form.setError('credit_notes_value',data.error);
        }
    }

}


/*
 * Eliminar la nota de credito
 */
const deleteCreditNote = (index:number) => {
    //Eliminar solo el dato seleccionado
    form.credit_notes.splice(index, 1);
    //Realizar el calculo
    emit('amountCreditNote');
}




</script>

<template>
    <!--Datos de la ventana-->
    <div
        class="fondo p-5 rounded-md min-w-[30rem]  max-w-[50rem]  h-fit mx-auto">
        <h3 class="text-2xl text-center">
            Datos de pagos
        </h3>

        <div class="flex items-center gap-3">
            <!--Tipo de apgo-->
            <div class="flex-1">
                <InputLabel
                    for="typePayment"
                    value="Tipo Pago" />
                <select
                    autofocus
                    v-model="form.type_payment"
                    id="typePayment"
                    class="inputGeneral py-1 w-full">
                    <option
                        v-for="(item, index) in typePaymentData" :key="index"
                        :value="item.value">
                        {{item.name}}
                    </option>
                </select>
            </div>
            <div class="flex-1">
                <InputLabel
                    for="credit_notes"
                    value="Notas Creditos"/>
                <div class="relative">
                    <TextInput
                        class="w-full mr-10"
                        v-model.trim="creditNote"
                        type="search"/>
                    <i
                        @click="getCreditNote"
                        class="icon-efect absolute right-0 p-2 flex items-center inset-y-0 fa-solid fa-magnifying-glass"></i>
                </div>
            </div>
        </div>

        <!--                        Aplicar nota de credito-->
        <div class=" mt-3">

            <!--                            Mostrar las notas de creditos asociada a esa venta-->
            <table class="table-auto w-full mt-3 text-white">
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
                    v-for="(item, index) in form.credit_notes" :key="index">
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
            <Money
                class="inputGeneral text-3xl"
                @blur="emit('returnedBlur')"
                v-model="form.received"
                v-bind="moneyConfig"/>
        </div>

        <div class="text-gray-50">
            <!--                        Datos pendiente para cobrar-->
            <div class="mt-3 text-3xl text0">
                Pendiente...: {{getMoney(form.pending)}}
            </div>
            <!--                        Datos Para devuelta-->
            <div class="mt-3 text-3xl">
                Devuelta......: {{getMoney(form.returned)}}
            </div>
        </div>



        <!--                        Boton para cerrar la factura-->
        <div class="mt-3 text-right">
            <PrimaryButton
                :disabled="form.processing"
                @click="emit('senData')">
                Cerrar Factura
            </PrimaryButton>
        </div>
	    <ErrorComponent v-model:errors="form.errors" />

    </div>
</template>
