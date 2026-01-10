<script setup lang="ts">
import Pagination from "@components/Pagination.vue";
import {clientBaseI} from "@/Interfaces/ClientInterface";
import {router, useForm, usePage} from "@inertiajs/vue3";
import FormSearch from "@components/FormSearch.vue";
import {PaginationI} from "@/Interfaces/GlobalInterface";
import {onMounted} from "vue";
import {exportExcel} from "@/Global/Helpers";
import {useRoute} from "ziggy-js";


const route = useRoute();
/**
 * Datos de la ventana
 */
const page = usePage();

/**
 * Datos del back end
 */
const props = defineProps<{
    clients: PaginationI<clientBaseI>;
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
    perPage:30,
    field: "name",
});



// Al momento de cargar
onMounted(()=>{
   //  Obtener el campo del local storage
   form.field = localStorage.getItem('field') || 'name';
});



/**
 * funciones
 */
// Enviar los datos
const submit = () => {
    // Limpiar los errores
    form.clearErrors();
    // Enviar el formularios
    router.get(``,{page:1, field:form.field, perPage:form.perPage, search:form.search},{
        preserveState: true,
        preserveScroll: true
    });
    // form.get(`?page=1&perPage=${form.perPage}&search=${form.search}`,{
    //     preserveScroll: true,
    //     preserveState: true
    // });


}

// Editar
const edit = (id:number) => {
    // Hacer la peticion
    router.get(route('client.edit', id));
}

// Eliminar el resistros
const destroy = (id:number) => {

    // // Enviar los datos
    // Swal.fire({
    //     title: "Desea eliminar este registro?",
    //     text: "Los cambios realizados son irreversible!",
    //     icon: "question",
    //     showCancelButton: true,
    //     confirmButtonColor: "#3085d6",
    //     cancelButtonColor: "#d33",
    //     confirmButtonText: "Si, eliminar!",
    //     cancelButtonText: "Cancelar",
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         router.delete(route('client.destroy', id), {
    //             onSuccess: () => {
    //                 // Mensaje de exito
    //             }
    //         })
    //     }
    // });
}

/**
 *
 * @param field
 */
const field = (field: string) => {
    form.field = field;

    // Colocar en el local storage
    localStorage.setItem('field', form.field);
}



/*
Descargar todos los clientes a excel
 */
const download = async () => {
    // Swal.fire({
    //     title: "Desea Exportar?",
    //     text: "Todos los registro seran exportado en formato xlsx!",
    //     icon: "warning",
    //     showCancelButton: true,
    //     confirmButtonColor: "#3085d6",
    //     cancelButtonColor: "#d33",
    //     confirmButtonText: "Si, Exportar!",
    //     cancelButtonText: "Cancelar",
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         exportExcel(route('client.export-excel'), "clientes.xlsx");
    //     }
    // });

}

</script>

<template>
    <div class=" fondo p-5 rounded-md overflow-y-auto">
        <div class=" mb-4 flex justify-between items-center ">
                <form
                    @submit.prevent="submit"
                    class="">
                    <FormSearch
                        v-model:search="form.search"
                        v-model:per-page.number="form.perPage"/>
                </form>

            <i
                @click="download"
                title="Descargar CSV"
                class="fa-solid fa-file-csv text-gray-50 text-[3rem]"></i>



            <h3 class="text-3xl font-bold text-gray-50">
                Clientes
            </h3>
        </div>

        <div class="">
            <table
                class="styleTable table-auto w-full">
                <thead>
                    <tr>
                      <th
                          @dblclick="field('name')"
                          class="">
                          <i
                              v-if="form.field === 'name'"
                              class="fa-solid fa-arrow-down mr-2"></i>
                          Nombre
                      </th>
                      <th
                          @dblclick="field('document')"
                          class="">
                          <i
                              v-if="form.field === 'document'"
                              class="fa-solid fa-arrow-down mr-2"></i>
                          Ced./RNC/Pas
                      </th>
                      <th
                          @dblclick="field('email')"
                          class="" >
                          <i
                              v-if="form.field === 'email'"
                              class="fa-solid fa-arrow-down mr-2"></i>
                          Correo
                      </th>
                      <th
                          @dblclick="field('phone')"
                          class="">
                          <i
                              v-if="form.field === 'phone'"
                              class="fa-solid fa-arrow-down mr-2"></i>
                          Teléfono
                      </th>
                      <th
                          class=" cursor-not-allowed">
                          Tipo
                      </th>
                      <th class="cursor-not-allowed">Act</th>
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
                                v-if="page.component !== 'Clients/Show'"
                                title="Seleccionar"
                                @click="emit('getData',item)"
                                class="icon-efect fa-solid fa-circle-check"></i>
                            <i
                                v-if="page.component === 'Clients/Show'"
                                title="Editar"
                                @click="edit(item.id)"
                                class=" ml-2 icon-efect fa-solid fa-pen-to-square"></i>
                            <i
                                v-if="page.component === 'Clients/Show'"
                                title="Eliminar"
                                @click="destroy(item.id)"
                                class="ml-2 icon-efect fa-solid fa-trash"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- PAginacion -->
        <Pagination
            :search="form.search"
            :field="form.field"
            :per-page="form.perPage"
            :current-page="props.clients.current_page"
            :total-page="props.clients.to"
            :prev="props.clients.prev_page_url"
            :next="props.clients.next_page_url"/>
    </div>
</template>

<style scoped>

</style>
