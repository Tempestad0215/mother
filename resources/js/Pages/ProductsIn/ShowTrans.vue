<script setup lang="ts">
import {Head, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import LinkHeader from "@components/LinkHeader.vue";
import {productTransPI} from "@/Interfaces/Product";
import {getMoney} from "@/Global/Helpers";
import FormSearch from "@components/FormSearch.vue";
import Pagination from "@components/Pagination.vue";
import InputError from "@components/InputError.vue";
import {successHttp} from "@/Global/Alert";
import Swal from "sweetalert2";
import Column from "primevue/column";
import DataTable from "primevue/datatable";


/**
 * Propiedades
 */
const props = defineProps<{
    trans: productTransPI
}>();

/**
 * Formulario
 */
const form = useForm({
    search:"",
    perPage:15,
    general:""
});



/*
Funciones
 */
const submit = () => {
    // form.get(`?search=${form.search}`, {
    //     preserveScroll: true,
    //     preserveState: true
    // });

   form.get(``, {
       preserveScroll: true,
       preserveState: true
   })
}

//Editar la entrada
// const edit = (id:number) => {
//     router.get(route('in.edit',{trans: id}));
// }

//Eliminar la transaccion
const destroy = (id:number) => {
    Swal.fire({
        title: "Desea Eliminar?",
        text: "Los Cambios Realizados Son Irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(route('in.destroy',{trans: id}),{},{
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    successHttp('Documento Eliminado Correctamente');
                },
                onError: () => {
                    //Limpiar el error luego de 5 Segundo
                    setTimeout(() => {
                        form.clearErrors('general');
                    },5000)
                }
            });
        }
    });


}


</script>

<template>
    <Head title="Transacciones"/>
    <AppLayout>

<!--        Cabecera de la pagina-->
        <template #header >
            <LinkHeader

                :href="route('in.create')">
                Entrada
            </LinkHeader>
            <LinkHeader
                :active="true"
                :href="route('in.show')">
                Mostrar
            </LinkHeader>

        </template>


<!--        Contenido de la ventana-->
        <div class="bg-gray-200 rounded-md mx-auto overflow-hidden p-5">
            <div class="flex items-center justify-between">

                <div>
                    <form @submit.prevent="submit">
                        <FormSearch
                            holder="Buscar"
                            v-model:select-value="form.perPage"
                            v-model="form.search"/>
                        <InputError :message="form.errors.search"/>
                    </form>

                </div>
                <h3 class="text-3xl font-bold text-center ">
                    Transacciones
                </h3>
            </div>


            <!--                Datos de los productos para la entrada    -->
            <DataTable
                striped-rows
                show-gridlines
                :value="props.trans.data" >
                <Column field="product_name" header="Producto/Servicio"  />
                <Column
                    field="stock"
                    header="Disponible"
                    #body="item">

                </Column>
                <Column
                    field="stock"
                    header="Itbis"
                    #body="item">
                    {{getMoney(item.data.tax)}}
                </Column>
                <Column
                    field="stock"
                    header="Precio"
                    #body="item">
                    {{getMoney(item.data.price)}}
                </Column>
                <Column field="type" header="Tipo"  />
                <Column
                    field="stock"
                    header="Act"
                    #body="item">

                    <span
                        v-if="item.data.type != 'entrada'">
                        N/A
                    </span>
                    <i
                        v-if="item.data.type == 'entrada'"
                        @click="destroy(item.data.id)"
                        class=" icon-efect ml-3 fa-solid fa-trash"></i>
                </Column>
            </DataTable>

<!--            Paginacion-->
            <Pagination
                :next="props.trans.links.next"
                :prev="props.trans.links.prev"
                :total-page="props.trans.meta.to"
                :current-page="props.trans.meta.current_page"/>
        </div>
    </AppLayout>

</template>

