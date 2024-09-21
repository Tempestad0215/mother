<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@components/TextInput.vue';
import InputError from '@components/InputError.vue';
import PrimaryButton from '@components/PrimaryButton.vue';
import {computed, onMounted} from 'vue';
import {clienteEditI} from '@/Interfaces/ClientInterface';
import { successHttp } from '@/Global/Alert';
import LinkHeader from "@components/LinkHeader.vue";
import {getMoney} from "@/Global/Helpers";

/**
 * Props de la vantana
 */
const props = defineProps<{
    clientEdit?: clienteEditI,
    update?: boolean,
}> ();

/**
 * Al momento de cargar
 */
onMounted(()=>{

    //Verificar si existe datos para poner en el formulario
    if(props.clientEdit)
    {
        form.id = props.clientEdit.id;
        form.name = props.clientEdit.name;
        form.personal_id = props.clientEdit.personal_id ? props.clientEdit.personal_id : "";
        form.phone = props.clientEdit.phone ? props.clientEdit.phone : "";
        form.email = props.clientEdit.email ? props.clientEdit.email : "" ;
        form.address = props.clientEdit.address ? props.clientEdit.address : "";
        form.comment = props.clientEdit.comment.content;
        form.status = props.clientEdit.status;
        form.type = props.clientEdit.type;
    }
});

/**
 * Propiedades computada
 */

//Veriificar si es credito o contado
const isMandatory = computed(()=>{
    //Retorna true cuando es credito o anticipo
   if(form.type === "credito" || form.type === "anticipo")
   {
       return true;
   }

   //Retorna true cuando es a contado
   return false;
});




/**
 * DAtos del formulario
 */
const form = useForm({
    id:0,
    name:"",
    personal_id:"",
    phone:"",
    email:"",
    address:"",
    type: 'contado',
    credit_limit: "",
    credit_day:"",
    credit_balance:"",
    credit_expired:"",
    advance_amount:"",
    advance_date:"",
    advance_expire:"",
    advance_balance:"",
    status: false,
    comment:""
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
        });

    //Enviar los datos por post
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
            <LinkHeader
                :href="route('client.show')">
                Mostrar
            </LinkHeader>

        </template>

        <!-- Formulario de registro -->
        <div>
            <form
                class="bg-gray-200 rounded-md p-5 md:max-w-full mx-auto"
                @submit.prevent="submit">

<!--                Titulo del formulario-->
                <h2 class="col-span-full text-2xl font-bold text-center mb-4">
                    {{ props.update ? 'Actualización' :  'Registro'}} de cliente
                </h2>

<!--                Tipo de cliente-->
                <fieldset class="flex border-2 border-gray-400 p-5 rounded-md">
                    <legend>
                        Tipo de Cliente
                    </legend>
                    <div>
                        <input
                            class="peer hidden"
                            type="radio"
                            v-model="form.type"
                            value="contado"
                            name="cli_cash"
                            id="cli_cash">
                        <label
                            class=" border-2 px-2 py-1 rounded-md border-gray-400 peer-checked:bg-gray-800 peer-checked:text-white duration-300 "
                            for="cli_cash">
                            Contado
                        </label>

                    </div>
<!--                    <div class="ml-5">-->
<!--                        <input-->
<!--                            class="peer hidden"-->
<!--                            v-model="form.type"-->
<!--                            value="credito"-->
<!--                            type="radio"-->
<!--                            name="cli_credit"-->
<!--                            id="cli_credit">-->
<!--                        <label-->
<!--                            class=" border-2 px-2 py-1 rounded-md border-gray-400 peer-checked:bg-gray-800 peer-checked:text-white duration-300 "-->
<!--                            for="cli_credit">-->
<!--                            Credito-->
<!--                        </label>-->
<!--                    </div>-->
<!--                    <div class="ml-5">-->
<!--                        <input-->
<!--                            class="peer hidden"-->
<!--                            v-model="form.type"-->
<!--                            value="anticipo"-->
<!--                            type="radio"-->
<!--                            name="cli_advance"-->
<!--                            id="cli_advance">-->
<!--                        <label-->
<!--                            class=" border-2 px-2 py-1 rounded-md border-gray-400 peer-checked:bg-gray-800 peer-checked:text-white duration-300 "-->
<!--                            for="cli_advance">-->
<!--                            Anticipo-->
<!--                        </label>-->
<!--                    </div>-->
                    <InputError :message="form.errors.type"/>
                </fieldset>


