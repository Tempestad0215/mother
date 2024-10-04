<script setup lang="ts">
import {Head, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import SecondaryButton from "@components/SecondaryButton.vue";
import FloatBox from "@components/FloatBox.vue";
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";
import {onMounted, Ref, ref} from "vue";
import {productDataI, productI} from "@/Interfaces/Product";
import {formatNumber, getMoney, getSequenceType} from "@/Global/Helpers";
import LinkHeader from "@components/LinkHeader.vue";
import Swal from "sweetalert2";
import InputError from "@components/InputError.vue";
import {clientDataI, clientI} from "@/Interfaces/ClientInterface";
import FloatShowCli from "@/Pages/Clients/FloatShowCli.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {successHttp} from "@/Global/Alert";
import axios from "axios";
import SaleOpenShow from "@/Pages/ProductsSale/SaleOpenShow.vue";
import {infoSaleI, saleDataI, saleDataPaginationI} from "@/Interfaces/Sale";
import {invoiceTypeI, sequenceDataI} from "@/Interfaces/Setting";



const {setting} = usePage().props;



/**
 * Datos del back end
 */
const propsW = defineProps<{
    products: productI,
    clients: clientI,
    pdf? : string,
    saleOpen : saleDataPaginationI,
    invoiceType: invoiceTypeI[]
}>();



/*
al momento de cargar
 */
onMounted(() => {
   getSequence("B02");
});



/**
 * Datos de la ventana
 */
const showClient:Ref<boolean> = ref<boolean>(false);
const showProduct:Ref<boolean> = ref(false);
const showSaleOpen:Ref<boolean> = ref<boolean>(false);
const sequenceData:Ref<sequenceDataI | null> = ref(null);


/**
 * Formulario
 */
const form = useForm({
    id: 0,
    code_product:"",
    client_name:"",
    client_id: 0,
    info:[] as infoSaleI[],
    tax: 0.00,
    discount_amount: 0.00,
    amount: 0.00,
    sub_total: 0.00,
    comment:"",
    comment_id:"",
    close_table: false,
    received: 0,
    returned: 0,
    general:"",
    type: "ventas",
    update: false,
    sequence_type:"",
    sequence:"",
    invoice_type:"B02"
});

/*
Funciones
 */

/**
 * Obtener los datos de la sequencia
 */

const getSequence = async (type: string) => {

    //Realizar la buqueda
    const result = await axios.get(route('sequence.get', {type: type}));

    //Verificar si la secuencia es correcta
    if (result.status === 200 &&  typeof(result.data) ==='object')
    {
        //Pasar los datos a las variables
        sequenceData.value  = result.data || null;

        //Obtner el tipo de secuencia
        form.sequence_type = getSequenceType(type);

        //Asegurar de que los datos existan
        if (sequenceData.value && sequenceData.value.type && sequenceData.value.next != undefined )
        {
            form.sequence = sequenceData.value.type+sequenceData.value.next.toString().padStart(8, '0');
        }
        //Crear la secuencia

    }else{
        //Mensaje de error
        form.setError("sequence", "Este Comprobante No Puedo Ser");
    }

}


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

        console.log(item);

        //Pasar los datos al formulario
        form.info.push({
            id: item.id,
            code: item.code,
            name: item.name,
            quantity: 1,
            cost: item.cost,
            price: item.price,
            stock: 0.00,
            amount: 0.00,
            type: item.type,
            discount: item.discount,
            discount_amount: 0.00,
            tax_rate: item.tax_rate / 100,
            tax: item.tax,
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
const deleteItem = async (name:string , index:number) => {
    //Tomar el resultado si vas a eliminar
    const result = await Swal.fire({
        title: `Desea eliminar registro : ${name}?`,
        text: "Los cambios realizados son irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
        cancelButtonText: "Cancelar"
    });

    //Verificar si se ha confirmado
    if(result.isConfirmed)
    {
        if (result.isConfirmed)
        {
            //Tomar datos la ventas
            let info:saleInfoI = form.info[index];

            //Eliminar el producto seleccionado
            form.info.splice(index,1);

            if(form.id !== 0)
            {
                //Enviar los datos para actualizar
                form.patch(route('sale.destroy.item',{product: info.id, sale: form.id},{
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        successHttp(`Item : ${info.name} Eliminado Correctamente` );
                    }
                }));
            }

            //REalizar el calculo de nuevo
            totalSale();
        }
    }

}

/**
 * Calcular el  itbis y otros datos de la ventana
 * @param index
 */
const totalAmount = (index:number) => {

    // Sacar los datos del produtos
    let info:infoSaleI = form.info[index];
    let discountRate = info.discount / 100;

    //Pasar los datos al formulario
    info.tax_amount = parseFloat ((info.tax  * info.stock).toFixed(2));
    info.amount = parseFloat ((info.price * info.stock).toFixed(2));
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
    form.tax = parseFloat((totalTax).toFixed(4));
    form.sub_total = parseFloat((subTotal).toFixed(4)) ;
    form.discount_amount = parseFloat(discount.toFixed(4));
    form.amount = parseFloat(subTotal - discount).toFixed(4);

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
            form.patch(route('sale.update',{sale: form.id}),{
                preserveState: true,
                preserveScroll: true,
                onSuccess:() =>{
                    successHttp('Documento Actualizado Correctamente');
                    form.reset();
                }
            });
        }else{

            //Guardar los datos por primera vez
            form.post(route('sale.store'),{
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
    item.info_sale.map((el, index) => {
        //colocar la informacion en la lista
        form.info.push({
            id: el.id ? el.id : 0,
            code: el.code,
            name: el.name,
            price: el.price,
            stock: el.stock,
            amount: el.amount,
            tax: formatNumber(el.tax),
            tax_amount: el.tax_amount,
            discount: el.discount,
            discount_amount: el.discount_amount,
            tax_rate: el.tax_rate,
            product_tax: el.product_tax,
        });

        totalAmount(index);

    });

    //calcular el total de las ventas
    totalSale();


    //colocar los datos en el formulario
    form.client_id = item.client_id;
    form.client_name = item.client_name;
    form.close_table = item.close_table;
    form.comment = item.comment.content ?? "";
    form.comment_id = item.comment.id ?? 0;

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
<!--Titulo de la ventana-->
    <Head title="Sale" />
<!--    Contenido general-->
    <AppLayout>

<!--        Cabecera de la ventana-->
        <template #header >

            <LinkHeader
                :active="true"
                :href="route('sale.create')">
                Ventas
            </LinkHeader>

            <LinkHeader
                :href="route('sale.show')">
                Mostrar
            </LinkHeader>

        </template>

<!--        //contenido-->
        <div>

            <div class=" bg-gray-200 p-5 max-w-[1180px] rounded-md mx-auto overflow-hidden">

                <form
                    class=" max-w-3/5"
                    action="">
                    <div >


                        <div class="flex">
                            <div>
<!--                                Botones para buscar datos-->
                                <div class="flex space-x-5 items-center ">

                                    <div class="relative">
                                        <input-label
                                            for="product"
                                            value="Cliente" />

                                        <div class="relative">
                                            <TextInput
                                                class=" w-[400px] pr-10"
                                                v-model="form.client_name"
                                                placeholder="Cliente"/>
<!--                                            Colocar al lado esto-->
                                            <div
                                                class="absolute inset-y-0 right-0 flex items-center">
                                                <i
                                                    title="Buscar Cliente"
                                                    @click="showClient = !showClient"
                                                    class=" icon-efect text-2xl pr-3 fa-solid fa-magnifying-glass-plus"></i>
                                            </div>
                                        </div>

                                        <InputError
                                            :message="form.errors.client_id"/>
                                    </div>
                                </div>
                                <InputError :message="form.errors.client_name"/>
                            </div>

                            <div
                                class=" flex-1 flex justify-end animate-pulse "
                                v-if="form.sequence_type == ''">
                                Cargando....
                            </div>

                            <div
                                class=" flex-1 flex justify-end"
                                v-if="form.sequence_type !== ''">
<!--                                Mensaje de cargando-->
                                <!--Numero de comprobantes-->
                                <fieldset
                                    class=" border-2 border-gray-400 rounded-md px-2 mx-3 max-w-[400px]">
                                    <legend>
                                        Datos Tributario
                                    </legend>
                                    <p><strong>RNC :</strong> {{form.sequence}}</p>
                                    <p><strong>Razon Social :</strong> {{form.sequence}}</p>
                                </fieldset>

                                <!--Numero de comprobantes-->
                                <fieldset class="border-2 border-gray-400 rounded-md px-2 ">
                                    <legend>
                                        {{form.sequence_type}}
                                    </legend>
                                    <p class="truncate"><strong>NCF :</strong> {{form.sequence}}</p>
                                </fieldset>
                            </div>

                        </div>

<!--                        Datos del formulario-->
                        <div class=" flex justify-between items-center mt-3">
                            <div class="flex">
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
                                <!--                            Buscar los datos necesario-->
                                <div class="ml-3">
                                    <InputLabel value="Datos"/>
                                    <SecondaryButton
                                        class="ml-3"
                                        @click="showProduct = !showProduct">
                                        Prod..
                                    </SecondaryButton>
                                    <SecondaryButton
                                        class="ml-3"
                                        @click="showSaleOpen = !showSaleOpen">
                                        Abierta
                                    </SecondaryButton>
                                </div>
                            </div>




                            <div class="flex">

                                <!--                        Tipo de factura-->
                                <div class="ml-3">
                                    <InputLabel for="type" value="Tipo de Factura"/>
                                    <select
                                        v-model="form.invoice_type"
                                        class="border-gray-200 rounded-md"
                                        name="type"
                                        id="type">
                                        <option
                                            v-for="(item, index) in propsW.invoiceType" :key="index"
                                            :value="item.type">{{ item.name }}</option>
                                        <!--                                        <option value="">Credito</option>-->
                                    </select>
                                    <InputError :message="form.errors.invoice_type"/>
                                </div>

                                <!--                        Tipo de factura-->
                                <div class="ml-3">
                                    <InputLabel for="type" value="Tipo de Venta"/>
                                    <select
                                        title="Tipo de Venta"
                                        v-model="form.type"
                                        class="border-gray-200 rounded-md"
                                        name="type"
                                        id="type">
                                        <option value="ventas">Ventas</option>
                                        <option value="contizacion">Cotizacion</option>
<!--                                        <option value="">Credito</option>-->
                                    </select>
                                    <InputError :message="form.errors.type"/>
                                </div>
<!--                                Tipo de cuenta si abierta o cerrada-->
                                <div class="ml-3">
                                    <InputLabel
                                        for="type_account"
                                        value="Cuenta"/>
                                    <select
                                        title="Tipo de Cuenta"
                                        v-model="form.close_table"
                                        class="border-gray-200 rounded-md">
                                        <option :value="false">Abierta</option>
                                        <option :value="true">Cerrada</option>
                                    </select>
                                </div>
                            </div>

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
                                        <th>Desc.</th>
                                        <th>Itbis</th>
                                        <th>Precio</th>
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
                                                v-model="item.stock"
                                                type="number">
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
                                                {{ getMoney(item.tax)}}
                                            </span>
                                        </td>
                                        <td
                                            class=" w-[150px]">
                                            {{ getMoney(item.price)}}
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
                                            class="border-gray-300 rounded-md min-h-[100px] max-h-[150px]"
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

