<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@components/TextInput.vue';
import InputError from '@components/InputError.vue';
import PrimaryButton from '@components/PrimaryButton.vue';
import ActionMessage from '@components/ActionMessage.vue';
import { onMounted, PropType } from 'vue';
import {clienteEditI, clientI} from '@/Interfaces/ClientInterface';
import { successHttp } from '@/Global/Alert';
import LinkHeader from "@components/LinkHeader.vue";
import FloatShowCli from "@/Pages/Clients/FloatShowCli.vue";
import {getMoney} from "@/Global/Helpers";

/**
 * Props de la vantana
 */
const props = defineProps({
    clientEdit:{
        type: Object as PropType<clienteEditI>,
    },
    update: {
        type: Boolean,
        default: false
    },
    clients:{
        type: Object as PropType<clientI>,
        required: true
    }
});

/**
 * Al momento de cargar
 */
onMounted(()=>{
    if(props.clientEdit)
    {
        form.id = props.clientEdit.id;
        form.name = props.clientEdit.name;
        form.phone = props.clientEdit.phone;
        form.email = props.clientEdit.email ? props.clientEdit.email : '' ;
        form.address = props.clientEdit.address ? props.clientEdit.address : '';
    }
})


/**
 * DAtos del formulario
 */
const form = useForm({
    id:0,
    name:"",
    phone:"",
    email:"",
    address:""
});


/**
 * Funciones
 */
const submit = () => {

    // Si es actualziar
    if(props.update)
    {
        form.patch(route('client.update', form.id),{
            onSuccess:()=>{
                successHttp('Datos actualizado correctamente');
            }
        })
    }else{

        // Enviar los datos
        form.post(route('client.store'),{
            onSuccess:()=>{
                successHttp('Datos registrado correctamente');
                form.reset();
            }
        });
    }

}

</script>



<template>
    <!-- Contenido -->
    <AppLayout
        title="Cliente">
        <template #header >
            <LinkHeader
                :active="true"
                :href="route('client.create')">
                Registrar
            </LinkHeader>

        </template>

        <!-- Formulario de registro -->
        <div>
            <form
                class="bg-gray-200 rounded-md p-5 md:max-w-full mx-auto"
                @submit.prevent="submit">

                <h2 class="col-span-full text-2xl font-bold text-center mb-4">
                    {{ props.update ? 'Actualización' :  'Registro'}} de cliente
                </h2>

                <fieldset class="border-2 border-gray-400 p-5 rounded-md grid grid-cols-2 gap-3">
                    <legend>
                        Datos Personales
                    </legend>
                    <div>
                        <InputLabel
                            for="name"
                            value="Nombre Completo *"/>
                        <TextInput
                            class=" w-full"
                            maxLength="75"
                            v-model="form.name"
                            placeholder="Nombre completo"
                            type="text"/>

                        <!-- Error -->
                        <InputError :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel
                            for="personal_id"
                            value="Cedula / Pasaporte *"/>
                        <TextInput
                            class=" w-full"
                            maxLength="75"
                            v-model="form.name"
                            placeholder="Nombre completo"
                            type="text"/>

                        <!-- Error -->
                        <InputError :message="form.errors.name" />
                    </div>

                    <!-- Telefono -->
                    <div class="">
                        <InputLabel
                            for="phone"
                            value="Teléfono *"/>
                        <TextInput
                            class=" w-full"
                            name="phone"
                            v-model="form.phone"
                            placeholder="(849) 425-8568"
                            v-mask="'(###) ###-####'"
                            type="text"/>

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

                    <!-- direccion -->
                    <div class=" col-span-full">
                        <InputLabel
                            for="phone"
                            value="Dirección"/>
                        <TextInput
                            class=" w-full"
                            name="address"
                            maxLength="150"
                            placeholder="Puerto Plata"
                            v-model="form.address"
                            type="text"/>

                        <!-- Error -->
                        <InputError :message="form.errors.address" />
                    </div>
                </fieldset>
                <!-- Nombre -->

                <div class="grid grid-cols-2 gap-4">
                    <fieldset class="border-2 border-gray-400 p-5 rounded-md grid grid-cols-2 gap-3">
                        <legend>
                            Datos Credito
                        </legend>
                        <div>
                            <InputLabel for="credit_limit" value="Limite de credito"/>
                            <TextInput
                                class="w-full"
                                type="number" />
                            <InputError/>
                        </div>

                        <div>
                            <InputLabel for="credit_day" value="Dias para pagar"/>
                            <TextInput
                                class="w-full"
                                type="number" />
                            <InputError/>
                        </div>

                        <div>
                            <InputLabel for="curren_balance" value="Balance Actual"/>
                            <p>
                                {{getMoney(1253.26)}}
                            </p>
                            <InputError/>
                        </div>
                        <div>
                            <InputLabel for="credit_expired" value="Balance Vencido"/>
                            <p>
                                {{getMoney(0.0)}}
                            </p>
                            <InputError/>
                        </div>
                    </fieldset>


                    <fieldset class="border-2 border-gray-400 p-5 rounded-md grid grid-cols-2 gap-3">
                        <legend>
                            Datos Anticipo
                        </legend>
                        <div>
                            <InputLabel for="advance_amount" value="Cantidad"/>
                            <TextInput
                                class="w-full"
                                type="number" />
                            <InputError />
                        </div>
                        <div>
                            <InputLabel for="advance_date" value="Fecha"/>
                            <TextInput
                                class="w-full"
                                type="date" />
                            <InputError />
                        </div>
                        <div>
                            <InputLabel for="advance_expire" value="Fecha de vencimiento"/>
                            <TextInput type="date" />
                            <InputError />
                        </div>
                        <div>
                            <InputLabel for="advance_available" value="Balance Disponible"/>
                            <p>
                                {{getMoney(12530.12)}}
                            </p>
                            <InputError />
                        </div>

                    </fieldset>


                    <div class="flex py-4">
                        <div>
                            <input
                                class="peer hidden"
                                type="radio" name="cli_active" id="cli_active">
                                <label
                                    class=" border-2 px-2 py-1 rounded-md border-gray-400 peer-checked:bg-gray-800 peer-checked:text-white duration-300 "
                                    for="cli_active">
                                    Activado
                                </label>
                        </div>
                        <div class="pl-5">
                            <input
                                class="peer hidden"
                                type="radio" name="cli_disabled" id="cli_disabled">
                            <label
                                class=" border-2 px-2 py-1 rounded-md border-gray-400 peer-checked:bg-gray-800 peer-checked:text-white duration-300 "
                                for="cli_disabled">
                                Desactivado
                            </label>
                        </div>

                    </div>

                </div>




                <div>
                    <InputLabel for="comment" value="Comentario"/>
                    <textarea>

                    </textarea>
                </div>

                <!-- Botones para enviar -->
                <div class=" col-span-full mt-4 flex justify-end items-center space-x-5">
                    <!-- Mensaje al crear -->
                    <ActionMessage :on="form.recentlySuccessful" >
                        {{ props.update ? ' !Actualizado' :  '! Registrado'}}
                    </ActionMessage>
                    <PrimaryButton>
                        {{ props.update ? 'Actualizar' :  'Registrar'}}
                    </PrimaryButton>

                </div>

            </form>

            <div class="mt-5">
                <FloatShowCli
                    :clients="props.clients"/>

            </div>

        </div>
    </AppLayout>
</template>
