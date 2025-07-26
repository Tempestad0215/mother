<script setup lang="ts">
import Pagination from "@components/Pagination.vue";
import FormSearch from "@components/FormSearch.vue";
import {successHttp} from "@/Global/Alert";
import {supplierI} from "@/Interfaces/SupplierInterface";
import Swal from "sweetalert2";
import {router, useForm} from "@inertiajs/vue3";
import {ref, Ref} from "vue";
import {paginationI} from "@/Interfaces/GlobalInterface";
import {exportExcel} from "@/Global/Helpers";

/*
Propiedades
 */
const propsW = defineProps<{
    suppliers: paginationI<supplierI>
}>();


const emit = defineEmits<{
    (e:'seeSupplier', data:supplierI):void
}>();

//
const supplierData:Ref<supplierI | null> = ref(null);
const seeSupplier:Ref<boolean> = ref(false);

/*
Fomulario
 */
const form = useForm({
    search:"",
    per_page:30,
    field: "company_name"
});


/**
 *
 * @param item
 */
const edit = (item:supplierI) => {
    router.get(route('supplier.edit',{supplier: item.id}));

}

/**
 *
 * @param item
 */
const destroy = (item:supplierI) => {

    Swal.fire({
        title: `Desea Eliminar el suplidor : ${item.company_name}?`,
        text: "Los cambios realizados son irreversible!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('supplier.destroy',{supplier: item.id}),{
                onSuccess: () => {
                    successHttp('Datos eliminado Correctamente');
                }
            });
        }
    });
}

/**
 *Buscar los datos
 */
const search = () => {
    form.get('',{
        preserveScroll: true,
        preserveState: true
    });
}

// /**
//  * Ver
//  */
// const see = (item:supplierI) => {
//     emit('see',item);
// }


// Actualizar el campo de suplidores
const field = (field:string) => {
    form.field = field;

    localStorage.setItem('field', field);
}

/*
Descargar todos los clientes a excel
 */
const download = async () => {
    Swal.fire({
        title: "Desea Exportar?",
        text: "Todos los registro seran exportado en formato xlsx!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Exportar!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            exportExcel(route('supplier.export-excel'), "suplidores.xlsx");
        }
    });

}


</script>

<template>
    <div class="fondo p-5 rounded-md">
        <!--        Table de datos-->
        <div class="mt-3">
            <div class="flex justify-between items-center">
                <form
                    @submit.prevent="search">
                    <FormSearch
                        v-model:per-page="form.per_page"
                        v-model:search.number="form.search"
                    />
                </form>

                <i
                    @click="download"
                    title="Descargar CSV"
                    class="fa-solid fa-file-csv text-gray-50 text-[3rem]"></i>

                <h3 class="text-3xl font-bold text-center">
                    Suplidores
                </h3>
            </div>
            <!--                Datos de los proveedores-->
            <table class=" styleTable mt-3 w-full">
                <thead>
                <tr>
                    <th
                        @dblclick="field('company_name')"
                        class="">
                        <i
                            v-show="form.field === 'company_name'"
                            class="fa-solid fa-arrow-down"></i>
                        Empresa
                    </th>
                    <th
                        @dblclick="field('contact')"
                        class="">
                        <i
                            v-show="form.field === 'contact'"
                            class="fa-solid fa-arrow-down"></i>
                        Contacto
                    </th>
                    <th
                        @dblclick="field('phone')"
                        class="">
                        <i
                            v-show="form.field === 'phone'"
                            class="fa-solid fa-arrow-down"></i>
                        Teléfono
                    </th>
                    <th
                        @dblclick="field('email')"
                        class="">
                        <i
                            v-show="form.field === 'email'"
                            class="fa-solid fa-arrow-down"></i>
                        Correo
                    </th>
                    <th class="">Act</th>
                </tr>
                </thead>
                <tbody>
                    <tr v-for="(item) in propsW.suppliers.data">
                        <td class="">{{item.company_name}}</td>
                        <td class="">{{item.contact}}</td>
                        <td class="">{{item.phone}}</td>
                        <td class="">{{item.email}}</td>
                        <td class="space-x-2" >
                            <i
                                @click="emit('seeSupplier', item)"
                                class="icon-efect fa-regular fa-eye"></i>
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
            :per-page="form.per_page"
            :search="form.search"
            :field="form.field"
            :next=" propsW.suppliers.next_page_url"
            :prev=" propsW.suppliers.prev_page_url"
            :total-page="propsW.suppliers?.to"
            :current-page="propsW.suppliers?.current_page"
        />
    </div>
</template>

<style scoped>

</style>
