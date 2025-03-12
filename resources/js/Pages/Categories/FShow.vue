<script setup lang="ts">
import Pagination from "@components/Pagination.vue";
import FormSearch from "@components/FormSearch.vue";
import {paginationI} from "@/Interfaces/Global";
import {categoryBaseI} from "@/Interfaces/Categories";
import {useForm} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";


/*Datos de la ventana*/
const propsW = defineProps<{
    categories: paginationI<categoryBaseI>
}>();

// Formulario
const form = useForm({
   search:"",
   per_page: 15,
   field: ""
});


// Enviar los datos
const search = () => {
    form.get(route('category.create'),{
        preserveState: true,
        preserveScroll: true
    });
}


/**
 * Para editar los datos
 * @param item
 */
const edit = (item:categoryBaseI) => {
    // form.id = item.id;
    // form.name = item.name;
    // form.description = item.description ? item.description : "";
    // form.update = true;

}

/**
 * Para eliminar los datos
 * @param item
 */
const destroy = (item:categoryBaseI) => {
    // Preguntar antes de eliminar
    Swal.fire({
        title: `Desea eliminar la categoria: ${item.name}?`,
        text: "Los cambios realizados son irreversible!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            form.patch(route('category.destroy',{category: item.id}),{
                onSuccess: () => {
                    successHttp('Datos eliminado correctamente');
                    form.reset();
                }

            })
        }
    });
}




</script>

<template>
    <div class="fondo mt-3 rounded-md p-5 ">
        <div class="flex justify-between items-center">
            <form
                @submit.prevent="search">
                <FormSearch
                    holder="-- Buscar Categoria --"
                    v-model:search="form.search"
                    v-model:per-page.number="form.per_page">
                </FormSearch>
            </form>
            <h3 class="text-3xl font-bold">
                Categorias
            </h3>
        </div>

        <!--    Tabla de las categorias-->
        <table class=" mt-3 styleTable table-fixed w-full">
            <thead>
            <tr>
                <th class="w-[25rem]">Nombre</th>
                <th>Desripción</th>
                <th class="w-[6rem]">Act</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item) in propsW.categories.data">
                <td class="truncate">{{item.name}}</td>
                <td class="truncate">{{item.description}}</td>
                <td>
                    <i
                        @click="edit(item)"
                        title="Editar"
                        class=" icon-efect fa-solid fa-pen-to-square"></i>
                    <i
                        @click="destroy(item)"
                        title="Eliminar"
                        class=" ml-3 icon-efect fa-solid fa-trash"></i>
                </td>
            </tr>
            </tbody>
        </table>
        <Pagination
            :field="form.field"
            :per-page="form.per_page"
            :search="form.search"
            :next="propsW.categories.next_page_url"
            :total-page="propsW.categories?.to"
            :prev=" propsW.categories.prev_page_url"
            :current-page="propsW.categories?.current_page"/>
    </div>
</template>

<style scoped>

</style>
