<script setup lang="ts">
import {Head, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import SecondaryButton from "@components/SecondaryButton.vue";
import FloatBox from "@components/FloatBox.vue";
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";
import {onMounted, PropType, ref} from "vue";
import {productDataI, productI, productSaleI} from "@/Interfaces/Product";
import {formatNumber, getMoney, readPDF} from "@/Global/Helpers";
import LinkHeader from "@components/LinkHeader.vue";
import Swal from "sweetalert2";
import InputError from "@components/InputError.vue";
import {clientDataI, clientI} from "@/Interfaces/ClientInterface";
import FloatShowCli from "@/Pages/Clients/FloatShowCli.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {successHttp} from "@/Global/Alert";
import axios from "axios";


/**
 * Datos del back end
 */
const props = defineProps({
    products: {
        type: Object as PropType<productI>,
        required: true
    },
    clients: {
        type: Object as PropType<clientI>,
        required: true
    },
    pdf: {
        type: String,
        default: ""
    }
});


/**
 * Datos de la ventana
 */
const showClient = ref<boolean>(false);
const showProduct = ref(false);


/**
 * Formulario
 */
const form = useForm({
    code_product:"",
    client_name:"",
    client_id: 0,
    info:[] as productSaleI[],
    tax: 0.00,
    discount: 0.00,
    amount: 0.00,
    sub_total: 0.00,
    total: 0.00,
    comment:"",

});


/**
 * Al momento de carga
 *
 */


/**
 * Funciones
 */

/**
 * funciones para obtener los datos de productos
 * @param item
 */
//Funciones
const getData = (item:productDataI) => {

    //Obtener los datos de productos
    let info = form.info.find((el) => el.id === item.id);

    // Verificar si el producto exite en todo
    if (info?.id  === item.id)
    {
        info.quantity += 1;
        showProduct.value = false;

    }else{
        let productTax:number = item.price * item.tax_rate;

        form.info.push({
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
    let index = form.info.findIndex((el) => el.id === item.id);

    //Calcular el indice
    totalAmount(index);

}


/**
 * Eliminar datos de la venta
 * @param name
 * @param index
 */
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
            form.info.splice(index);

            //REalizar el calculo de nuevo
            totalSale();
        }

    });
}

/**
 * Calcular el  itbis y otros datos de la ventana
 * @param index
 */
const totalAmount = (index:number) => {

    // Sacar los datos del produtos
    let info:productSaleI = form.info[index];

    //Pasar los datos al formulario
    info.tax = info.product_tax * info.quantity;
    info.amount = info.price * info.quantity;

    //Calcular los totales
    totalSale();

}

/**
 * Calculo el total de venta
 */
// Calculo de los datos finales
const totalSale = () => {
    let totalTax:number = 0.00;
    let subTotal:number = 0.00;

    //Recorrer el array para realizar el calcuclo
    form.info.forEach((el) =>{
        totalTax += formatNumber(el.tax);
        subTotal += formatNumber(el.amount);
    });


    //Calcular el total de todo
    form.tax = totalTax;
    form.sub_total = subTotal;
    form.total = totalTax + subTotal;

}

/**
 * Seleccionar el cliente
 * @param item
 */
//Seleccionar el cliente
const selectClient = (item:clientDataI) =>  {
    form.client_name = item.name;
    form.client_id = item.id;
    showClient.value = false;
}


/**
 * Enviar los datos
 */
const submit = () => {
    form.post(route('product-sale.store'),{
        onSuccess:()=>{
            successHttp('Venta cerrada correctamente');
            // form.reset();
            readPDF(props.pdf);
        }
    })
}


/**
 * Obtener el producto por codigo
 */
const getBycode = () => {

    //Verificar que tenga mas de 6 caracter
    if(form.code_product.length > 6)
    {
        //realizar la busqueda en automatico
        axios.get(route('product.get.code', {search: form.code_product}))
            .then((res) =>{
                //Formatear los datos
                const product:productDataI = res.data;
                //Pasar los datos al metodo
                getData(product);
                //Limpiar campo y errores en caso de tenerlo
                form.reset('code_product');
                form.clearErrors('code_product');
            })
            .catch((err)=>{
                //Mensjae de que no existe en la base de datos
                form.setError('code_product','Este Producto no existe en la Base de Datos');
            })
    }
}



