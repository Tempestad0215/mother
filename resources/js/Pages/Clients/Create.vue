<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import HeaderBox from '@/Components/HeaderBox.vue';
import NavLink from '@/Components/NavLink.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@components/TextInput.vue';
import InputError from '@components/InputError.vue';


const form = useForm({
    name:"",
    phone:"",
    email:"",
    address:""
});



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
                <div>
                    <NavLink
                        :href="route('client.create')" >
                        Registrar
                    </NavLink>
                </div>

            </HeaderBox>
        </template>

        <!-- Formulario de registro -->
        <div>
            <form action="">
                <!-- Nombre -->
                <div>
                    <InputLabel
                        for="name"
                        value="Nombre"/>
                    <TextInput
                        v-model="form.name"
                        type="text"/>

                    <!-- Error -->
                    <InputError :message="form.errors.name" />
                </div>

                <!-- Telefono -->
                <div>
                    <InputLabel
                        for="phone"
                        value="Teléfono"/>
                    <TextInput
                        name="phone"
                        v-model="form.name"
                        v-mask="'(###) ###-####'"
                        type="text"/>

                    <!-- Error -->
                    <InputError :message="form.errors.name" />
                </div>

                <!-- Telefono -->
                <div>
                    <InputLabel
                        for="phone"
                        value="email"/>
                    <TextInput
                        name="email"
                        v-model="form.name"
                        type="email"/>

                    <!-- Error -->
                    <InputError :message="form.errors.name" />
                </div>
            </form>
        </div>
    </AppLayout>
</template>
