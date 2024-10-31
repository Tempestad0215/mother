<script setup lang="ts">
import Pagination from "@components/Pagination.vue";
import {clientDataI, clientI} from "@/Interfaces/ClientInterface";
import {router, useForm, usePage} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";
import FormSearch from "@components/FormSearch.vue";
import {computed} from "vue";
import {paginationJoin} from "@/Global/Helpers";

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
}>();


//Propiedades computada
const isSale = computed(()=>{
   return page.url.startsWith('/sale');
});



/**
 * Formulario
 */
const form = useForm({
    search:"",
    perPage:15
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
    <div class=" bg-gray-200 p-5 rounded-md">
        <div class=" mb-4 flex justify-between items-center ">
            <form
                @submit.prevent="submit"
                class="">
                <FormSearch
                    v-model="form.search"
                    holder="Buscar"
                    v-model:select-value="form.perPage"/>


            </form>

            <h3 class="text-3xl font-bold text-gray-900">
                Clientes
            </h3>
        </div>

        <div class=" max-h-[550px] overflow-y-auto">
            <table
                class=" table-auto w-full">
                <thead
                    class=" sticky top-0 text-left">
                <tr
                    class="">
                    <th >Code</th>
                    <th class="overflow-hidden max-w-[50px]" >Nombre</th>
                    <th >Ced./Rnc./Pas.</th>
                    <th v-if="!isSale" >Correo</th>
                    <th v-if="!isSale" >Teléfono</th>
                    <th >Atc</th>

                </tr>
                </thead>

                <!-- Contenido -->
                <tbody>
                <tr
                    class=" "
                    v-for="(item, index) in props.clients?.data" :key="index" >
                    <td class="">
                        {{ item.code }}
                    </td>
                    <td class="overflow-hidden" >
                        {{item.name}}
                    </td>
                    <td class=" ">
                        {{item.personal_id}}
                    </td>
                    <td
                        v-if="!isSale"
                        class="">
                        {{item.email ? item.email : 'N/A'}}
                    </td>
                    <td
                        v-if="!isSale"
                        class="">
                        {{ item.phone }}
                    </td>
                    <!-- Botones -->
                    <td class="space-x-5">
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
