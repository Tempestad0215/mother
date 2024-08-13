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

// Al momento de cargar
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




const form = useForm({
    id:0,
    name:"",
    phone:"",
    email:"",
    address:""
});



// funciones
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
                class="grid grid-cols-2 gap-3 bg-gray-200 rounded-md p-5 md:max-w-full mx-auto"
                @submit.prevent="submit">

                <h2 class="col-span-full text-2xl font-bold text-center mb-4">
                    {{ props.update ? 'Actualización' :  'Registro'}} de cliente
                </h2>

                <!-- Nombre -->
                <div>
                    <InputLabel
                        for="name"
                        value="Nombre *"/>
                    <TextInput
                        class=" w-full"
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
                        placeholder="ejemplo@ejemplo.com"
                        v-model="form.email"
                        type="email"/>

                    <!-- Error -->
                    <InputError :message="form.errors.email" />
                </div>

                <!-- direccion -->
                <div class="">
                    <InputLabel
                        for="phone"
                        value="Dirección"/>
                    <TextInput
                        class=" w-full"
                        name="address"
                        placeholder="Puerto Plata"
                        v-model="form.address"
                        type="text"/>

                    <!-- Error -->
                    <InputError :message="form.errors.address" />
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
