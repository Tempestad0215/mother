<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import SecondaryButton from "@components/SecondaryButton.vue";
import FloatBox from "@components/FloatBox.vue";
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";
import {ref} from "vue";
import {productDataI, productI} from "@/Interfaces/Product";
import {getMoney} from "@/Global/Helpers";
import LinkHeader from "@components/LinkHeader.vue";
import Swal from "sweetalert2";
import InputError from "@components/InputError.vue";
import {clientDataI, clientI} from "@/Interfaces/ClientInterface";
import FloatShowCli from "@/Pages/Clients/FloatShowCli.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {successHttp} from "@/Global/Alert";
import axios from "axios";
import SaleOpenShow from "@/Pages/ProductsSale/SaleOpenShow.vue";
import {saleDataI, saleDataPaginationI, saleInfoI} from "@/Interfaces/Sale";


/**
 * Datos del back end
 */
const propsW = defineProps<{
    products: productI,
    clients: clientI,
    pdf? : string,
    saleOpen : saleDataPaginationI
}>();


/**
 * Datos de la ventana
 */
const showClient = ref<boolean>(false);
const showProduct = ref(false);
const showSaleOpen = ref<boolean>(false);

/**
 * Formulario
 */
const form = useForm({
    id: 0,
    code_product:"",
    client_name:"",
    client_id: 0,
    info:[] as saleInfoI[],
    tax: 0.00,
    discount_amount: 0.00,
    amount: 0.00,
    sub_total: 0.00,
    comment:"",
    close_table: false,
    received: 0,
    returned: 0,
    general:"",
    update: false,
});

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

        //Pasar los datos al formulario
        form.info.push({
            id: item.id,
            code: item.code,
            name: item.name,
            quantity: 1,
            cost: item.cost,
            price: item.product_no_tax,
            stock: 0.00,
            amount: 0.00,
            discount: item.discount,
            discount_amount: 0.00,
            tax: item.tax,
            tax_amount: item.tax,
            tax_rate: item.tax_rate,
            product_tax: item.price,
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
        confirmButtonText: "Si, Eliminar!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed)
        {
            //Tomar datos la ventas
            let info:saleInfoI = form.info[index];

            //Eliminar el producto seleccionado
            form.info.splice(index,1);

            //Enviar los datos para actualizar
            form.patch(route('product-sale.destroy.item',{product: info.id, sale: form.id},{
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    successHttp(`Item : ${info.name} Eliminado Correctamente` );
                }
            }));

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
    let info:saleInfoI = form.info[index];
    let discountRate = info.discount / 100;

    //Pasar los datos al formulario
    info.tax_amount = parseFloat ((info.tax  * info.quantity).toFixed(2));
    info.amount = parseFloat ((info.price * info.quantity).toFixed(2));
    //Descuento datos
    info.discount_amount = parseFloat((info.amount * discountRate).toFixed(2));

    //Calcular los totales
    totalSale();

}

/**
 * Calculo el total de venta
 */
// Calculo de los datos finales
const totalSale = () => {

    //Tomar los datos
    let totalTax:number = 0.00;
    let subTotal:number = 0.00;
    let discount:number = 0.00;

    //Recorrer el array para realizar el calcuclo
    form.info.forEach((el) =>{
        totalTax += el.tax_amount;
        subTotal += el.amount;
        discount += el.discount_amount
    });


    //Calcular el total
    form.tax = parseFloat((totalTax).toFixed(2));
    form.sub_total = parseFloat((subTotal).toFixed(2)) ;
    form.discount_amount = parseFloat(discount.toFixed(2));
    form.amount = parseFloat(((totalTax + subTotal) - discount).toFixed(2));

    //calcular el retorno
    returned();

}

/**
 * Seleccionar el cliente
 * @param item
 */
//Seleccionar el cliente
const selectClient = (item:clientDataI) =>  {
    //Pasar los datos al formulario
    form.client_name = item.name;
    form.client_id = item.id;
    showClient.value = false;
}


/**
 * Enviar los datos
 */
const submit = () => {
    //Si van a cerrar verificar que lo recibido sea menor a
    if(form.received < form.amount && form.close_table)
    {
        form.setError('received','El monto recibido no puede ser menor al Total');
    }else{

        //si es para actualizar
        if (form.update)
        {
            //Enviar los datos para actualizar
            form.patch(route('product-sale.update',{sale: form.id}),{
                preserveState: true,
                preserveScroll: true,
                onSuccess:() =>{
                    successHttp('Documento Actualizado Correctamente');
                    form.reset();
                }
            });
        }else{

            //Guardar los datos por primera vez
            form.post(route('product-sale.store'),{
                onSuccess:()=>{
                    successHttp('Venta cerrada correctamente');
                    form.reset();
                    // readPDF(propsW.pdf);
                    //Actualizar la ventana
                },
                onError:()=>{
                    setTimeout(()=>{
                        form.clearErrors();
                    },5000)
                },
                only: ['products','clients','saleOpen'],
            });
        }


    }
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
            .catch(()=>{
                //Mensjae de que no existe en la base de datos
                form.setError('code_product','Este Producto no existe en la Base de Datos');
            })
    }
}

