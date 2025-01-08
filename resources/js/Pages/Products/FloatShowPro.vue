<script setup lang="ts">

import FormSearch from "@components/FormSearch.vue";
import Pagination from "@components/Pagination.vue";
import {productBaseI, productI} from "@/Interfaces/Product";
import {router, useForm, usePage} from "@inertiajs/vue3";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";
import {getMoney} from "@/Global/Helpers";
import {onMounted} from "vue";

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


onMounted(()=>{
    //Tomar el parametros de buscar
    const search:string = route().params.search;

    //si existe el search
    if(search){
        form.search = search;
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
    search:'',
    perPage: 30
});



/**
 * Funciones
 */
// Funciones
const submit = () => {

    router.get(``,{
        page:1,
        perPage:form.perPage,
        search:form.search
    },{
        preserveState: true,
        preserveScroll: true,
    });

}


//editar el producto
const edit = (id:number) => {
    router.get(route('product.edit', {id: id}));
}

//Seleccionar
const selectData = (item:productBaseI) => {
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
const detroy = (id:number) => {
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
         class="rounded-md ">
        <div
            class="flex justify-between">
            <div>
                <form @submit.prevent="submit"  >
                    <FormSearch
                        v-model:search="form.search"
                        v-model:per-page.number="form.perPage"/>
                </form>
            </div>
            <h3 class="text-3xl font-bold float-right mt-6">
                Productos
            </h3>
        </div>

        <div
            class="max-h-[65vh] overflow-y-auto overflow-x-hidden">
            <table
                class=" mt-3 styleTable table-fixed  w-full">
                <thead>
                    <tr>
                        <th class="w-[10rem]">Cod. Barra</th>
                        <th class="w-[10rem]">Ref.</th>
                        <th class="w-[20rem]">Nombre</th>
                        <th class="w-[10rem]">Disp.</th>
                        <th class="w-[10rem]">Precio</th>
                        <th class="w-[6rem]">Act</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item) in propsW.products.data">
                        <td>{{item.bar_code || 'N/A'}}</td>
                        <td>{{item.sku || 'N/A'}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.stock}}</td>
                        <td>{{getMoney(item.price)}}</td>
                        <td>

                            <!-- Entrada de producto -->
                            <i
                                v-if="url !== 'Products/Show'"
                                title="Crear Entrada"
                                @click="selectData(item)"
                                class=" icon-efect fa-solid fa-circle-check"></i>

                            <!-- Editar -->
                            <i
                                v-if="component === 'Products/Show' "
                                title="Editar"
                                @click="edit(item.id)"
                                class="ml-2 icon-efect fa-solid fa-pen-to-square"></i>

                            <!-- Eliminar -->
                            <i
                                v-if="component === 'Products/Show' && auth.user.role === 'admin' "
                                title="Eliminar"
                                @click="detroy(item.id)"
                                class="ml-2 icon-efect fa-solid fa-trash"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!--        PAginacion de la ventana-->
        <Pagination
            :current-page="propsW.products.current_page"
            :total-page="propsW.products.to"
            :next="propsW.products.next_page_url ? propsW.products.next_page_url+'&perPage='+form.perPage : ''"
            :prev="propsW.products.prev_page_url ? propsW.products.prev_page_url+'&perPage='+form.perPage : '' "/>

    </div>
</template>

