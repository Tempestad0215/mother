<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import { PropType } from 'vue';
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

const props = defineProps({
    suppliers:{
        type: Object as PropType<supplierPaginationI>
    }

});



const form = useForm({
    id:0,
    contact:"",
    company_name:"",
    phone:"",
    email:"",
    update: false,
});



// funciones
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

const edit = (item:supplierI) => {
    form.update = true;
    form.id = item.id;
    form.contact = item.contact ? item.contact : "";
    form.company_name = item.company_name;
    form.phone = item.phone ? item.phone : "";
    form.email = item.email ? item.email : "";
}

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



</script>



<template>
    <!-- Titulo -->
    <Head title="Cliente"/>

    <!-- Contenido -->
    <AppLayout>
        <template #header >
<!--            <LinkHeader-->
<!--                :href="route('supplier.create')"-->
<!--                :active="true">-->
<!--                Registrar-->
<!--            </LinkHeader>-->
<!--            <LinkHeader-->
<!--                :href="route('supplier.show')"-->
<!--                :active="true">-->
<!--                Mostrar-->
<!--            </LinkHeader>-->


        </template>

        <!-- Formulario de registro -->
        <div>

            <form
                class=" bg-gray-200 rounded-md p-5"
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
                            for="name"
                            value="Representante"/>
                        <TextInput
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
                    <PrimaryButton>
                        {{ form.update ? 'Actualizar' :  'Registrar'}}
                    </PrimaryButton>

                </div>

            </form>


            <div class="bg-gray-200 mt-5 rounded-md p-5">
                <h3 class="text-2xl font-bold text-center">
                    Tabla de Suplidores
                </h3>
                <form>
                    <FormSearch
                        />
                </form>

                <table class=" text-left w-full table-auto">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Empresa</th>
                            <th>Representante</th>
                            <th>telefono</th>
                            <th>Correo</th>
                            <th>Atc</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="odd:bg-gray-400"
                            v-for="(item,index) in props.suppliers?.data" :key="index">
                            <td>{{item.id}}</td>
                            <td>{{item.company_name}}</td>
                            <td>{{item.contact ? item.contact : "N/A" }}</td>
                            <td>{{item.phone ? item.phone : 'N/A'}}</td>
                            <td>{{item.email ? item.email : 'N/A'}}</td>
                            <td class="w-16 space-x-3">
                                <i
                                    @click="edit(item)"
                                    title="Editar"
                                    class=" icon-efect fa-solid fa-pen-to-square"></i>
                                <i
                                    @click="destroy(item)"
                                    title="Eliminar"
                                    class=" icon-efect fa-solid fa-trash"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination
                    :next="props.suppliers?.next_page_url"
                    :prev="props.suppliers?.prev_page_url"
                    :total-page="props.suppliers?.to"
                    :current-page="props.suppliers?.current_page"
                    />

            </div>

        </div>
    </AppLayout>
</template>