//Obtener los datos de las cuentas abiertas
const getSaleOpen = (item:saleDataI) => {

    //Colocar la variable en nada al principio
    form.info = [];
    form.id = item.id;
    form.update = true;
    //Verificar Pasar los datos a la variable
    item.info.map((el) => {
        //colocar la informacion en la lista
        form.info.push({
            id: el.id ? el.id : 0,
            code: el.code,
            name: el.name,
            quantity: el.quantity,
            cost: el.product_tax,
            price: el.price,
            stock: el.quantity,
            amount: el.amount,
            tax: el.tax,
            tax_amount: el.tax_amount,
            discount: el.discount,
            discount_amount: el.discount_amount,
            tax_rate: el.tax_rate,
            product_tax: el.product_tax,
        });

    });

    //calcular el total de las ventas
    totalSale();


    //colocar los datos en el formulario
    form.client_id = item.client_id;
    form.client_name = item.client_name;
    form.close_table = item.close_table;
    form.comment = item.comment ? item.comment : "";

    //Cerra la ventana
    showSaleOpen.value = false;

}


/**
 * Devuelta de cambio
 */
const returned = () => {
    form.returned = form.received  - form.amount;

    if(form.returned < 0)
    {
        form.setError('returned','El monto recibido no puede ser menor al Total');
        form.returned = 0;
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

            <LinkHeader
                :href="route('product-sale.show')">
                Mostrar
            </LinkHeader>

        </template>

<!--        //contenido-->
        <div>

            <div class=" bg-gray-200 rounded-md p-5 mx-auto overflow-hidden">

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
                                <SecondaryButton
                                    @click="showSaleOpen = !showSaleOpen">
                                    Abierta
                                </SecondaryButton>

                            </div>

                            <InputError :message="form.errors.client_name"/>

                        </div>


                        <div class=" grid grid-cols-3 gap-3 items-center">
                            <form
                                @submit.prevent="getBycode">
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
                            </form>


                            <fieldset class="flex border-2 border-gray-500 p-2 rounded-md max-w-[200px]">
                                <legend>
                                    Mesa
                                </legend>
                                <div>
                                    <input
                                        class="peer hidden"
                                        type="radio"
                                        name="open"
                                        :value="false"
                                        v-model="form.close_table"
                                        id="open">
                                    <label
                                        class="font-bold border-2 border-gray-500 px-2 py-1 rounded-md peer-checked:bg-gray-600 peer-checked:text-white duration-300"
                                        for="open">
                                        Abierta
                                    </label>
                                </div>


                                <div class="ml-5">
                                    <input
                                        class="peer hidden"
                                        type="radio"
                                        name="open"
                                        :value="true"
                                        v-model="form.close_table"
                                        id="close">
                                    <label
                                        class="font-bold border-2 border-gray-500 px-2 py-1 rounded-md peer-checked:bg-gray-600 peer-checked:text-white duration-300"
                                        for="close">
                                        Cerrada
                                    </label>

                                </div>
                            </fieldset>

                        </div>


                        <div>
                            <table
                                class="w-full table-auto mt-4">
                                <thead class="">
                                    <tr
                                        class="text-left border-b-2 border-gray-400">
                                        <th>#</th>
                                        <th>Producto/Servicio</th>
                                        <th>Cantidad</th>
                                        <th>Precio sin Itbis</th>
                                        <th>Itbis</th>
                                        <th>Desc.</th>
                                        <th>Importe</th>
                                        <th>Atc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class=" odd:bg-gray-300 border-2 border-b-gray-500"
                                        v-for="(item, index) in form.info" :key="index">
                                        <td>{{index+1}}</td>
                                        <td>
                                            <div>
                                                <p>{{item.code}}</p>
                                                <p>{{item.name}}</p>
                                            </div>

                                        </td>
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
                                                {{ getMoney(item.tax_amount)}}
                                            </span>
                                        </td>
                                        <td
                                            class=" w-[150px]">
                                            <input
                                                class=" border-none bg-transparent rounded-md h-8 bg-white w-4/5"
                                                @blur="totalAmount(index)"
                                                v-model="item.discount"
                                                type="number">
                                        </td>
                                        <td class=" w-[150px]">
                                            <span>
                                                {{ getMoney(item.amount) }}
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

                            <div>
                                <InputError :message="form.errors.info"/>
                            </div>


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
                                            {{ 255 - form.comment.length }}
                                        </span>

                                        <InputError :message="form.errors.comment"/>

                                    </fieldset>
                                </div>
                                <div class="">
                                    <div>
                                        <h5 class="inline font-bold">Itbis...................: </h5>
                                        <span class="">
                                            {{getMoney(form.tax)}}
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="inline font-bold">Sub Total.........: </h5>
                                        <span>
                                            {{getMoney(form.sub_total)}}
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="inline font-bold">Descuento......: </h5>
                                        <span>
                                            {{getMoney(form.discount_amount)}}
                                        </span>
                                    </div>
                                    <div>
                                        <h5 class="inline font-bold">Total.................: </h5>
                                        <span>
                                            {{getMoney(form.amount)}}
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class=" mt-5 w-64 float-right">
                            <div v-if="form.close_table">

                                <div>
                                    <strong>
                                        Devuelta :
                                    </strong>
                                    <span>
                                        {{getMoney(form.returned)}}
                                    </span>
                                </div>

                                <InputLabel
                                    class="text-left"
                                    for="received"
                                    value="Monto Recibido"/>
                                <TextInput
                                    class="w-full"
                                    type="number"
                                    required
                                    @blur="returned"
                                    v-model="form.received"/>
                                <InputError :message="form.errors.received"/>
                            </div>

                            <div class="mt-5">
<!--                                <SecondaryButton-->
<!--                                    type="button">-->
<!--                                    Limpiar-->
<!--                                </SecondaryButton>-->
                                <PrimaryButton
                                    @click="submit()"
                                    type="button">
                                    {{form.close_table ? 'Cerrar Venta' : 'Registrar'}}
                                </PrimaryButton>
                            </div>

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
                        class=" w-4/5 rounded-md px-10 py-5"
                        @get-data="selectClient"
                        :clients="propsW.clients"/>

                </FloatBox>
            </Transition>



            <!-- Ventana flotante -->
            <Transition>
                <FloatBox
                    @close="showProduct = false"
                    v-if="showProduct">
                    <FloatShowPro
                        class=" bg-gray-200 w-4/5 rounded-md px-10 py-5"
                        @select="getData"
                        :products="propsW.products"/>
                </FloatBox>
            </Transition>


            <!-- Vetana de las ordenes abierta -->
            <Transition>
                <FloatBox
                    v-if="showSaleOpen"
                    @close="showSaleOpen = false">
                    <SaleOpenShow
                        @sen-data="getSaleOpen"
                        class=" bg-gray-200 w-4/5 rounded-md px-10 py-5"
                        :sale-open="propsW.saleOpen"/>
                </FloatBox>
            </Transition>

        </div>

    </AppLayout>
</template>

