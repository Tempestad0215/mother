<script setup lang="ts">
import {Head, useForm } from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import SecondaryButton from "@components/SecondaryButton.vue";
import FloatBox from "@components/FloatBox.vue";
import FloatShow from "@/Pages/Products/FloatShowPro.vue";
import {PropType, ref} from "vue";
import {productDataI, productI, productSaleI} from "@/Interfaces/Product";
import {formatNumber, getMoney} from "@/Global/Helpers";
import LinkHeader from "@components/LinkHeader.vue";
import Swal from "sweetalert2";



const props = defineProps({
    products: {
        type: Object as PropType<productI>,
        required: true
    }
});

const form = useForm({
    client_name:"",
    client_id:"",
    products:[] as productSaleI[],
    tax: 0.00,
    amount: 0.00,
    sub_total: 0.00,
    total: 0.00

});

const showProduct = ref(false);


//Funciones
const getData = (item:productDataI) => {

    //Obtener los datos de productos
    let products = form.products.find((el) => el.id === item.id);

    // Verificar si el producto exite en todo
    if (products?.id  === item.id)
    {
        products.quantity += 1;
        showProduct.value = false;

    }else{
        let productTax:number = item.price * item.tax_rate;

        form.products.push({
            id: item.id,
            name: item.name,
            quantity: 1,
            price: item.price - productTax,
            stock: 0.00,
            amount: 0.00,
            tax: 0.00,
            stockTotal: item.stock,
            tax_rate: item.tax_rate,
            product_tax: productTax
        });

        //Cerrar la ventana
        showProduct.value = false;
    }


    //Conseguir el index para poder realizar el calculo
    let index = form.products.findIndex((el) => el.id === item.id);

    //Calcular el indice
    totalAmount(index);

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

    // Sacar los datos del produtos
    let info:productSaleI = form.products[index];

    //Pasar los datos al formulario
    info.tax = info.product_tax * info.quantity;
    info.amount = info.price * info.quantity;

    //Calcular los totales
    totalSale();

}

// Calculo de los datos finales
const totalSale = () => {
    let totalTax:number = 0.00;
    let subTotal:number = 0.00;

    //Recorrer el array para realizar el calcuclo
    form.products.forEach((el, index) =>{
        totalTax += formatNumber(el.tax);
        subTotal += formatNumber(el.amount);
    });

    console.log(subTotal);

    //Calcular el total de todo
    form.tax = totalTax;
    form.sub_total = subTotal;
    form.total = totalTax + subTotal;

}

</script>

<template>

    <Head title="Sale" />
    <AppLayout>
        <template #header >

            <LinkHeader
                :href="route('product.create')">
                Registrar
            </LinkHeader>
            <LinkHeader
                :active="true"
                :href="route('product-sale.create')">
                Ventas
            </LinkHeader>
            <LinkHeader
                :href="route('product.show')">
                Mostrar
            </LinkHeader>
            <LinkHeader
                :href="route('product-in.create')">
                Entradas
            </LinkHeader>

        </template>

<!--        //contenido-->
        <div>

            <div class=" bg-gray-200 rounded-md p-5 mx-auto">
                <form
                    class=" max-w-3/5"
                    action="">
                    <div >
                        <input-label
                            for="product"
                            value="Cliente" />
                        <div class="flex space-x-5 ">
                            <TextInput
                                class=" w-[400px]"
                                placeholder="Cliente"/>
                            <SecondaryButton>
                                Clie...
                            </SecondaryButton>
                            <SecondaryButton
                                @click="showProduct = !showProduct">
                                Prod..
                            </SecondaryButton>

                        </div>

                        <div>
                            <table
                                class="w-full table-auto mt-4">
                                <thead>
                                <tr
                                    class="text-left">
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Itbis</th>
                                    <th>Importe</th>
                                    <th>Atc</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr
                                    class=" odd:bg-gray-200"
                                    v-for="(item, index) in form.products" :key="index">
                                    <td>{{index+1}}</td>
                                    <td>{{item.name}}</td>
                                    <td
                                        class=" w-[150px]">
                                        <input
                                            class=" border-none bg-transparent rounded-md h-8 bg-white w-4/5"
                                            @blur="totalAmount(index)"
                                            v-model="item.quantity"
                                            type="text">
                                    </td>
                                    <td
                                        class=" w-[150px]">
                                        {{ getMoney(item.price)}}
                                    </td>
                                    <td class=" w-[150px]">
                                        <span>
                                            {{ getMoney(item.tax)}}
                                        </span>
                                    </td>
                                    <td class=" w-[150px]">
                                        <span>
                                            {{ getMoney(item.amount)}}
                                        </span>
                                    </td>
                                    <td
                                        class="text-xl w-[50px]">
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
                                        <td>{{ getMoney(form.tax) }}</td>
                                    </tr>
                                    <tr>
                                        <th class=" text-left">Sub Total :</th>
                                        <td>{{getMoney(form.sub_total)}}</td>
                                    </tr>
                                    <tr>
                                        <th class=" text-left">Total :</th>
                                        <td>
                                            {{getMoney(form.total)}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </div>
                </form>

            </div>

            <!-- Ventana flotante -->
            <Transition>
                <FloatBox
                    @close="showProduct = false"
                    v-if="showProduct">
                    <FloatShow
                        class=" bg-amber-50 w-4/5 rounded-md px-10 py-5"
                        @select="getData"
                        :products="props.products"/>
                </FloatBox>
            </Transition>

        </div>

    </AppLayout>
</template>

