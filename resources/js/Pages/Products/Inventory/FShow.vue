<script setup lang="ts">
import Pagination from "@components/Pagination.vue";
import {paginationI} from "@/Interfaces/Global";
import {entryProductI} from "@/Interfaces/EntryTrans";
import FormSearch from "@components/FormSearch.vue";
import {router, useForm} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";

// PRopiedades de la ventana
const propsW = defineProps<{
    entries: paginationI<entryProductI>
}>();

const emit = defineEmits<{
    (e:'edit', item: entryProductI): void;
}>();


const formSearch = useForm({
    search: '',
    per_page: 30,
    field: "name",
});



/**
 * editar los datos
 * @param item
 */
const edit = (item:entryProductI) => {
    emit("edit", item);
    // form.id = item.id;
    // productName.value = item.product.name;
    // form.product_id = item.product.id;
    // form.cost = item.cost;
    // form.quantity = item.quantity;
    // form.description = item.description ||  '';
    // form.type = 'AJUSTE';
    // form.update = true;
}

/**
 * Eliminar
 * @param item
 */
const destroy = (item:entryProductI) => {
    Swal.fire({
        title: "Desea Eliminar?",
        text: "Los Cambios Realizados Son Irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('entry.destroy',{entry: item.id}),{
                onSuccess: () => {
                    successHttp('Datos Eliminados Correctamente');
                }
            });
        }
    });
}

/**
 * Buscar los datos
 */
const search = () =>{
    router.get(`/product/entry?search=${formSearch.search}&perPage=${formSearch.per_page}`,
        {},
        {
            preserveState:true,
            preserveScroll:true
        });
}


</script>

<template>
    <div class="fondo mt-3 p-3  rounded-md">
        <form
            @submit.prevent="search">
            <FormSearch
                v-model:search="formSearch.search"
                v-model:total="formSearch.per_page"/>
        </form>
        <table class="styleTable w-full mt-2">
            <thead>
            <tr>
                <th>Producto</th>
                <th>Referencia</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Act</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(item, index) in propsW.entries.data"
                :key="index">
                <td>{{item.product.name}}</td>
                <td>{{item.product.sku}}</td>
                <td>{{item.created_at}}</td>
                <td>{{item.type}}</td>
                <td>{{item.quantity}}</td>
                <td class="">
                    <span
                        v-if="!item.was_updated"
                        class="space-x-3">
                        <i
                            @click="edit(item)"
                            class="icon-efect fa-solid fa-pen-to-square"></i>
                        <i
                            @click="destroy(item)"
                            class="icon-efect fa-solid fa-trash"></i>
                    </span>
                    <span v-else>
                        Editado
                    </span>

                </td>
            </tr>
            </tbody>
        </table>
        <Pagination
            :field="formSearch.field"
            :search="formSearch.search"
            :per-page="formSearch.per_page"
            :current-page="propsW.entries.current_page"
            :next="propsW.entries.next_page_url || ''"
            :prev="propsW.entries.prev_page_url || '' "
            :total-page="propsW.entries.to"/>

    </div>

</template>

<style scoped>

</style>
