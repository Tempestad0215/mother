<script setup lang="ts">
import {warehouseBaseI} from "@/Interfaces/WarehouseInterface";
import Swal from "sweetalert2";
import {router} from "@inertiajs/vue3";
import {successHttp} from "@/Global/Alert";
import {useRoute} from "ziggy-js";


const route = useRoute();
/*
Propiedades
 */
const propsW = defineProps<{
    warehouse: warehouseBaseI[]
}>();


const emit = defineEmits<{
    (e: "editWareHouse", item: warehouseBaseI): void
}>()


/**
 *
 * @param item
 */
const edit = (item:warehouseBaseI) => {
    // form.id = item.id;
    // form.name = item.name;
    // form.description = item.description;
    // form.location = item.location;
    // form.update = true;
   emit('editWareHouse', item);
}


/**
 *
 * @param item
 */
const destroy = (item:warehouseBaseI) => {
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
            router.delete(route('wh.destroy',{wh: item.id}),{
                onSuccess: () => {
                    successHttp('Datos Eliminado Correctamente');
                }
            });
        }
    });
}

</script>

<template>
    <!-- Cuentas registrada -->
    <div class="mt-3 p-5 fondo rounded-md">
        <h3 class="title text-center">
            Listado de Almacenes
        </h3>
        <table
            class="styleTable table-auto w-full mt-3">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Description</th>
                <th>Location</th>
                <th>Act</th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-for="(item, index) in propsW.warehouse"
                :key="index">
                <td>{{item.name}}</td>
                <td>{{item.description}}</td>
                <td>{{item.location}}</td>
                <td class="space-x-3">
                    <i
                        @click="edit(item)"
                        class=" icon-efect fa-solid fa-file-pen"></i>
                    <i
                        @click="destroy(item)"
                        class=" icon-efect fa-solid fa-trash-can"></i>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>

</style>
