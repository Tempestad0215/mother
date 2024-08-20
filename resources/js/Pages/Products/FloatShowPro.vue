<script setup lang="ts">

import FormSearch from "@components/FormSearch.vue";
import Pagination from "@components/Pagination.vue";
import {PropType} from "vue";
import {productDataI, productI} from "@/Interfaces/Product";
import {router, useForm, usePage} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";


/**
 * Informacion de la ventana
 */
const page = usePage();


/**
 * Propiedades de la ventana
 */
const props = defineProps({
    products: {
        type: Object as PropType<productI>,
        required: true
    }
});


/**
 * Emitir los eventos
 */
const emit = defineEmits(['select']);


/**
 * Formulario de datos
 */
const form = useForm({
    search:''
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

const edit = (id:number) => {
    router.get(route('product.edit', id));
}

const selectData = (item:productDataI) => {
    emit('select',item);
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
    <div class="bg-gray-200 mt-5 rounded-md p-5">
        <div>
            <form @submit.prevent="submit"  >
                <FormSearch
                    v-model="form.search" />
            </form>
        </div>

        <table class=" table-auto w-full">
            <thead>
            <tr class=" text-left">
                <th>Id</th>
                <th>Cod. Barr</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Uni.</th>
                <th>Disp.</th>
                <th>Precio</th>
                <th class=" w-[75px] max-w-[150px]">Atc</th>
            </tr>
            </thead>
            <tbody>
            <tr
                class=" odd:bg-gray-400"
                v-for="(item, index) in props.products.data" :key="index">
                <td>{{item.id}}</td>
                <td>{{item.bar_code ? item.bar_code : 'N/A'}}</td>
                <td>{{item.name}}</td>
                <td>{{item.description}}</td>
                <td>{{item.unit}}</td>
                <td>{{item.stock}}</td>
                <td>{{item.price}}</td>
                <td
                    class="">
                    <div
                        class=" w-[75px] space-x-3">
                        <!-- Entrada de producto -->
                        <i
                            v-if="$page.component !== 'Products/Create'"
                            title="Seleccionar"
                            @click="selectData(item)"
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
                            v-if="page.component !== 'ProductsSale/Create' "
                            title="Editar"
                            @click="edit(item.id)"
                            class="icon-efect fa-solid fa-pen-to-square"></i>

                        <!-- Eliminar -->
                        <i
                            v-if="page.component !== 'ProductsSale/Create' "
                            title="Eliminar"
                            @click="detroy(item.id)"
                            class="icon-efect fa-solid fa-trash"></i>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <Pagination
            :current-page="props.products.current_page"
            :total-page="props.products.to"
            :next="props.products.next_page_url ? props.products.next_page_url : ''"
            :prev="props.products.prev_page_url ? props.products.prev_page_url : '' "/>
    </div>
</template>

