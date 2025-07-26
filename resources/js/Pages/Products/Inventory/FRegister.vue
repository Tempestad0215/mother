<script setup lang="ts">
import {moneyConfig} from "@/Global/Helpers";
import TextInput from "@components/TextInput.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import InputError from "@components/InputError.vue";
import {Money} from "v-money3";
import {errorHttp, successHttp} from "@/Global/Alert";
import {productBaseI, productI} from "@/Interfaces/ProductInterface";
import {ref, watch} from "vue";
import axios from "axios";
import {useForm} from "@inertiajs/vue3";
import {entryBaseI, entryProductI} from "@/Interfaces/EntryTransInterface";
import {paginationI} from "@/Interfaces/GlobalInterface";
import InputLabel from "@components/InputLabel.vue";
import FloatBox from "@components/FloatBox.vue";
import FShow from "@/Pages/Products/FShow.vue";


// Propiedades
const propsW = defineProps<{
    products: productBaseI[],
    productTable: productI,
    entryEdit?: entryBaseI ,
    entries: paginationI<entryBaseI>
    editDataFloat?: entryProductI
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


watch(()=> propsW.editDataFloat, (newValue) => {

    form.id = newValue?.id || 0;
    form.product_id = newValue?.product.id || 0;
    productName.value = newValue?.product.name || "";
    form.quantity = newValue?.quantity || 0;
    form.cost = newValue?.cost || 0;
    form.description = newValue?.description || "";
    form.type = "AJUSTE";
    form.update = true;
});

/*
Funciones
 */
const submit = ()=>{
    if(form.update){
        form.patch(route('entry.update',{entry: form.id}))
    }else{
        form.post(route('entry.store'),{
            onSuccess: () => {
                successHttp('Datos Registrado Correctamente');
                form.reset();
                productName.value = '';
            }
        });
    }
}

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
    <form
        class="fondo p-5 rounded-md grid grid-cols-2 gap-3 "
        @submit.prevent="submit">
        <h3 class="title text-center col-span-full">
            Entrada
        </h3>

        <!-- Productos -->
        <div>
            <InputLabel
                for="product"
                value="Producto"/>
            <div
                class="relative">
                <!-- Buscador de productos -->
                <input
                    placeholder="Busca Cliente"
                    class="peer inputGeneral group w-[90%]"
                    v-model="productName"
                    autocomplete="off"
                    type="search"
                    name="product"
                    id="product">
                <!-- Para buscar los datos -->
                <i
                    @click="showProduct = !showProduct"
                    class="bg-cyan-400 ml-2  px-3 py-auto rounded-md icon-efect fa-solid fa-magnifying-glass"></i>

                <!--                        Mostrar los datos de la base de datos-->
                <div
                    class=" opacity-0 -z-20 peer-focus:opacity-100 peer-focus:z-20 duration-300 ease-in absolute bg-gray-800 text-white rounded-md border w-full ">
                    <ul class="rounded-md">
                        <li
                            class="hover:bg-cyan-400 even:bg-cyan-300 hover:text-black duration-300 truncate cursor-pointer border-b border-black rounded-md"
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
            <InputLabel
                for="quantity"
                value="Cantidad"/>
            <Money
                class="inputGeneral w-full"
                v-bind="moneyConfig"
                v-model="form.quantity" />
            <InputError :message="form.errors?.quantity" />
        </div>
        <!-- Cantidad -->
        <div>
            <InputLabel
                for="cost"
                value="Costo x Unidad"/>
            <Money
                class="inputGeneral w-full"
                v-bind="moneyConfig"
                v-model="form.cost" />
            <InputError :message="form.errors?.cost" />
        </div>

        <!-- Comentario -->
        <div class=" ">
            <InputLabel
                for="description"
                value="Comentario"/>
            <TextInput
                class=" w-full"
                placeholder="Artilculo recien llegado"
                v-model="form.description"/>
            <InputError :message="form.errors?.description" />
        </div>


        <!-- Boton para enviar -->
        <div class="col-span-full text-right">
            <PrimaryButton>
                Registrar
            </PrimaryButton>
        </div>
    </form>

    <!-- Para  Buscar mas productos -->
    <FloatBox
        class="z-30"
        header="Productos"
        @close="showProduct = !showProduct"
        v-if="showProduct">
        <FShow
            class="fondo p-5 w-[70rem]"
            @select="getProductTable"
            :products="propsW.productTable"/>
    </FloatBox>
</template>

<style scoped>

</style>
