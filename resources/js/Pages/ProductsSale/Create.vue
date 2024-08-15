<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import SecondaryButton from "@components/SecondaryButton.vue";
import FloatBox from "@components/FloatBox.vue";
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";
import { PropType, ref} from "vue";
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
import SaleOpenShow from "@/Pages/ProductsSale/SaleOpenShow.vue";
import {saleDataI, saleDataPaginationI} from "@/Interfaces/Sale";


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
    },
    saleOpen:{
        type: Object as PropType<saleDataPaginationI>,
        required: true
    }
});


/**
 * Datos de la ventana
 */
const showClient = ref<boolean>(false);
const showProduct = ref(false);
const showSaleOpen = ref<boolean>(false);
const productCheck = ref<productDataI[]>([]);


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
    close_table: false

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
        let productTax:number = item.price * item.tax_rate;

        console.log(productTax);
        //Pasar los datos al formulario
        form.info.push({
            id: item.id,
            name: item.name,
            quantity: 1,
            price: item.price - productTax,
            stock: 0.00,
            amount: 0.00,
            tax: productTax,
            total_tax: 0.00,
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
    info.total_tax = info.tax * info.quantity;
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

        console.log( JSON.stringify(el));
        totalTax += formatNumber(el.total_tax);
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
    //Verificar Pasar los datos a la variable
    item.info.map((el,index) => {
        //Busca el product coincidente
        let propsData = props.products?.data.find((p) => p.id === el.id);

        //Pasar los datos al producto
        if(propsData)
        {
            productCheck.value.push(propsData);
        }

        //colocar la informacion en la lista
        form.info.push({
            id: el.id ? el.id : 0,
            name: el.name,
            quantity: el.quantity,
            price: el.price,
            stock: productCheck.value[index].stock,
            amount: el.amount,
            tax: el.tax,
            total_tax: el.total_tax,
            tax_rate: el.tax_rate,
            stockTotal: 0,
            product_tax: el.price,
        });

    });

    //Calcular el total de venta
    totalSale();


    //colocar los datos en el formulario
    form.client_id = item.client_id;
    form.client_name = item.client_name;
    form.close_table = item.close_table;
    form.comment = item.comment ? item.comment : "";

    //Cerra la ventana
    showSaleOpen.value = false;

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
                                <SecondaryButton
                                    @click="showSaleOpen = !showSaleOpen">
                                    Abierta
                                </SecondaryButton>

                            </div>

                            <InputError :message="form.errors.client_name"/>

                        </div>


                        <div class=" grid grid-cols-3 gap-3 items-center">
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
                            <div class="flex">



                            </div>



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
                                                {{ getMoney(item.total_tax)}}
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
                                {{form.close_table ? 'Cerrar Venta' : 'Registrar'}}
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


            <!-- Vetana de las ordenes abierta -->
            <Transition>
                <FloatBox
                    v-if="showSaleOpen"
                    @close="showSaleOpen = false">
                    <SaleOpenShow
                        @sen-data="getSaleOpen"
                        class=" bg-gray-200 w-4/5 rounded-md px-10 py-5"
                        :sale-open="props.saleOpen"/>
                </FloatBox>
            </Transition>

        </div>

    </AppLayout>
</template>