</script>

<template>

    <Head title="Sale" />


    <AppLayout>
        <template #header >

            <LinkHeader
                :active="true"
                :href="route('product-sale.create')">
                Ventas
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

                        <div>
                            <div class="flex space-x-5 ">

                                <div class="relative">
                                    <TextInput
                                        class=" w-[400px]"
                                        v-model="form.client_name"
                                        placeholder="Cliente"/>
                                    <InputError
                                        :message="form.errors.client_id"/>
                                </div>

                                <SecondaryButton
                                    @click="showClient = !showClient">
                                    Clie...
                                </SecondaryButton>
                                <SecondaryButton
                                    @click="showProduct = !showProduct">
                                    Prod..
                                </SecondaryButton>

                            </div>

                            <InputError :message="form.errors.client_name"/>

                        </div>


                        <div>
                            <InputLabel
                                for="Product"
                                value="Codigo"/>

                            <TextInput
                                placeholder="Producto"
                                maxLength="15"
                                class="w-[400px]"
                                @blur="getBycode"
                                v-model="form.code_product"
                            />

                            <InputError :message="form.errors.code_product"/>


                        </div>


                        <div>
                            <table
                                class="w-full table-auto mt-4">
                                <thead class="">
                                    <tr
                                        class="text-left border-b-2 border-gray-400">
                                        <th>#</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio sin Itbis</th>
                                        <th>Itbis</th>
                                        <th>Importe</th>
                                        <th>Atc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class=" odd:bg-gray-300 border-2 border-b-gray-500"
                                        v-for="(item, index) in form.info" :key="index">
                                        <td>{{index+1}}</td>
                                        <td>{{item.name}}</td>
                                        <td
                                            class=" w-[150px]">
                                            <input
                                                class=" border-none bg-transparent rounded-md h-8 bg-white w-4/5"
                                                @blur="totalAmount(index)"
                                                v-model="item.quantity"
                                                type="number">
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



                            <div class="grid grid-cols-4 items-center gap-4 mt-5">
                                <div class=" col-span-3">
                                    <fieldset class=" relative max-w-[400px]">
                                        <legend>
                                            Comentario
                                        </legend>
                                        <textarea
                                            title="Comentario de la venta"
                                            class="border-gray-300 rounded-md min-h-[190px] max-h-[190px]"
                                            name="note"
                                            placeholder="Escribe tu comentario"
                                            v-model="form.comment"
                                            maxlength="300"
                                            id="note"
                                            cols="50"
                                            rows="3">

                                        </textarea>

                                        <span
                                            class=" absolute inset-y-0 right-3 text-red-400">
                                            {{ 300 - form.comment.length }}
                                        </span>

                                        <InputError :message="form.errors.comment"/>

                                    </fieldset>
                                </div>
                                <div class="">
                                    <div>
                                        <h5 class="inline font-bold">Itbis.............: </h5>
                                        <span class="">
                                            {{getMoney(form.tax)}}
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="inline font-bold">Sub Total...: </h5>
                                        <span>
                                            {{getMoney(form.sub_total)}}
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="inline font-bold">Total............: </h5>
                                        <span>
                                            {{getMoney(form.total)}}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class=" text-right mt-5 space-x-3">
                            <SecondaryButton
                                type="button">
                                Limpiar
                            </SecondaryButton>
                            <PrimaryButton
                                @click="submit()"
                                type="button">
                                Cerrar venta
                            </PrimaryButton>
                        </div>

                    </div>
                </form>

            </div>


            <!-- Mostrar flotante los clientes --->
            <Transition>
                <FloatBox
                    @close="showClient = false"
                    v-if="showClient">
                    <FloatShowCli
                        class=" bg-amber-50 w-4/5 rounded-md px-10 py-5"
                        @get-data="selectClient"
                        :clients="props.clients"/>

                </FloatBox>
            </Transition>



            <!-- Ventana flotante -->
            <Transition>
                <FloatBox
                    @close="showProduct = false"
                    v-if="showProduct">
                    <FloatShowPro
                        class=" bg-amber-50 w-4/5 rounded-md px-10 py-5"
                        @select="getData"
                        :products="props.products"/>
                </FloatBox>
            </Transition>

        </div>

    </AppLayout>
</template>

