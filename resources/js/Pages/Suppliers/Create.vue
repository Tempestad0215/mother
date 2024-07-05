<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import HeaderBox from '@/Components/HeaderBox.vue';
import NavLink from '@/Components/NavLink.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { onMounted, PropType } from 'vue';
import { clienteEditI } from '@/Interfaces/ClientInterface';
import { successHttp } from '@/Global/Alert';
import AppLayout from '@layout/AppLayout.vue';
import ContentBox from '@components/ContentBox.vue';
import TextInput from '@components/TextInput.vue';
import InputError from '@components/InputError.vue';
import ActionMessage from '@components/ActionMessage.vue';
import PrimaryButton from '@components/PrimaryButton.vue';

const props = defineProps({
    clientEdit:{
        type: Object as PropType<clienteEditI>,
    },
    update: {
        type: Boolean,
        default: false
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
    }
})



const form = useForm({
    id:0,
    name:"",
    company_name:"",
    phone:"",
    email:"",
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
    <!-- Titulo -->
    <Head title="Cliente"/>

    <!-- Contenido -->
    <AppLayout>
        <template #header >
            <!-- Caja de la cabecera -->
            <HeaderBox>
                <h2>
                    Cliente
                </h2>

                <!-- Link de navegacion -->
                 <template #link>
                     <NavLink
                         :active="true"
                         :href="route('client.create')" >
                         Registrar
                     </NavLink>
                     <NavLink
                         :href="route('client.show')" >
                         Mostrar
                     </NavLink>
                 </template>
            </HeaderBox>
        </template>

        <!-- Formulario de registro -->
        <div>

            <!-- Contenido de todo -->
            <ContentBox>
                <form @submit.prevent="submit">

                    <h2 class=" text-2xl font-bold text-center mb-4">
                        {{ props.update ? 'Actualización' :  'Registro'}} de Suplidor
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

                    <!-- Nombre de la empresa -->
                    <div class="mt-4">
                        <InputLabel
                            for="name"
                            value="Nombre de la empresa *"/>
                        <TextInput
                            class=" w-full"
                            v-model="form.company_name"
                            placeholder="Nombre comercial"
                            type="text"/>

                        <!-- Error -->
                        <InputError :message="form.errors.company_name" />
                    </div>

                    <!-- Telefono -->
                    <div class="mt-4">
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
                    <div class="mt-4">
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


                    <!-- Botones para enviar -->
                    <div class="mt-4 flex justify-end items-center space-x-5">
                        <!-- Mensaje al crear -->
                        <ActionMessage :on="form.recentlySuccessful" >
                            {{ props.update ? ' !Actualizado' :  '! Registrado'}}
                        </ActionMessage>
                        <PrimaryButton>
                            {{ props.update ? 'Actualizar' :  'Registrar'}}
                        </PrimaryButton>

                    </div>

                </form>

            </ContentBox>
        </div>
    </AppLayout>
</template>
