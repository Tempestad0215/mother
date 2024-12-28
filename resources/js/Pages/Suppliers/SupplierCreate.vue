<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import { successHttp } from '@/Global/Alert';
import AppLayout from '@layout/AppLayout.vue';
import TextInput from '@components/TextInput.vue';
import InputError from '@components/InputError.vue';
import ActionMessage from '@components/ActionMessage.vue';
import PrimaryButton from '@components/PrimaryButton.vue';
import TabLink from "@components/TabLink.vue";
import {supplierI} from "@/Interfaces/Supplier";
import {computed, onMounted} from "vue";
import {getMoney, moneyConfig} from "@/Global/Helpers";
import {Money} from "v-money3";
import ToggleButton from "@components/ToggleButton.vue";



const propsW = defineProps<{
    supplierEdit?: supplierI,
    update?: boolean
}>();

/*
Al momento de cargar
 */
onMounted(()=>{
    if (propsW.supplierEdit)
    {
        form.uuid = propsW.supplierEdit.uuid;
        form.contact = propsW.supplierEdit.contact ?? "";
        form.company_name = propsW.supplierEdit.company_name;
        form.phone = propsW.supplierEdit.phone ?? "";
        form.email = propsW.supplierEdit.email ?? "";
    }
});


/*
Formulario
 */
const form = useForm({
    uuid: "",
    contact:"",
    company_name:"",
    phone:"",
    email:"",
    type_payment:"contado",
    receive_email: false,
    is_recurring: false,
    payment_day: null,
    account_bank: "",
    comment: "",
    amount: 0,
    due_date: 0,
    late_fee: 0,
    consumed: 0
});



/*
Propiedades computada
 */
const balance = computed(()=>{
   return getMoney((form.amount - form.consumed))
});




/**
 *Enviar los datos
 */
const submit = () => {
    // Si es actualziar
    if(propsW.update)
    {
        form.patch(route('supplier.update', {supplier: form.uuid}),{
            onSuccess:()=>{
                successHttp('Datos actualizado correctamente');
            }
        });
    }else{
        // Enviar los datos
        form.post(route('supplier.store'),{
            onSuccess:()=>{
                successHttp('Datos registrado correctamente');
                form.reset();
            }
        });
    }
}

</script>



<template>
    <!-- Titulo -->
    <Head title="Cliente"/>

    <!-- Contenido -->
    <AppLayout>
        <template #header >
            <TabLink
                :active="true"
                :href="route('supplier.create')">
                Registrar
            </TabLink>
            <TabLink
                :href="route('supplier.show')">
                Mostrar
            </TabLink>


        </template>

        <!-- Formulario de registro -->
        <div class="max-w-[1100px] mx-auto max-h-[85vh] overflow-y-auto">

            <form
                class=" bg-blue-300 rounded-md p-5"
                @submit.prevent="submit">

                <h2 class=" text-2xl font-bold text-center mb-4">
                    {{ propsW.update ? 'Actualización' :  'Registro'}} de Suplidor
                </h2>





                <fieldset
                    class="field grid grid-cols-2 gap-3 ">
                    <legend>Info General</legend>
                    <div>
                        <InputLabel for="type_payment" value="Tipo Pago"/>
                        <select
                            v-model="form.type_payment"
                            class="inputGeneral py-0 w-full">
                            <option value="contado" >Contado</option>
                            <option value="anticipo" >Anticipo</option>
                            <option value="credito" >Credito</option>
                            <option value="tarjeta" >Tarjeta</option>
                            <option value="cheque" >Cheque</option>
                            <option value="transferencia" >Transferencia</option>
                            <option value="otros" >Otros</option>
                        </select>
                    </div>

                    <!-- Nombre de la empresa -->
                    <div class="">
                        <InputLabel
                            for="name"
                            value="Nombre de la empresa *"/>
                        <TextInput
                            name="name"
                            class=" w-full"
                            maxLength="75"
                            v-model="form.company_name"
                            placeholder="Nombre comercial"
                            type="text"/>

                        <!-- Error -->
                        <InputError :message="form.errors.company_name" />
                    </div>
                    <!-- Nombre -->
                    <div>
                        <InputLabel
                            for="officer"
                            value="Representante"/>
                        <TextInput
                            name="officer"
                            class=" w-full"
                            maxLength="75"
                            v-model="form.contact"
                            placeholder="Nombre completo"
                            type="text"/>
                        <!-- Error -->
                        <InputError :message="form.errors.contact" />
                    </div>

                    <!-- Telefono -->
                    <div class="">
                        <InputLabel
                            for="phone"
                            value="Teléfono"/>
                        <TextInput
                            class="w-full"
                            v-mask="['+# (###) ###-####','+## (###) ###-####']"
                            v-model="form.phone"
                            placeholder="+1 (###) ###-####"
                            name="phone"/>
                        <!-- Error -->
                        <InputError :message="form.errors.phone" />
                    </div>

                    <!-- correo -->
                    <div class="">
                        <InputLabel
                            for="phone"
                            value="Correo"/>
                        <TextInput
                            class=" w-full"
                            name="email"
                            maxLength="150"
                            placeholder="ejemplo@ejemplo.com"
                            v-model="form.email"
                            type="email"/>
                        <!-- Error -->
                        <InputError :message="form.errors.email" />
                    </div>

                    <!-- Cuenta de banco -->
                    <div class="">
                        <InputLabel
                            for="account_bank"
                            value="Cuenta De Banco"/>
                        <TextInput
                            class=" w-full"
                            name="account_bank"
                            maxLength="20"
                            placeholder="2256326598"
                            v-model="form.account_bank"
                            type="text"/>

                        <!-- Error -->
                        <InputError :message="form.errors.account_bank" />
                    </div>

