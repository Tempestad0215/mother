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
import {onMounted} from "vue";



const propsW = defineProps<{
    supplierEdit: supplierI,
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
        })
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

                <div class=" grid grid-cols-2 gap-3 ">
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
                </div>

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
