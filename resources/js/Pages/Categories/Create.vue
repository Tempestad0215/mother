<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import InputError from "@components/InputError.vue";
import {useForm} from "@inertiajs/vue3";
import PrimaryButton from "@components/PrimaryButton.vue";
import {successHttp} from "@/Global/Alert";
import {PropType} from "vue";
import {categoryI, categoryPaginationI} from "@/Interfaces/Categories";
import FormSearch from "@components/FormSearch.vue";
import Pagination from "@components/Pagination.vue";
import Swal from "sweetalert2";

const props = defineProps({
    categories: {
        type: Object as PropType<categoryPaginationI>
    }
});


const form = useForm({
    id:0,
    name:"",
    description:"",
    update: false,
    search: ""
});

const formSearch = useForm({
    search:""
});



const submit = () => {
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

const edit = (item:categoryI) => {
    form.id = item.id;
    form.name = item.name;
    form.description = item.description;
    form.update = true;

}
const destroy = (item:categoryI) => {

    form.id = item.id;

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
                }
            })
        }
    });
}

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

        <div>
            <form
                @submit.prevent="submit"
                class="bg-gray-200 md:max-w-full mx-auto rounded-md p-5 grid grid-cols-2 gap-3">
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
                    <primary-button>
                        Registrar
                    </primary-button>
                </div>

            </form>


            <div class="bg-gray-200 p-5 rounded-md mt-5">

                <form
                    @submit.prevent="search">
                    <FormSearch
                        holder="-- Buscar Categoria --"
                        v-model="formSearch.search">
                    </FormSearch>
                </form>

                <table class="w-full  rounded-md">
                    <thead>
                    <tr class=" text-left">
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Act</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="odd:bg-gray-400"
                            v-for="(item, index) in props.categories?.data" :key="index">
                            <td>{{item.id}}</td>
                            <td>{{item.name}}</td>
                            <td>{{item.description ? item.description : 'N/A'}}</td>
                            <td class="text-xl space-x-3 w-16">
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
                    :next="props.categories?.next_page_url"
                    :total-page="props.categories?.to"
                    :prev="props.categories?.prev_page_url"
                    :current-page="props.categories?.current_page"/>
            </div>

        </div>
  </AppLayout>
</template>
