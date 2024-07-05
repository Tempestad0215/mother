<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    contact:"",
    company_name:"",
    phone:"",
    email:"",
});

</script>


<template>
    <div class=" bg-gray-200 p-5 w-2/5 rounded-md">
        <form class="" >
            <h3 class="text-2xl font-bold text-center">
                Registro de Proveedores
            </h3>

            <div class=" mt-4">
                <InputLabel
                    for="company_name"
                    value="Contacto"/>
                <TextInput
                    class="w-full"
                    v-model="form.company_name"
                    placeholder="Empresa"
                    />
                <InputError :message="form.errors.company_name"/>
            </div>

            <div class="mt-4">
                <InputLabel
                    for="company_name"
                    value="Empresa *"/>
                <TextInput
                    class="w-full"
                    v-model="form.contact"
                    placeholder="ejemplo SRL"
                    />
                <InputError :message="form.errors.contact"/>
            </div>

            <div class=" mt-4">
                <InputLabel
                    for="phone"
                    value="Teléfono"/>
                <TextInput
                    class="w-full"
                    v-model="form.phone"
                    v-mask="'(###) ###-####'"
                    placeholder="(424) 245-45432"
                    />
                <InputError :message="form.errors.phone"/>
            </div>

            <div class=" mt-4">
                <InputLabel
                    for="email"
                    value="Correo"/>
                <TextInput
                    class="w-full"
                    v-model="form.email"
                    placeholder="example@example.com"
                    />
                <InputError :message="form.errors.email"/>
            </div>

            <div class="mt-4 text-right">
                <PrimaryButton
                    :disabled="form.processing">
                    Registrar
                </PrimaryButton>
            </div>
        </form>
    </div>

</template>
