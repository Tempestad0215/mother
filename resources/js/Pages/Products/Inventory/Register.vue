<script lang="ts" setup>
import {productBaseI, productI} from '@/Interfaces/Product';
import TabLink from '@components/TabLink.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import {ref, watch} from 'vue';
import {errorHttp, successHttp} from "@/Global/Alert";
import {entryBaseI, entryProductI} from "@/Interfaces/EntryTrans";
import axios from "axios";
import Swal from "sweetalert2";
import {paginationI} from "@/Interfaces/Global";
import FRegister from "@/Pages/Products/Inventory/FRegister.vue";
import FShowEntrie from "@/Pages/Products/Inventory/FShow.vue";

// Propiedades
const propsW = defineProps<{
    products: productBaseI[],
    productTable: productI,
    entry_edit?: entryBaseI,
    entries: paginationI<entryProductI>
}>();

//datos de la ventana
const showProduct = ref<boolean>(false);
const productName = ref<string>();
const products = ref<productBaseI[] | null>(null);
const editData = ref<entryProductI | null>(null);


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

const edit = (item:entryProductI) => {
    editData.value = item;


    console.log(editData.value)
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
        <div class="">
<!--            Mostrar el formulario de registro-->
            <FRegister
                :edit-data-float="editData"
                :products="propsW.products"
                :product-table="propsW.productTable"
                :entries="propsW.entries"/>

<!--            Mostrar el formulario de taka-->
            <FShowEntrie
                @edit="edit"
                :entries="propsW.entries"/>
        </div>
    </AppLayout>
</template>
