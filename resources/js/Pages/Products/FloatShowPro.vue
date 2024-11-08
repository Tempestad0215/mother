<script setup lang="ts">

import FormSearch from "@components/FormSearch.vue";
import Pagination from "@components/Pagination.vue";
import {productDataI, productI} from "@/Interfaces/Product";
import {router, useForm, usePage} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";
import {getMoney} from "@/Global/Helpers";

import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';

/**
 * Informacion de la ventana
 */
const {url, component, props} = usePage();
const {auth} = props;

/**
 * Propiedades de la ventana
 */
const propsW = defineProps<{
    products: productI
}>();


/**
 * Emitir los eventos
 */
const emit = defineEmits(['select']);


/**
 * Formulario de datos
 */
const form = useForm({
    search:'',
    perPage: 15
});




/**
 * Funciones
 */
// Funciones
const submit = () => {
    form.get( ``, {
        preserveScroll: true,
        preserveState: true
    });
}


//editar el producto
const edit = (id:number) => {
    router.get(route('product.edit', {id: id}));
}

//Seleccionar
const selectData = (item:productDataI) => {
    //Verificar si es la URL
    if (url.startsWith('/product'))
    {
        //Enviar los datos
        router.get(route('in.entrance',{productIn: item.id}));
    }else{
        //Enviar los datos
        emit('select',item);
    }


}

//Eliminar el producto
const detroy = (id:Number) => {
    Swal.fire({
        title: "Esta seguro?",
        text: "Los cambios realizados son irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(route('product.destroy', {product: id}),{},{
                onSuccess: () => {
                    successHttp('Datos eliminado correctamente');
                }
            })
        }
    });
}

/**
 * Propiedades computada
 */
//PRopiedades computada
// const isSale = computed(()=>{
//    if(page.component === 'Products/Sale')
//    {
//        return ' text-xl'
//    }else{
//        return ' w-[200px] max-w-[200px] truncate  text-xl px-3';
//    }
// });

</script>

<template>
    <div
         class="rounded-md max-h-[90vh] px-5 overflow-hidden ">
        <div
            class="flex justify-between">
            <div>
                <form @submit.prevent="submit"  >
                    <FormSearch
                        v-model="form.search"
                        holder="Buscar"
                        v-model:select-value="form.perPage"/>
                </form>
            </div>
            <h3 class="text-3xl font-bold float-right mt-6">
                Productos
            </h3>
        </div>

        <div
            class="max-h-[65vh] overflow-y-scroll ">
            <DataTable show-gridlines striped-rows :value="propsW.products.data" >
                <Column field="code" header="Cod."  />
                <Column field="bar_code" header="Cod. Barra"  />
                <Column field="name" header="Nombre"  />
                <Column field="stock" header="Disp."  />
                <Column field="price" header="Disp." >
                    <template #body="{data}">
                        {{getMoney(data.price)}}
                    </template>
                </Column>
                <Column #body="{data}" field="name" header="Act" >
                    <!-- Entrada de producto -->
                    <i
                        v-if="url !== 'Products/Show'"
                        title="Crear Entrada"
                        @click="selectData(data)"
                        class=" icon-efect fa-solid fa-circle-check"></i>

                    <!--                        <i-->
                    <!--                            v-if="page.component !== 'Products/Sale' "-->
                    <!--                            class="icon-efect fa-solid fa-arrows-down-to-line"></i>-->

                    <!-- Ver los productos -->
                    <!--                        <i-->
                    <!--                            v-if="page.component !== 'Products/Sale' "-->
                    <!--                            class="icon-efect  fa-solid fa-eye"></i>-->

                    <!-- Editar -->
                    <i
                        v-if="component === 'Products/Show' "
                        title="Editar"
                        @click="edit(data.id)"
                        class="ml-2 icon-efect fa-solid fa-pen-to-square"></i>

                    <!-- Eliminar -->
                    <i
                        v-if="component === 'Products/Show' && auth.user.role === 'admin' "
                        title="Eliminar"
                        @click="detroy(data.id)"
                        class="ml-2 icon-efect fa-solid fa-trash"></i>
                </Column>
            </DataTable>

        </div>

        <!--        PAginacion de la ventana-->
        <Pagination
            :current-page="propsW.products.current_page"
            :total-page="propsW.products.to"
            :next="propsW.products.next_page_url ? propsW.products.next_page_url+'&perPage='+form.perPage : ''"
            :prev="propsW.products.prev_page_url ? propsW.products.prev_page_url+'&perPage='+form.perPage : '' "/>

    </div>
</template>

