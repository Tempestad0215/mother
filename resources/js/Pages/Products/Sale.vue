<script setup lang="ts">
import {Head, useForm } from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import HeaderBox from "@components/HeaderBox.vue";
import NavLink from "@components/NavLink.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import SecondaryButton from "@components/SecondaryButton.vue";
import ContentBox from "@components/ContentBox.vue";
import FloatBox from "@components/FloatBox.vue";
import FloatShow from "@/Pages/Products/FloatShowPro.vue";
import {PropType, ref} from "vue";
import {productDataI, productI, productSaleI} from "@/Interfaces/Product";
import {formatNumber, moneyConfig} from "@/Global/Helpers";
import Swal from "sweetalert2";



const props = defineProps({
    products: {
        type: Object as PropType<productI>,
        required: true
    }
});

const form = useForm({
    products:[] as productSaleI[]
});

const showProduct = ref(false);


//Funciones
const getData = (item:productDataI) => {
    //Obtener los datos de productos
    let products = form.products.find((el) => el.id === item.id);

    if (products?.id  === item.id)
    {
        let stock:number =  formatNumber(products.stock);
        products.stock = (stock + 1).toFixed(2);
        showProduct.value = false;

    }else{
        form.products.push({
            id: item.id,
            name: item.name,
            price: item.price,
            stock: "0.00",
            amount:"0.00",
            tax: "0.00"
        });

        //Cerrar la ventana
        showProduct.value = false;
    }
}

const deleteItem =(name:string , index:number) => {
    Swal.fire({
        title: `Desea eliminar registro : ${name}?`,
        text: "Los cambios realizados son irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Elimianr!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed)
        {
            //Eliminar el producto seleccionado
            form.products.splice(index);
        }

    });
}

const totalAmount = (index:number) => {
    let info:productSaleI = form.products[index];
    let price = formatNumber(info.price) * 100;
    let stock = formatNumber(info.stock);
    info.amount = ((price * stock) / 100).toFixed(2);

}



</script>

<template>
    <Head title="sale" />
    <AppLayout>
        <template #header >
            <HeaderBox>
                <h2>
                    Orden de producto
                </h2>
                <template #link >
                    <NavLink

                        :href="route('product.create')" >
                        Registrar
                    </NavLink>
                    <NavLink
                        :active="true"
                        :href="route('product.sale')" >
                        Venta
                    </NavLink>
                    <NavLink
                        :href="route('product.show')" >
                        Mostrar
                    </NavLink>
                    <NavLink
                        :href="route('product-in.create')" >
                        Entrada
                    </NavLink>
                </template>
            </HeaderBox>
        </template>

<!--        //contenido-->
        <div>
            <ContentBox class=" md:max-w-5xl">
                <form
                    class=" max-w-3/5"
                    action="">
                    <div >
                        <input-label
                            for="product"
                            value="Productos" />
                        <div class="flex space-x-5 ">
                            <TextInput
                                class=" w-full flex-1"
                                placeholder="Producto"/>
                            <SecondaryButton
                                @click="showProduct = !showProduct">
                                Prod..
                            </SecondaryButton>

                        </div>

                        <div>
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="text-left">
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th >Importe</th>
                                        <th>Atc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class=" odd:bg-gray-200"
                                        v-for="(item, index) in form.products" :key="index">
                                        <td>{{item.name}}</td>
                                        <td>{{item.price}}</td>
                                        <td class=" w-[150px]">
                                            <input
                                            v-money="moneyConfig"
                                            v-model="item.stock"
                                            @blur="totalAmount(index)"
                                            class=" border-none bg-transparent rounded-md h-8"
                                            type="text">
                                        </td>
                                        <td class=" w-[150px]">
                                            <input
                                                v-money="moneyConfig"
                                                v-model="item.amount"
                                                @blur="totalAmount(index)"
                                                readonly
                                                class=" focus:ring-0  border-none bg-transparent rounded-md h-8"
                                                type="text">
                                        </td>
                                        <td
                                            class="text-xl">
                                            <i
                                                @click="deleteItem(item.name, index)"
                                                class=" icon-efect text-red-500 fa-solid fa-circle-xmark"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="my-4">
                                <hr>
                            </div>

                            <div class=" flex justify-end">
                                <table class="">
                                    <tbody>
                                    <tr>
                                        <th class=" text-left">Itbis :</th>
                                        <td>12,213.20</td>
                                    </tr>
                                    <tr>
                                        <th class=" text-left">Sub Total :</th>
                                        <td>12,213.20</td>
                                    </tr>
                                    <tr>
                                        <th class=" text-left">Total :</th>
                                        <td>12,213.20</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                </form>
            </ContentBox>

<!--            Ventana flotante -->
            <Transition>
                <FloatBox
                    @close="showProduct = false"
                    v-if="showProduct">
                    <FloatShow
                        class=" bg-amber-50 w-3/5 rounded-md px-10 py-5"
                        @select="getData"
                        :products="props.products"/>
                </FloatBox>
            </Transition>

        </div>

    </AppLayout>
</template>

