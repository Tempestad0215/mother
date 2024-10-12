<script setup lang="ts">

import TextInput from "@components/TextInput.vue";
import InputLabel from "@components/InputLabel.vue";
import Pagination from "@components/Pagination.vue";
import {clientDataI, clientI} from "@/Interfaces/ClientInterface";
import {router, useForm, usePage} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";

/**
 * Datos de la ventana
 */
const page = usePage();

/**
 * Datos del back end
 */
const props = defineProps<{
    clients: clientI;
}>();

/**
 * Para emitir los eventos
 */
const emit = defineEmits<{
    (e: 'getData', item:clientDataI):void
}>()



/**
 * Formulario
 */
const form = useForm({
    search:""
});


/**
 * funciones
 */
// Enviar los datos
const submit = () => {

    // Limpiar los errores
    form.clearErrors();
    // Enviar el formularios
    form.get(`?search=${form.search}`,{
        preserveScroll: true,
        preserveState: true
    });


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
            router.patch(route('client.destroy', id), {}, {
                onSuccess: () => {
                    // Mensaje de exito
                    successHttp('Datos eliminado correctamente');
                }
            })
        }
    });
}



</script>

<template>
    <div class=" bg-gray-200 p-5 rounded-md overflow-y-auto">
        <div class=" mb-4 flex justify-between items-center ">
            <form
                @submit.prevent="submit"
                class=" w-[300px]">
                <InputLabel
                    for="search"
                    value="Buscar" />
                <TextInput
                    class=" w-full"
                    v-model="form.search"
                    type="text"/>

            </form>

            <h3 class="text-3xl font-bold text-gray-900">
                Clientes
            </h3>
        </div>

        <table class=" table-auto w-full">
            <thead class=" border-b-2 text-left">
                <tr class=" border-b-2 border-gray-800">
                    <th>Code</th>
                    <th>Nombre</th>
                    <th>Ced./Rnc./Pas.</th>
                    <th>Correo</th>
                    <th>Telefono</th>
                    <th>Atc</th>

                </tr>
            </thead>

            <!-- Contenido -->
            <tbody>
                <tr
                    class=" border-b odd:bg-gray-400"
                    v-for="(item, index) in props.clients?.data" :key="index" >
                    <td class=" px-2">
                        {{ item.code }}
                    </td>
                    <td class=" px-2">
                        {{item.name}}
                    </td>
                    <td class=" px-2">
                        {{item.personal_id}}
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
                            v-if="page.component !== 'Clients/Show'"
                            title="Seleccionar"
                            @click="emit('getData',item)"
                            class="fa-solid fa-circle-check"></i>
                        <i
                            v-if="page.component === 'Clients/Show'"
                            title="Editar"
                            @click="edit(item.id)"
                            class=" icon-efect fa-solid fa-pen-to-square"></i>
                        <i
                            v-if="page.component === 'Clients/Show'"
                            title="Eliminar"
                            @click="destroy(item.id)"
                            class=" icon-efect fa-solid fa-trash"></i>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- PAginacion -->
        <Pagination
            :current-page="props.clients.current_page"
            :total-page="props.clients.to"
            :prev="props.clients.prev_page_url ? props.clients.prev_page_url : '' "
            :next="props.clients.next_page_url ? props.clients.next_page_url : '' " />
    </div>
</template>

<style scoped>

</style>
