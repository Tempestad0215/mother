<script lang="ts" setup>
import { moneyConfig } from '@/Global/Helpers';
import {productBaseI, productI} from '@/Interfaces/Product';
import FloatBox from '@components/FloatBox.vue';
import PrimaryButton from '@components/PrimaryButton.vue';
import TabLink from '@components/TabLink.vue';
import TextInput from '@components/TextInput.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import { Money } from 'v-money3';
import {ref, watch} from 'vue';
import {errorHttp, successHttp} from "@/Global/Alert";
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";
import {entryBaseI, entryProductI, entryTableI} from "@/Interfaces/EntryTrans";
import axios from "axios";
import FormSearch from "@components/FormSearch.vue";
import Swal from "sweetalert2";
import Pagination from "@components/Pagination.vue";

// Propiedades
const propsW = defineProps<{
    products: productBaseI[],
    productTable: productI,
    entry_edit?: entryBaseI,
    entries: entryTableI
}>();

//datos de la ventana
const showProduct = ref<boolean>(false);
const productName = ref<string>();
const products = ref<productBaseI[] | null>(null);


// Formularios
const form = useForm({
    id:0,
    product_id: 0,
    quantity: 0,
    cost: 0,
    description:'',
    type:'ENTRADA',
    update: false,
});

const formSearch = useForm({
    search: '',
    perPage: 30
});




/*
Funciones
 */
const submit = ()=>{
    form.post(route('entry.store'),{
        onSuccess: () => {
            successHttp('Datos Registrado Correctamente');
            form.reset();
            productName.value = '';
        }
    });
}


/**
 * Evento watch
 */

/**
 * Pra buscar los datos por cada cambio
 */
watch(productName, (newValue) => {
    if (newValue && newValue?.length > 3) {
        axios.get(route('product.get.json',{search: productName.value}))
            .then(res => {
                products.value = res.data;
            })
            .catch(() => {
                errorHttp('Error al Obtenr los datos');
            });
    }
});


/**
 * Para los productos a los datos
 * @param item
 */
const getProduct = (item:productBaseI) => {
    form.product_id = item.id;
    productName.value = item.name;
    form.cost = item.cost;
}


/**
 *
 * @param item
 */
const getProductTable = (item:productBaseI) => {
    form.product_id = item.id;
    productName.value = item.name;
    form.cost = item.cost;
    //Para cerrar la ventana
    showProduct.value = false;
}

/**
 * editar los datos
 * @param item
 */
const edit = (item:entryProductI) => {
    productName.value = item.product.name;
    form.product_id = item.product.id;
    form.cost = item.cost;
    form.quantity = item.quantity;
    form.description = item.description ||  '';
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


</script>

<template>
    <Head title="Entrada"/>
    <AppLayout>
        <template #header>
            <TabLink
                :href="route('product.create')">
                Registrar
            </TabLink>
            <TabLink
                :active="true"
                :href="route('entry.index')">
                Entrada
            </TabLink>

            <TabLink
                :href="route('product.show')">
                Mostrar
            </TabLink>
        </template>
        <div class="max-w-[70rem] mx-auto">
            <form
                class="bg-blue-300 p-5 rounded-md grid grid-cols-2 gap-3 max-w-[70rem] "
                @submit.prevent="submit">
                <h3 class="title text-center col-span-full">
                    Entrada
                </h3>

                <!-- Productos -->
                <div>
                    <label
                        class="block"
                        for="product">Producto</label>
                    <div
                        class="relative">
                        <!-- Buscador de productos -->
                            <input
                                placeholder="Busca Cliente"
                                class="peer inputGeneral group w-full pr-12"
                                v-model="productName"
                                autocomplete="off"
                                type="search"
                                name="product"
                                id="product">
                            <!-- Para buscar los datos -->
                            <i
                                @click="showProduct = !showProduct"
                                class="bg-blue-700 absolute right-0 flex items-center inset-y-0 text-white px-3 rounded-md icon-efect fa-solid fa-magnifying-glass"></i>

<!--                        Mostrar los datos de la base de datos-->
                        <div
                            class=" opacity-0 -z-20 peer-focus:opacity-100 peer-focus:z-20 duration-300 ease-in absolute bg-blue-300 border rounded-md border-black w-full ">
                            <ul class="px-3">
                                <li
                                    class="hover:bg-blue-700 even:bg-blue-400 hover:text-white duration-300 truncate cursor-pointer border-b border-black"
                                    v-for="(item,index) in products"
                                    :key="index"
                                    @click="getProduct(item)">
                                    {{item.name}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


                <!-- Cantidad -->
                <div>
                    <label
                        class="block"
                        for="quantity">Cantidad</label>
                    <Money
                        class="inputGeneral w-full"
                        v-bind="moneyConfig"
                        v-model="form.quantity" />
                </div>
                <!-- Cantidad -->
                <div>
                    <label
                        class="block"
                        for="cost">Costo Unitario</label>
                    <Money
                        class="inputGeneral w-full"
                        v-bind="moneyConfig"
                        v-model="form.cost" />
                </div>

                <!-- Comentario -->
                <div class=" ">
                    <label
                        class="block"
                        for="description">
                        Comentario
                    </label>
                    <TextInput
                        class=" w-full"
                        v-model="form.description"/>
                </div>


                <!-- Boton para enviar -->
                <div class="col-span-full text-right">
                    <PrimaryButton>
                        Registrar
                    </PrimaryButton>
                </div>
            </form>

            <div class="mt-3 p-3 bg-blue-300 rounded-md">
                <FormSearch
                    v-model:search="formSearch.search"
                    v-model:total="formSearch.perPage"/>
                <table class="styleTable w-full mt-2">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Referencia</th>
                            <th>Fecha</th>
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
                            <td>{{item.quantity}}</td>
                            <td class="space-x-3">

                                <i
                                    @click="edit(item)"
                                    class="icon-efect fa-solid fa-pen-to-square"></i>
                                <i
                                    @click="destroy(item)"
                                    class="icon-efect fa-solid fa-trash"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination
                    :current-page="propsW.entries.meta?.current_page"
                    :next="propsW.entries.link?.next || ''"
                    :prev="propsW.entries.link?.next || '' "
                    :total-page="propsW.entries.meta?.to"/>
            </div>
        </div>




    </AppLayout>

       <!-- Para  Buscar mas productos -->
       <FloatBox
            header="Productos"
            @close="showProduct = !showProduct"
            v-if="showProduct">
            <FloatShowPro
                class="bg-blue-300 p-5"
                @select="getProductTable"
                :products="propsW.productTable"/>
        </FloatBox>

</template>
