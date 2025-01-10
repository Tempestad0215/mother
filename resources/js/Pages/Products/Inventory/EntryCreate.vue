<script lang="ts" setup>
import { moneyConfig } from '@/Global/Helpers';
import { productBaseI } from '@/Interfaces/Product';
import FloatBox from '@components/FloatBox.vue';
import InputError from '@components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import { formToJSON } from 'axios';
import { Money } from 'v-money3';
import { ref } from 'vue';

// Propiedades
const propsW = defineProps<{
    products: productBaseI[]
}>();

//datos de la ventana
const showProduct = ref<boolean>(false);


// Formularios
const form = useForm({
    product_id: 0,
    quantity: 0,
    cost: 0,
    description:"",
});





</script>

<template>
    <Head title="Entrada"/>
    <AppLayout>
        <template #header>

        </template>
        <div>
            <form
                class="bg-blue-300 p-5 rounded-md" 
                action="">
                <h3 class="title text-center">
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
                            class="inputGeneral py-1"
                            name="product" 
                            id="product">
                            <option value="">--- Seleccione ----</option>
                            <option
                                v-for="(item, index) in propsW.products" :key="index" 
                                :value="item.id">
                                {{ item.name }}
                            </option>
                        </select>
                        <!-- Para buscar los datos -->
                        <i 
                            @click="showProduct = !showProduct"
                            class="icon-efect ml-3 p-1 fa-solid fa-magnifying-glass"></i>
                    </div>
                    <InputError :message="form.errors?.product_id"/>
                </div>


                <!-- Cantidad -->
                <div>
                    <label
                        class="block" 
                        for="quantity">Cantidad</label>
                    <Money
                        class="inputGeneral" 
                        v-bind="moneyConfig"  
                        v-model="form.quantity" />
                </div>
                <!-- Cantidad -->
                <div>
                    <label
                        class="block" 
                        for="cost">Costo Unitario</label>
                    <Money
                        class="inputGeneral" 
                        v-bind="moneyConfig"  
                        v-model="form.quantity" />
                </div>
            </form>
        </div>

    </AppLayout>

       <!-- Para  Buscar mas productos -->
       <FloatBox
            header="Productos"
            @close="showProduct = !showProduct"
            v-if="showProduct">
            <div>
                asdunasdasdlasdl;
            </div>
        </FloatBox>
    
</template>