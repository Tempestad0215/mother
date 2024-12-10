<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {ref, Ref} from "vue";


/**
 * Propiedades de la ventna
 */
defineProps<{
    canResetPassword: Boolean,
    status: String
}>();

/**
 * Datos de la ventana
 */
const urlImage:Ref<string> = ref(window.location.origin);


/**
 * Datos del formulario
 */
const form = useForm({
    email: '',
    password: '',
    remember: false,
});

/**
 * Enviar los datos
 */
const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

</script>





<template>
<!--    Titulo de la pagina-->
    <Head title="Inicio" />
    <div class=" h-screen ">
        <div v-if="status" class="mb-4 font-medium text-sm ">
            {{ status }}
        </div>
        <!--        formulario de inicio-->
        <form
            class="max-w-[600px] mx-auto bg-blue-500 p-10 rounded-md flex-col items-center shadow-md "
            @submit.prevent="submit">

            <img
                class="rounded-2xl mb-5"
                :src="`${urlImage}/logo.jpeg`" alt="Logo de la empresa" >


            <div>
                <InputLabel for="email" value="Correo" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Contraseña" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox v-model:checked="form.remember" name="remember" />
                    <span class="ms-2 text-sm ">Recuerdame</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm  rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Olvidó su contraseña?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Iniciar
                </PrimaryButton>
            </div>
        </form>
    </div>



</template>
