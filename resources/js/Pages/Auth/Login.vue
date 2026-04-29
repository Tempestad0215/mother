<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import {ref, Ref} from "vue";
import {useRoute} from "ziggy-js";
import {FloatLabel, InputText, Password, ToggleSwitch, Button, Image, Tag} from "primevue";


const route = useRoute();
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
    <div class=" h-screen ">
        <div v-if="status" class="mb-4 font-medium text-sm ">
            {{ status }}
        </div>
        <!--        formulario de inicio-->
        <form
            class="max-w-150 mx-auto fondo p-10 rounded-md flex-col items-center shadow-md "
            @submit.prevent="submit">
            <Image :src="`${urlImage}/logo.jpeg`" />

            <FloatLabel class="mt-5" variant="on">
                <InputText fluid v-model="form.email" />
                <label for="email">Correo Electronico</label>

            </FloatLabel>
            <div class="text-center">
                <Tag severity="danger" class="scale-75 text-center" v-if="form.errors.email" :value="form.errors.email" />

            </div>
            <FloatLabel class="mt-3" variant="on">
                <Password fluid toggleMask v-model="form.password" />
                <label for="passowrd">Contraseña</label>
            </FloatLabel>
            <div class="text-center">
                <Tag severity="danger" class="scale-75 text-center" v-if="form.errors.password" :value="form.errors.password" />

            </div>

            <div class="flex items-center mt-5 space-x-3">
                <ToggleSwitch id="remember" v-model="form.remember" />
                <label for="remember">Recuerdame</label>
            </div>


            <div class="flex items-center justify-end mt-4 space-x-3">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-50  rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Olvidó su contraseña?
                </Link>
                <Button :disabled="form.processing" label="Iniciar" icon="pi pi-send" type="submit"/>
            </div>
        </form>
    </div>



</template>