<!--                    Informacion de pago-->
                    <div class="flex justify-between">
                        <div>
                            <ToggleButton
                                v-model="form.receive_email"
                                label="Rec. Correo"
                                on-label="SI"
                                off-label="NO"/>
                        </div>
                        <div>
                            <ToggleButton
                                v-model="form.is_recurring"
                                label="Pago Recurrente"
                                on-label="SI"
                                off-label="NO"/>
                        </div>

                        <div>
                            <InputLabel
                                for="payment_day"
                                value="Dia de pago"/>
                            <TextInput
                                name="payment_day"
                                type="number"
                                v-model="form.payment_day"/>
                        </div>

                    </div>


<!--                    Comentaio-->

                    <div>
                        <InputLabel for="note" value="Comentario" />
                        <TextInput
                            name="note"
                            class=" w-full"
                            v-model="form.comment"/>
                        <!-- Error -->
                        <InputError :message="form.errors.comment" />
                    </div>
                </fieldset>


<!--                Informacion de credito-->
                <fieldset
                    v-if="form.type_payment == 'anticipo' || form.type_payment == 'credito'"
                    class="field grid grid-cols-4 gap-3">
                    <legend>
                        Informacion de Pagos
                    </legend>
                    <div>
                        <InputLabel for="credit_limit" value="Limite de credito"/>
                        <Money
                            class="inputGeneral w-full"
                            v-bind="moneyConfig"
                            v-model="form.amount" />
                        <InputError :message="form.errors.amount"/>
                    </div>

                    <div>
                        <InputLabel for="credit_day" value="Dias para pagar"/>
                        <Money
                            class="inputGeneral w-full"
                            v-bind="moneyConfig"
                            v-model="form.due_date" />
                        <InputError :message="form.errors.due_date" />
                    </div>
                    <!--                        Balance-->
                    <div>
                        <InputLabel for="" value="Balance"/>
                        <span class="flex justify-center items-center bg-white px-3 rounded-md h-[2rem] " >
                            {{balance}}
                        </span>
                    </div>
                    <!--                        Consumido-->
                    <div>
                        <InputLabel for="" value="Consumido"/>
                        <span class="flex justify-center items-center bg-white px-3 rounded-md h-[2rem] " >
                            {{getMoney(form.consumed)}}
                        </span>
                    </div>
                </fieldset>


                <!-- Botones para enviar -->
                <div class="mt-4 flex justify-end items-center space-x-5">
                    <!-- Mensaje al crear -->
                    <ActionMessage :on="form.recentlySuccessful" >
                        {{ propsW.update ? ' !Actualizado' :  '! Registrado'}}
                    </ActionMessage>
                    <PrimaryButton
                        :disabled="form.processing">
                        {{ propsW.update ? 'Actualizar' :  'Registrar'}}
                    </PrimaryButton>

                </div>

            </form>

        </div>
    </AppLayout>
</template>
