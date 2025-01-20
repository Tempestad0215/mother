<script lang="ts" setup>
import { moneyConfig } from '@/Global/Helpers';
import {productBaseI, productI} from '@/Interfaces/Product';
import FloatBox from '@components/FloatBox.vue';
import PrimaryButton from '@components/PrimaryButton.vue';
import TabLink from '@components/TabLink.vue';
import TextInput from '@components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import { Money } from 'v-money3';
import {ref, watch} from 'vue';
import {errorHttp, successHttp} from "@/Global/Alert";
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";
import {EntryBaseI} from "@/Interfaces/EntryTrans";
import axios from "axios";
import InputError from "@components/InputError.vue";

// Propiedades
const propsW = defineProps<{
    products: productBaseI[],
    productTable:productI,
    entry_edit?: EntryBaseI,
}>();

//datos de la ventana
const showProduct = ref<boolean>(false);
const productName = ref<string>();
const products = ref<productBaseI[] | null>(null);


// Formularios
const form = useForm({
    product_id: 0,
    product_name: '',
    quantity: 0,
    cost: 0,
    description:'',
    type:'ENTRADA',
});




/*
Funciones
 */
const submit = ()=>{
    form.post(route('entry.store'),{
        onSuccess: () => {
            successHttp('Datos Registrado Correctamente');
            form.reset();
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
        axios.get(route('product.get.json',{search: form.product_name}))
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
        <div>
            <form
                class="bg-blue-300 p-5 rounded-md grid grid-cols-3 gap-3 max-w-[70rem] "
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
                            class="peer inputGeneral w-[80%]"
                            v-model="productName"
                            type="search"
                            name="product"
                            id="product">
                        <!-- Para buscar los datos -->
                        <i
                            @click="showProduct = !showProduct"
                            class="bg-blue-700 text-white px-3 rounded-md icon-efect ml-3 fa-solid fa-magnifying-glass"></i>

                        <div
                            class="opacity-0 -z-20 peer-focus:opacity-100 peer-focus:z-20 duration-300 ease-in absolute bg-blue-300 border rounded-md border-black w-[85%]">
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
                    <InputError :message="form.errors.product_name"/>
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