<!--                Datos personales-->
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
                            value="Cedula / Pasaporte /RNC"/>
                        <TextInput
                            class=" w-full"
                            maxLength="75"
                            :required="isMandatory"
                            v-model="form.personal_id"
                            placeholder="Nombre completo"
                            type="text"/>

                        <!-- Error -->
                        <InputError :message="form.errors.personal_id" />
                    </div>

                    <!-- Telefono -->
                    <div class="">
                        <InputLabel
                            for="phone"
                            value="Teléfono"/>
                        <TextInput
                            class=" w-full"
                            name="phone"
                            :required="isMandatory"
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
                            :required="isMandatory"
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
                            :required="isMandatory"
                            placeholder="Puerto Plata"
                            v-model="form.address"
                            type="text"/>

                        <!-- Error -->
                        <InputError :message="form.errors.address" />
                    </div>
                </fieldset>
                <!-- Nombre -->



<!--             Datos de credito-->
                <div class="grid grid-cols-2 gap-4">
                    <fieldset
                        v-if="form.type === 'credito'"
                        class="border-2 border-gray-400 p-5 rounded-md grid grid-cols-2 gap-3">
                        <legend>
                            Datos Credito
                        </legend>
                        <div>
                            <InputLabel for="credit_limit" value="Limite de credito"/>
                            <TextInput
                                v-model="form.credit_limit"
                                class="w-full"
                                type="number" />
                            <InputError :message="form.errors.credit_limit"/>
                        </div>

                        <div>
                            <InputLabel for="credit_day" value="Dias para pagar"/>
                            <TextInput
                                v-model="form.credit_day"
                                class="w-full"
                                type="number" />
                            <InputError :message="form.errors.credit_day" />
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


<!--                  Datos de anticipo  -->
                    <fieldset
                        v-if="form.type === 'anticipo'"
                        class="border-2 border-gray-400 p-5 rounded-md grid grid-cols-2 gap-3">
                        <legend>
                            Datos Anticipo
                        </legend>
                        <div>
                            <InputLabel for="advance_amount" value="Cantidad"/>
                            <TextInput
                                v-model="form.advance_amount"
                                class="w-full"
                                type="number" />
                            <InputError :message="form.errors.advance_amount" />
                        </div>
                        <div>
                            <InputLabel for="advance_date" value="Fecha"/>
                            <TextInput
                                v-model="form.advance_date"
                                class="w-full"
                                type="date" />
                            <InputError :message="form.errors.advance_date" />
                        </div>
                        <div>
                            <InputLabel for="advance_expire" value="Fecha de vencimiento"/>
                            <TextInput
                                v-model="form.advance_expire"
                                type="date" />
                            <InputError :message="form.errors.advance_expire" />
                        </div>
                        <div>
                            <InputLabel for="advance_balance" value="Balance Disponible"/>
                            <p>
                                {{getMoney(12530.12)}}
                            </p>
                            <InputError />
                        </div>

                    </fieldset>

                </div>



                <!-- Datos de comentario y  -->
                <div class="flex mt-5">
                    <fieldset class=" border-2 border-gray-400 rounded-md p-2 flex items-center">
                        <legend>
                            Comentario
                        </legend>
                        <textarea
                            maxlength="255"
                            v-model="form.comment"
                            rows="5"
                            cols="50"
                            class="border-gray-200 rounded-md max-w-[400px] min-h-[150px] max-h-[200px]"/>
                        <InputError :message="form.errors.comment" />
                    </fieldset>
                    <div>

                    </div>

                    <!--                Estatus del cliente    -->
                    <fieldset class="ml-3 p-3 border-2 border-gray-400 rounded-md flex items-center">
                        <legend>
                            Estado
                        </legend>
                        <div>
                            <input
                                class="peer hidden"
                                v-model="form.status"
                                :value="false"
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
                                v-model="form.status"
                                :value="true"
                                type="radio"
                                name="cli_disabled"
                                id="cli_disabled">
                            <label
                                class=" border-2 px-2 py-1 rounded-md border-gray-400 peer-checked:bg-gray-800 peer-checked:text-white duration-300 "
                                for="cli_disabled">
                                Desactivado
                            </label>
                        </div>
                        <InputError :message="form.errors.status" />
                    </fieldset>



                    <!-- Botones para enviar -->
                    <div class="flex flex-1 justify-end items-center space-x-5">
                        <PrimaryButton
                            :disabled="form.processing">
                            {{ props.update ? 'Actualizar' :  'Registrar'}}
                        </PrimaryButton>

                    </div>
                </div>



            </form>

        </div>
    </AppLayout>
</template>
