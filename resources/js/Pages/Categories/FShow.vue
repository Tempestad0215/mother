<script setup lang="ts">
import Pagination from "@components/Pagination.vue";
import FormSearch from "@components/FormSearch.vue";
import {paginationI} from "@/Interfaces/GlobalInterface";
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {router, useForm} from "@inertiajs/vue3";
import {onMounted} from "vue";
import {exportExcel} from "@/Global/Helpers";
import {useRoute} from "ziggy-js";


const route = useRoute();
/*Datos de la ventana*/
const propsW = defineProps<{
    categories: paginationI<categoryBaseI>
}>();

// Formulario
const form = useForm({
   search:"",
   per_page: 15,
   field: "name"
});


// Al momento de cargar
onMounted(()=>{
    //  Obtener el campo del local storage
    form.field = localStorage.getItem('field') || 'name';
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
    // Swal.fire({
    //     title: `Desea editar la categoria: ${item.name}?`,
    //     text: "Estos Datos Seran Editada!",
    //     icon: "question",
    //     showCancelButton: true,
    //     confirmButtonColor: "#3085d6",
    //     cancelButtonColor: "#d33",
    //     confirmButtonText: "Si, Editar!",
    //     cancelButtonText: "Cancelar"
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         router.get(route('category.edit',{category: item.id}));
    //     }
    // });
}

/**
 * Para eliminar los datos
 * @param item
 */
const destroy = (item:categoryBaseI) => {
    // Preguntar antes de eliminar
    // Swal.fire({
    //     title: `Desea eliminar la categoria: ${item.name}?`,
    //     text: "Los cambios realizados son irreversible!",
    //     icon: "question",
    //     showCancelButton: true,
    //     confirmButtonColor: "#3085d6",
    //     cancelButtonColor: "#d33",
    //     confirmButtonText: "Si, eliminar!",
    //     cancelButtonText: "Cancelar"
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         form.patch(route('category.destroy',{category: item.id}),{
    //             onSuccess: () => {
    //                 form.reset();
    //             }
    //
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
    //         exportExcel(route('category.export-excel'), "categorias.xlsx");
    //     }
    // });

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
            <i
                @click="download"
                title="Descargar CSV"
                class="fa-solid fa-file-csv text-gray-50 text-[3rem]"></i>

            <h3 class="text-3xl font-bold">
                Categorias
            </h3>
        </div>

        <!--    Tabla de las categorias-->
        <table class=" mt-3 styleTable table-fixed w-full">
            <thead>
                <tr>
                    <th
                        @dblclick="field('name')"
                        class="w-[25rem]">
                        <i
                            v-if="form.field === 'name'"
                            class="fa-solid fa-arrow-down mr-2"></i>
                        Nombre
                    </th>
                    <th
                        @dblclick="field('description')">
                        <i
                            v-if="form.field === 'description'"
                            class="fa-solid fa-arrow-down mr-2"></i>
                        Desripción
                    </th>
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
<!--        Pagination-->
        <Pagination
            :field="form.field"
            :per-page="form.per_page"
            :search="form.search"
            :next="propsW.categories.next_page_url"
            :total-page="propsW.categories.to"
            :prev=" propsW.categories.prev_page_url"
            :current-page="propsW.categories.current_page"/>
    </div>
</template>

<style scoped>

</style>
