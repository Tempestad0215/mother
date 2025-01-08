<script setup lang="ts">
import {paginationJoin} from "@/Global/Helpers";
import Pagination from "@components/Pagination.vue";
import FormSearch from "@components/FormSearch.vue";
import {router, useForm} from "@inertiajs/vue3";
import {supplierDataI, supplierI} from "@/Interfaces/Supplier";
import AppLayout from "@layout/AppLayout.vue";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";
import {Head} from "@inertiajs/vue3"
import TabLink from "@components/TabLink.vue";
import {ref, Ref} from "vue";
import FloatBox from "@components/FloatBox.vue";
import SupplierSee from "@/Pages/Suppliers/SupplierSee.vue";


/*
Propiedades
 */
const propsW = defineProps<{
    suppliers: supplierDataI
}>()


const supplierData:Ref<supplierI | null> = ref(null);
const seeSupplier:Ref<boolean> = ref(false);

/*
Fomulario
 */
const form = useForm({
    search:"",
    perPage:30,
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

/**
 * Ver
 */
const see = (item:supplierI) => {
    //Pasar los datos a la variable
    supplierData.value = item;
    seeSupplier.value = true;
}


</script>

<template>
    <Head title="Suplidores"/>
    <AppLayout>
        <template #header>
            <TabLink
                :href="route('supplier.create')">
                Registrar
            </TabLink>
            <TabLink
                :active="true"
                :href="route('supplier.show')">
                Mostrar
            </TabLink>
        </template>
        <div class="bg-blue-300 p-5 rounded-md max-w-[70rem] mx-auto">
            <!--        Table de datos-->
            <div class="mt-3">
                <div class="flex justify-between items-center">
                    <form
                        @submit.prevent="search">
                        <FormSearch
                            v-model:per-page="form.perPage"
                            v-model:search.number="form.search"
                        />
                    </form>
                    <h3 class="text-3xl font-bold text-center">
                        Suplidores
                    </h3>
                </div>
                <!--                Datos de los proveedores-->
                <table class=" styleTable table-fixed mt-3 w-full">
                    <thead>
                    <tr>
                        <th class="w-[15rem]">Empresa</th>
                        <th class="w-[10rem]">Contacto</th>
                        <th class="w-[10rem]">Teléfono</th>
                        <th class="w-[20rem]">Correo</th>
                        <th class="w-[6rem]">Act</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item) in propsW.suppliers.data">
                        <td class="truncate">{{item.company_name}}</td>
                        <td class="truncate">{{item.contact}}</td>
                        <td class="truncate">{{item.phone}}</td>
                        <td class="truncate">{{item.email}}</td>
                        <td class="space-x-2" >
                            <i
                                @click="see(item)"
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
    </AppLayout>


    <FloatBox
        :header="`Ver Suplidor : ${supplierData?.company_name}`"
        @close="seeSupplier = false"
        v-if="seeSupplier">
        <supplier-see
            :supplier="supplierData"/>
    </FloatBox>

</template>
