<script setup lang="ts">

import {paginationJoin} from "@/Global/Helpers";
import Pagination from "@components/Pagination.vue";
import FormSearch from "@components/FormSearch.vue";
import {useForm} from "@inertiajs/vue3";
import {supplierDataI, supplierI} from "@/Interfaces/Supplier";


/*
Propiedades
 */
const propsW = defineProps<{
    suppliers: supplierDataI
}>()

/*
Fomulario
 */
const form = useForm({
    search:"",
    perPage:10,
});


const search = () => {

}


const edit = (item: supplierI) => {

}

const destroy = (uuid: supplierI) => {

}


</script>

<template>
    <div>
    <!--        Table de datos-->
    <div class="mt-3">
        <div class="bg-gray-200 rounded-md px-5">
            <div class="flex justify-between items-center">
                <form
                    @submit.prevent="search">
                    <FormSearch
                        v-model:per-page="form.perPage"
                        v-model:search="form.search"
                    />
                </form>
                <h3 class="text-3xl font-bold text-center">
                    Suplidores
                </h3>
            </div>
        </div>
        <!--                Datos de los proveedores-->
        <table class=" styleTable w-full">
            <thead>
            <tr>
                <th>Empresa</th>
                <th>Contacto</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Act</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item) in propsW.suppliers.data">
                <td>{{item.company_name}}</td>
                <td>{{item.contact}}</td>
                <td>{{item.phone}}</td>
                <td>{{item.email}}</td>
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
    </div>

    <!--                PAginacion-->
    <Pagination
        :next=" propsW.suppliers.next_page_url
        ? paginationJoin(propsW.suppliers.next_page_url, form.search, form.perPage)
        : ''"
        :prev=" propsW.suppliers.prev_page_url
        ? paginationJoin(propsW.suppliers.prev_page_url, form.search, form.perPage)
        : ''"
        :total-page="propsW.suppliers?.to"
        :current-page="propsW.suppliers?.current_page"
    />

    </div>
</template>

<style scoped>

</style>
