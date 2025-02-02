<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import InputError from "@components/InputError.vue";
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@components/PrimaryButton.vue";
import {successHttp} from "@/Global/Alert";
import {categoryBaseI, categoryPaginationI} from "@/Interfaces/Categories";
import FormSearch from "@components/FormSearch.vue";
import Pagination from "@components/Pagination.vue";
import Swal from "sweetalert2";
import {paginationJoin} from "@/Global/Helpers";

/*
Propiedades de la ventana
 */
const props = defineProps<{
    categories: categoryPaginationI
}>();

/*
Formularios
 */
const form = useForm({
    id: 0,
    name:"",
    description:"",
    update: false,
    search: ""
});

/*
Formulario de busqueda
 */
const formSearch = useForm({
    search:"",
    perPage:30
});


/**
 * Para enviar los datos
 */
const submit = () => {
    //si es para actualizar
    if(form.update)
    {
        form.patch(route('category.update',{category: form.id}),{
            onSuccess: ()=>{
                successHttp('Datos actualizado correctamente');
            }
        })
    }else {
        form.post(route('category.store'),{
            onSuccess:()=>{
                successHttp('Datos registrado correctamente');
                form.reset();
            }
        });
    }

}

/**
 * Para editar los datos
 * @param item
 */
const edit = (item:categoryBaseI) => {
    form.id = item.id;
    form.name = item.name;
    form.description = item.description ? item.description : "";
    form.update = true;

}

/**
 * Para eliminar los datos
 * @param item
 */
const destroy = (item:categoryBaseI) => {
    //colocar el id al formulario
    form.id = item.id;
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
            form.patch(route('category.destroy',{category: form.id}),{
                onSuccess: () => {
                    successHttp('Datos eliminado correctamente');
                    form.reset();
                }

            })
        }
    });
}


/**
 * Para busqueda los datos
 */
const search = () => {
    formSearch.get(route('category.create',{search: formSearch.search}),{
        preserveState: true,
        preserveScroll: true
    })
}


</script>

<template>
  <AppLayout
    title="Categoria">
        <template #header >

        </template>

        <div class="">
            <form
                @submit.prevent="submit"
                class="bg-blue-300 rounded-md p-5 grid grid-cols-2 gap-3">
                <h3 class=" text-2xl font-bold col-span-full text-center">
                    Registro de Categoria
                </h3>
                <div class="mt-4">
                    <input-label
                        for="name"
                        value="Nombre *" />
                    <text-input
                        class="w-full"
                        name="name"
                        maxLength="75"
                        v-model="form.name"
                        placeholder="Nombre"/>
                    <input-error
                        :message="form.errors.name"/>
                </div>

                <div class="mt-4">
                    <input-label
                        for="description"
                        value="Descripción" />
                    <text-input
                        class="w-full"
                        name="description"
                        maxLength="255"
                        v-model="form.description"
                        placeholder="Describe brevemente"/>
                    <input-error
                        :message="form.errors.description"/>
                </div>

                <div class="mt-4 text-right col-span-full">
                    <primary-button
                        :disabled="form.processing">
                        Registrar
                    </primary-button>
                </div>

            </form>


            <div class="bg-blue-300 mt-3 rounded-md px-5 ">
                <div class="flex justify-between items-center">
                    <form
                        @submit.prevent="search">
                        <FormSearch
                            holder="-- Buscar Categoria --"
                            v-model:search="formSearch.search"
                            v-model:per-page.number="formSearch.perPage">
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
                        <tr v-for="(item) in props.categories.data">
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
                    :next="props.categories.next_page_url
                        ? paginationJoin(props.categories.next_page_url, formSearch.search, formSearch.perPage)
                        : ''"
                    :total-page="props.categories?.to"
                    :prev=" props.categories.prev_page_url
                        ? paginationJoin(props.categories.prev_page_url, formSearch.search, formSearch.perPage)
                        : ''"
                    :current-page="props.categories?.current_page"/>
            </div>

        </div>
  </AppLayout>
</template>
