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
import { ref } from 'vue';
import {successHttp} from "@/Global/Alert";
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";

// Propiedades
const propsW = defineProps<{
    products: productBaseI[],
    productTable:productI
}>();

//datos de la ventana
const showProduct = ref<boolean>(false);


// Formularios
const form = useForm({
    product_id: 0,
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
                class="bg-blue-300 p-5 rounded-md grid grid-cols-3 gap-3 max-w-[70rem]"
                @submit.prevent="submit">
                <h3 class="title text-center col-span-full">
                    Entrada
                </h3>

                <!-- Productos -->
                <div>
                    <label
                        class="block"
                        for="product">Producto</label>
                    <div>
                        <!-- Buscador de productos -->
                        <select
                            v-model="form.product_id"
                            class="inputGeneral py-1 w-[80%]"
                            name="product"
                            id="product">
                            <option :value="0">--- Seleccione ----</option>
                            <option
                                v-for="(item, index) in propsW.products" :key="index"
                                :value="item.id">
                                {{ item.name }}
                            </option>
                        </select>
                        <!-- Para buscar los datos -->
                        <i
                            @click="showProduct = !showProduct"
                            class="bg-blue-700 text-white px-3 rounded-md icon-efect ml-3 fa-solid fa-magnifying-glass"></i>
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
        </div>

    </AppLayout>

       <!-- Para  Buscar mas productos -->
       <FloatBox
            header="Productos"
            @close="showProduct = !showProduct"
            v-if="showProduct">
            <FloatShowPro
                class="bg-blue-300 p-5"
                :products="propsW.productTable"/>
        </FloatBox>

</template>
