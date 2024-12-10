<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import { successHttp } from '@/Global/Alert';
import AppLayout from '@layout/AppLayout.vue';
import TextInput from '@components/TextInput.vue';
import InputError from '@components/InputError.vue';
import ActionMessage from '@components/ActionMessage.vue';
import PrimaryButton from '@components/PrimaryButton.vue';
import FormSearch from "@components/FormSearch.vue";
import {supplierI, supplierPaginationI} from "@/Interfaces/Supplier";
import Pagination from "@components/Pagination.vue";
import Swal from "sweetalert2";
import {paginationJoin} from "@/Global/Helpers";
import TabLink from "@components/TabLink.vue";




/*
Propiedades de la ventana
 */
const props = defineProps<{
    suppliers: supplierPaginationI
}>();


/*
Formulario
 */
const form = useForm({
    id:0,
    contact:"",
    company_name:"",
    phone:"",
    email:"",
    update: false,
});

/**
 * Formaulario de busqueda
 */
const formSearch = useForm({
    search:"",
    perPage: 15
});


/**
 *Enviar los datos
 */
const submit = () => {

    // Si es actualziar
    if(form.update)
    {
        form.patch(route('supplier.update', {supplier: form.id}),{
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

/**
 *
 * @param item
 */
const edit = (item:supplierI) => {
    form.update = true;
    form.id = item.id;
    form.contact = item.contact ? item.contact : "";
    form.company_name = item.company_name;
    form.phone = item.phone ? item.phone : "";
    form.email = item.email ? item.email : "";
}

/**
 *
 * @param item
 */
const destroy = (item:supplierI) => {
    form.id = item.id;

    Swal.fire({
        title: `Desea Eliminar el suplidor : ${item.company_name}?`,
        text: "Los cambios realizados son irreversible!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            form.patch(route('supplier.destroy',{supplier: form.id}),{
                onSuccess: ()=>{
                    successHttp('Datos eliminado correctamente');
                }
            })
        }
    });
}

/**
 *Buscar los datos
 */
const search = () => {
    formSearch.get('',{
        preserveScroll: true,
        preserveState: true
    });
}


</script>



<template>
    <!-- Titulo -->
    <Head title="Cliente"/>

    <!-- Contenido -->
    <AppLayout>
        <template #header >
            <TabLink
                :href="route('supplier.create')">
                Registrar
            </TabLink>
            <TabLink
                :href="route('supplier.create')">
                Registrar
            </TabLink>


        </template>

        <!-- Formulario de registro -->
        <div class="max-w-[1100px] mx-auto max-h-[85vh] overflow-y-auto">

            <form
                class=" bg-blue-300 rounded-md p-5"
                @submit.prevent="submit">

                <h2 class=" text-2xl font-bold text-center mb-4">
                    {{ form.update ? 'Actualización' :  'Registro'}} de Suplidor
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
                        {{ form.update ? ' !Actualizado' :  '! Registrado'}}
                    </ActionMessage>
                    <PrimaryButton
                        :disabled="form.processing">
                        {{ form.update ? 'Actualizar' :  'Registrar'}}
                    </PrimaryButton>

                </div>

            </form>



        </div>
    </AppLayout>
</template>
