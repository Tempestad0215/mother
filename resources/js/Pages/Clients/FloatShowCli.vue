<script setup lang="ts">
import Pagination from "@components/Pagination.vue";
import {clientBaseI, clientDataI} from "@/Interfaces/Client";
import {router, useForm, usePage} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";
import FormSearch from "@components/FormSearch.vue";
import {paginationJoin} from "@/Global/Helpers";


/**
 * Datos de la ventana
 */
const page = usePage();

/**
 * Datos del back end
 */
const props = defineProps<{
    clients: clientDataI;
}>();

/**
 * Para emitir los eventos
 */
const emit = defineEmits<{
    (e: 'getData', item:clientBaseI):void
}>();


/**
 * Formulario
 */
const form = useForm({
    search:"",
    perPage:30
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
const edit = (id:string) => {
    // Hacer la peticion
    router.get(route('client.edit', id));
}

// Eliminar el resistros
const destroy = (id:string) => {

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
    <div class=" bg-blue-300 p-5 rounded-md max-w-[70rem] overflow-y-auto mx-auto">
        <div class=" mb-4 flex justify-between items-center ">
            <form
                @submit.prevent="submit"
                class="">
                <FormSearch
                    v-model:search="form.search"
                    v-model:per-page.number="form.perPage"/>
            </form>

            <h3 class="text-3xl font-bold text-gray-900">
                Clientes
            </h3>
        </div>

        <div class=" max-h-[60vh] overflow-y-auto">
            <table
                class="styleTable table-fixed w-full">
                <thead>
                    <tr>
                      <th class="w-[15rem]">Nombre</th>
                      <th class="w-[10rem]">Ced./RNC/Pas</th>
                      <th class="w-[20rem]" >Correo</th>
                      <th class="w-[10rem]">Teléfono</th>
                      <th class="w-[5rem]">Tipo</th>
                      <th class="w-[5rem]">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item) in props.clients.data">
                        <td class="truncate">{{item.name}}</td>
                        <td class="truncate">{{item.personal_id}}</td>
                        <td class=" truncate">{{item.email}}</td>
                        <td class="truncate">{{item.phone}}</td>
                        <td class="truncate">{{item.type}}</td>
                        <td class="truncate">
                            <i
                                v-if="page.component !== 'Clients/ClientShow'"
                                title="Seleccionar"
                                @click="emit('getData',item)"
                                class="icon-efect fa-solid fa-circle-check"></i>
                            <i
                                v-if="page.component === 'Clients/ClientShow'"
                                title="Editar"
                                @click="edit(item.uuid)"
                                class=" ml-2 icon-efect fa-solid fa-pen-to-square"></i>
                            <i
                                v-if="page.component === 'Clients/ClientShow'"
                                title="Eliminar"
                                @click="destroy(item.uuid)"
                                class="ml-2 icon-efect fa-solid fa-trash"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- PAginacion -->
        <Pagination
            :current-page="props.clients.current_page"
            :total-page="props.clients.to"
            :prev="props.clients.prev_page_url
                ? paginationJoin(props.clients.prev_page_url, form.search, form.perPage)
                : '' "
            :next="props.clients.next_page_url
                ? paginationJoin(props.clients.next_page_url, form.search, form.perPage)
                : '' " />
    </div>
</template>

<style scoped>

</style>
