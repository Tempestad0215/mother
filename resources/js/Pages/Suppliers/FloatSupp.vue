<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { successHttp } from '@/Global/Alert';
import PrimaryButton from '@components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    contact:"",
    company_name:"",
    phone:"",
    email:"",
});


// Funciones
const submit = () =>{
    form.post(route('supplier.store'),{
        onSuccess:()=>{
            successHttp('Datos registrado correctamente');
            form.reset();

        },
        preserveScroll: true,
        preserveState: true
    });
}

</script>


<template>
    <div class=" bg-gray-200 p-5 w-full m-8 rounded-md">
        <form
            class=" grid grid-cols-2 gap-3"
            @submit.prevent="submit" >
            <h3 class=" col-span-full text-2xl font-bold text-center ">
                Registro de Proveedores
            </h3>

            <div class=" mt-4">
                <InputLabel
                    for="company_name"
                    value="Empresa * "/>
                <TextInput
                    class="w-full"
                    required
                    v-model="form.company_name"
                    placeholder="Empresa"
                    />
                <InputError :message="form.errors.company_name"/>
            </div>

            <div class="mt-4">
                <InputLabel
                    for="contact"
                    value="Contacto"/>
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

            <div class="mt-4 text-right col-span-full">
                <PrimaryButton
                    :disabled="form.processing">
                    Registrar
                </PrimaryButton>
            </div>
        </form>
    </div>

</template>
