<script setup lang="ts">

import ContentBox from '@/Components/ContentBox.vue';
import HeaderBox from '@/Components/HeaderBox.vue';
import InputLabel from '@/Components/InputLabel.vue';
import NavLink from '@/Components/NavLink.vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';
import { successHttp } from '@/Global/Alert';
import type { clientI } from '@/Interfaces/ClientInterface';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import type { PropType } from 'vue';
import LinkHeader from "@components/LinkHeader.vue";


// Datos del backend
const props = defineProps({
    clients:{
        type: Object as PropType<clientI>,
        required: true
    }
})


// Formulario
const form = useForm({
    search:""
});


// Enviar los datos
const submit = () => {

    if(form.search.length < 2)
    {
        form.setError('search', 'El campo debes tener al menos 3 caracter');
        return false;
    }else{
        // Limpiar los errores
        form.clearErrors();
        // Enviar el formularios
        form.get(route('client.show'),{
            preserveScroll: true,
            preserveState: true
        });
    }

}

// Editar
const edit = (id:Number) => {

    // Hacer la peticion
    router.get(route('client.edit', id));
}

// Eliminar el resistros
const destroy = (id:Number) => {

    // Enviar los datos
    Swal.fire({
        title: "Desea eliminar este registro?",
        text: "Los cambios realizados son irreversible!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(route('client.destroy', id),{},{
                onSuccess:()=>{
                    // Mensaje de exito
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

    <!-- Contenido de todo -->
    <AppLayout>
        <!-- Cabecera de la ventana -->
        <template #header>
            <LinkHeader
                :href="route('client.create')">
                Registrar
            </LinkHeader>
            <LinkHeader
                :active="true"
                :href="route('client.create')">
                Mostrar
            </LinkHeader>
        </template>

        <!-- Contenido principal -->
        <div>
            <div class=" bg-gray-200 p-5 rounded-md">
                <div class=" mb-4">
                    <form
                        @submit.prevent="submit"
                        class=" max-w-sm">
                        <InputLabel
                            for="search"
                            value="Buscar" />
                        <TextInput
                            class=" w-full"
                            v-model="form.search"
                            type="text"/>

                    </form>
                </div>

                <table class=" table-auto w-full">
                    <thead class=" border-b-2 text-left">
                    <tr class=" border-b-2">
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Telefono</th>
                        <th>Atc</th>

                    </tr>
                    </thead>

                    <!-- Contenido -->
                    <tbody>
                    <tr
                        class=" border-b odd:bg-gray-100"
                        v-for="(item, index) in props.clients?.data" :key="index" >
                        <td class=" px-2">
                            {{ item.id }}
                        </td>
                        <td class=" px-2">
                            {{item.name}}
                        </td>
                        <td class=" px-2">
                            {{item.email ? item.email : 'N/A'}}
                        </td>
                        <td class=" px-2">
                            {{ item.phone }}
                        </td>
                        <!-- Botones -->
                        <td class="text-xl space-x-5 w-[100px]">
                            <i
                                @click="edit(item.id)"
                                class=" icon-efect fa-solid fa-pen-to-square"></i>
                            <i
                                @click="destroy(item.id)"
                                class=" icon-efect fa-solid fa-trash"></i>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <!-- PAginacion de todo -->
                <Pagination
                    :current-page="props.clients.current_page"
                    :total-page="props.clients.to"
                    :prev="props.clients.prev_page_url ? props.clients.prev_page_url : '' "
                    :next="props.clients.next_page_url ? props.clients.next_page_url : '' " />
            </div>

        </div>
    </AppLayout>


</template>
