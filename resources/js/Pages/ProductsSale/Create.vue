<script setup lang="ts">
import {Head, router, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import FloatBox from "@components/FloatBox.vue";
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";
import {onMounted, onUpdated, reactive, Ref, ref} from "vue";
import {productDataI, productI, productSaleI} from "@/Interfaces/Product";
import {getMoney, getRncHelper, getSequenceType} from "@/Global/Helpers";
import LinkHeader from "@components/LinkHeader.vue";
import Swal from "sweetalert2";
import InputError from "@components/InputError.vue";
import {clientDataI, clientI} from "@/Interfaces/ClientInterface";
import FloatShowCli from "@/Pages/Clients/FloatShowCli.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {successHttp} from "@/Global/Alert";
import axios from "axios";
import SaleOpenShow from "@/Pages/ProductsSale/SaleOpenShow.vue";
import {creditNotesSaleI, infoSaleI, saleDataI, saleDataPaginationI} from "@/Interfaces/Sale";
import {invoiceTypeI, rncUserI, sequenceDataI} from "@/Interfaces/Setting";


/*
Utilizar el page para los datos de la pagina
 */
const page = usePage();

/*
 * Datos del back end
 */
const propsW = defineProps<{
    products: productI,
    clients: clientI,
    pdf? : string,
    saleOpen : saleDataPaginationI,
    invoiceType: invoiceTypeI[],
    saleInfo?: saleDataI,
    refund?: boolean
}>();

/*
al momento de cargar
 */
onMounted( () => {
    //Verificar si existe los datos para devoluicion
    if (propsW.refund && propsW.saleInfo)
    {
        form.id = propsW.saleInfo.id;
        form.ncf_m = propsW.saleInfo.ncf;
        form.client_name = propsW.saleInfo.client_name;
        form.info_sale = propsW.saleInfo.info_sale;
        form.invoice_type = page.props.setting.sequence ? "B04" : "";
        form.type = "devolucion";

        //Recorrer los datos
        form.info_sale.forEach((item, index) => {
            totalAmount(index);
        });

        //calcular totales
        totalSale();
    }
    //Buscar la secuencia si esta en la configuracion
    if (page.props.setting.sequence)  getSequence(form.invoice_type);
});


/*
 * al momento de cargar
 */
onUpdated( () => {
    //Buscar la secuencia si esta en la configuracion
    if (page.props.setting.sequence) getSequence(form.invoice_type);
});

/*
 * Datos de la ventana
 */
const showClient:Ref<boolean> = ref<boolean>(false);
const showProduct:Ref<boolean> = ref(false);
const showSaleOpen:Ref<boolean> = ref<boolean>(false);
const sequenceData:Ref<sequenceDataI | null> = ref(null);
const showClientRnc:Ref<boolean> = ref(false);
const showReturn:Ref<boolean> = ref(false);
const typePaymentData = reactive([
    {
        name: "Contado",
        value: 'contado'
    },
    {
        name: "Credito",
        value: 'credito'
    },
    {
        name: "Cheque",
        value: 'cheque'
    },
    {
        name: "Tarjeta",
        value: 'tarjeta'
    },
    {
        name: "Transferencia",
        value: 'transferencia'
    },
    {
        name: "Anticipo",
        value: 'anticipo'
    },
])


/*
 * Formulario
 */
const form = useForm({
    id: 0,
    code_value: "",
    ncf:"",
    ncf_m:"",
    client_name: "",
    client_id: 0,
    client_rnc:"",
    client_social:"",
    info_sale: [] as infoSaleI[],
    tax: 0,
    discount_amount: 0,
    amount: 0,
    sub_total: 0,
    comment: "",
    comment_id: 0,
    close_table: false,
    received: 0,
    returned: 0,
    general: "",
    type: "ventas",
    type_payment:"contado",
    update: false,
    sequence_type: "",
    invoice_type: "B02",
    credit_notes_value: "",
    credit_notes: [] as creditNotesSaleI[],
    credit_notes_amount: 0,
    pending: 0
});



/*
Funciones
 */

/*
 * Obtener los datos de la sequencia
 */
/**
 * Obtner los comprobantes
 * @param type
 */
const getSequence = async (type: string) => {

    if (!page.props.setting.sequence)

    console.log('funciona');
    {
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
                form.ncf = sequenceData.value.type+sequenceData.value.next.toString().padStart(8, '0');
            }
            //Crear la secuencia

        }else{
            //Mensaje de error
            form.setError("sequence", "Este Comprobante No Puedo Ser");
        }
    }
}


/**
 * Return blir
 */
const returnedBlur = ():boolean => {
    //Verificar el calculo
    if(form.returned < 0)
    {
        //Enviar el mensaje de error
        form.setError('returned','El monto recibido no puede ser menor al Total');
        setTimeout(()=>{
            form.clearErrors('returned');
        },3500);
        return false;

    }else return  true
}

/**
 * Devuelta de cambio
 */
const returned = ():void => {

    let received:number = parseFloat(form.received ?? 0);
    let amount:number = parseFloat(form.amount ?? 0);
    let creditAmount:number = parseFloat(form.credit_notes_amount ?? 0);
    //Restar la cantidad
    form.returned = creditAmount + received - amount;

    //Datos pendiente para nota de credito o balance
    form.pending = (creditAmount + received - amount) < 0 ? (creditAmount + received - amount) : 0 ;

}


/**
 * Verificar el tipo de factura
 */
const checkInvoiceType = async ()=>{

    // Verificar si es nota de credito
    if (form.invoice_type === 'B04')
    {
        //REsultaod de la pregunta
        const result = await Swal.fire({
            title: "Desea Colocar Comprobante?",
            text: "Registre El Comprobante Del Cliente!",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si",
            cancelButtonText: "No"
        });

        //Verificar la accion
        showClientRnc.value = result.isConfirmed;

    }else showClientRnc.value = form.invoice_type !== 'B02';

    //llamar el tipo de boleta
    await getSequence(form.invoice_type);
};


/**
 * Obtener los datos de productos
 * @param item
 */
const getData = (item:productSaleI) => {

    //Obtener los datos de productos
    let info = form.info_sale.find((el) => el.product_id === item.id);

    // Verificar si el producto exite
    if (info?.product_id  === item.id)
    {
        info.stock += 1;
        showProduct.value = false;

    }else{

       //Pasar los datos al formulario
       form.info_sale.push({
            product_id: item.id,
            code: item.code,
            product_name: item.name,
            stock: 1,
            cost: item.cost,
            price: item.price,
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

    // //Conseguir el index para poder realizar el calculo
    let index = form.info_sale.findIndex((el) => el.product_id === item.id);

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
        //Tomar datos la ventas
        let info:infoSaleI = form.info_sale[index];

        //Eliminar el producto seleccionado
        form.info_sale.splice(index,1);

        if (!propsW.refund)
        {

            if(form.id !== 0)
            {
                //Enviar los datos para actualizar
                form.transform((data) => ({
                    ...data,
                    info: info,
                    info_new: data.info,
                })).patch(route('sale.destroy.item',{product: info.product_id, sale: form.id},{
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        successHttp(`Item : ${info.name} Eliminado Correctamente` );
                    }
                }));
            }
        }
        //REalizar el calculo de nuevo
        totalSale();
    }

}

/**
 * Calcular el  itbis y otros datos de la ventana
 * @param index
 */
const totalAmount = (index:number) => {

    // Sacar los datos del produtos
    let info:infoSaleI = form.info_sale[index];
    let discountRate = info.discount / 100;


    info.amount = parseFloat ((info.price * info.stock).toFixed(2));
    //Descuento datos
    info.discount_amount = parseFloat((info.amount * discountRate).toFixed(2));
    //Pasar los datos al formulario
    info.tax = parseFloat ((info.amount  * info.tax_rate).toFixed(2));

    //Calcular los totales
    totalSale();

}

/**
 * Calculo el total de venta
 */
// Calculo de los datos finales
const totalSale = () => {


    //Calcular el total
    form.tax = form.info_sale.reduce((tax:number, item:infoSaleI) => tax + item.tax, 0);
    form.sub_total = form.info_sale.reduce((subTotal:number, item:infoSaleI) => subTotal + item.amount, 0);
    form.discount_amount = form.info_sale.reduce((discount, item:infoSaleI) => discount + item.discount, 0);
    form.amount = form.sub_total - form.discount_amount;

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
 * Enviar los datos para guardar
 */
const sendData = ():void => {

    if (propsW.refund)
    {
        // Enviar los datos para las devoluciones
        form.patch(route('credit-note.store',{sale: form.id}),{
            only: ['products','clients','saleOpen','invoiceType'],
            onSuccess: () => {
                form.reset();
                successHttp('Nota de Credito Creada Correctamente');
                router.get(route('sale.create'));
            },
            onError:()=>{
                setTimeout(()=>{
                    form.clearErrors('general');
                },3500);
            }
        });

    }else{

        //Verificar si no hay problema con nada
        if (!returnedBlur() && form.close_table)
        {
            return false;
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
                        showReturn.value = false;
                    },
                    onError:()=>{
                        setTimeout(()=>{
                            form.clearErrors();
                        },5000)
                    },
                });
            }else{

                //Guardar los datos por primera vez
                form.post(route('sale.store'),{
                    onSuccess:()=>{
                        successHttp('Venta Cerrada Correctamente');
                        form.reset();
                        showReturn.value = false;
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
}


/**
 * Obtener el producto por codigo
 */
const getBycode = () => {

    //Verificar que tenga mas de 6 caracter
    if(form.code_value.length > 6)
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
    form.info_sale = [];
    form.id = item.id;
    form.update = true;
    //Verificar Pasar los datos a la variable
    item.info_sale.map((el, index) => {
        //colocar la informacion en la lista
        form.info_sale.push({
            id: el.product_id,
            code: el.code,
            product_id: el.product_id,
            product_name: el.product_name,
            price: el.price,
            stock: el.stock,
            amount: el.amount,
            tax: el.tax,
            type: el.type,
            discount: el.discount,
            discount_amount: el.discount_amount,
            tax_rate: el.tax_rate,
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
 * Verificar la venta
 */
const checkSale = () => {
    //Verificar si se puede mostrar los datos
    if (form.close_table && form.info_sale.length > 0) {
        //REalizar calculo si existe
        amountCreditNote();
        //Mostar la ventana
        showReturn.value = form.close_table;
    }
    else{
        sendData();
    }

}

/**
 * Buscar la notas de credito para pagar la factura
 */
const getCreditNote = async () => {

    //Si no hay suficiente caracateres
    if (form.credit_notes_value.length < 5) {
        form.setError('credit_notes_value', 'Por Favor, Introduzca Valores Valido');
        return false;
    }

    //Verificar si ya esta en positivo no puede colocar nota de credito
    if (form.returned > 0)
    {
        form.setError('credit_notes_value','Existe Suficiente Balance Para Cerrar La Cuenta');
        return false;
    }

    //Verificar si exsite alguna igual
    const exist:boolean = form.credit_notes.some((el) => el.code == form.credit_notes_value || el.ncf == form.credit_notes_value);

    if (exist)
    {
        form.setError('credit_notes_value','Esta Nota De Credito, Esta Agregada');

    }else{
        //Buscar la nota de credito
        const {data} = await axios.get(route('credit-note.get',{code: form.credit_notes_value}));

        //Verifciar los datos
        if (data.hasOwnProperty('code'))
        {
            //Pasar los datos al formulario
            form.credit_notes.push(data);
            //Calcular los datos
            amountCreditNote();
            //Limpiar los errores
            form.clearErrors('credit_notes_value');
            //Limpiar el campo para agreagr otros
            form.reset('credit_notes_value');

        }else{
            //Poner el mensaje de error
            form.setError('credit_notes_value',data.error);
        }
    }

}

/**
 * Eliminar la nota de credito
 */
const deleteCreditNote = (index:number) => {
    //Eliminar solo el dato seleccionado
    form.credit_notes.splice(index, 1);
    //Realizar el calculo
    amountCreditNote();
}

/**
 * Calcular la nota de credito
 */
const amountCreditNote = () => {
    //REalizar el calculo de notas de credito
    form.credit_notes_amount = form.credit_notes.reduce((acc, cur) => acc + cur.n_available, 0);
    //Datos pendiente por pagar
    form.returned = form.credit_notes_amount - form.amount;
    form.pending = (form.credit_notes_amount - form.amount) < 0 ?(form.credit_notes_amount - form.amount) : 0;

}

/**
 * Conseguirel RNC del cliente
 */
const getRncClient = async () => {
    //Verificar tis tiene menos de la longitud deseada
    if (form.client_rnc.length < 7)
    {
        //Poner el mensaje cuando sea menos de la longitud real
        form.setError("client_rnc",'Por favor, La Longitud De La Cadena Es Insuficiente');
    }else{
        //Obtener el resultado de los
        const result = await getRncHelper(form.client_rnc);

        //Verificar los estado del RNC
        if (result === "SUSPENDIDO")
        {
            form.setError("client_rnc", "Este Contribuyente Esta Suspendido, Por Favor Elegir Otro");

        }else if (result === "ERROR")
        {
            form.setError("client_rnc", "Este Contribuyente No Pudo Ser Encontrado");

        }else if (result === "CANCELLED")
        {
            form.setError("client_rnc", "Este Contribuyente Esta Cancelado");
        }else{
            //Formatear el json
            const info:rncUserI = JSON.parse(result);

            //Poner cada datos en su lugar
            form.client_name = info.razon_social;

            console.log(info);
        }
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
                    class=" max-w-3/5">
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
                                                type="search"
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


                                <!--RNC del cliente-->
                                <div v-if="showClientRnc && page.props.setting.sequence" >
                                    <InputLabel
                                        for="client_rnc"
                                        value="RNC" />
                                    <div class="relative">
                                        <TextInput
                                            v-model="form.client_rnc"
                                            class="w-[400px] pr-[32px]"
                                            type="search" />
                                        <i
                                            @click="getRncClient"
                                            class=" absolute right-0 inset-y-0 flex items-center icon-efect p-2 text-2xl fa-solid fa-magnifying-glass"></i>
                                    </div>

                                    <InputError :message="form.errors.client_rnc"/>
                                </div>
                            </div>

<!--                            Mensaje cargando-->
                            <div
                                class=" flex-1 flex justify-end animate-pulse "
                                v-if="form.sequence_type == '' && page.props.setting.sequence">
                                Cargando....
                            </div>

<!--                            Datos de comprobante-->
                            <div
                                class="flex flex-col-reverse"
                                v-if="form.sequence_type !== ''">
<!--                                Mensaje de cargando-->
                                <!--Numero de comprobantes-->
                                <fieldset class="border-2 border-gray-400 rounded-md px-2 mx-3 w-[350px] ">
                                    <legend>
                                        {{form.sequence_type}}
                                    </legend>
                                    <p class="truncate"><strong>NCF :</strong> {{form.ncf}}</p>
                                    <p
                                        v-if="form.invoice_type === 'B04'"
                                        class="truncate"><strong>NCF M. :</strong> {{form.ncf_m}}</p>
                                </fieldset>
                                <!--Numero de comprobantes-->
                                <fieldset
                                    v-if="showClientRnc"
                                    class=" border-2 border-gray-400 rounded-md px-2 mx-3 w-[350px] max-w-[400px]">
                                    <legend>
                                        Datos Tributario
                                    </legend>
                                    <p><strong>RNC :</strong> {{form.client_rnc}}</p>
                                    <p class="max-w-[300px] truncate">
                                        <strong>Razon Social :</strong>
                                        {{form.client_name}}
                                    </p>
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
                                        v-model="form.code_value"
                                    />

                                <InputError :message="form.errors.code_product"/>
                                </form>
                                <!-- Buscar los datos necesario -->
                                <div
                                    v-if="!propsW.refund"
                                    class="ml-3">
                                    <InputLabel value="Datos"/>

<!--                                    Btn de producto-->
                                    <i
                                        title="Productos"
                                        @click="showProduct = !showProduct"
                                        class="icon-efect text-3xl fa-solid fa-box-open"></i>

<!--                                    Btn de Cuentas abierta-->
                                    <i
                                        title="Cuentas Abiertas"
                                        @click="showSaleOpen = !showSaleOpen"
                                        class=" ml-3 icon-efect text-3xl  fa-solid fa-table-cells-row-unlock"></i>

                                </div>
                            </div>


                            <div class="flex">
                                <!--Tipo de factura-->
                                <div
                                    v-if="page.props.setting.sequence"
                                    class="ml-3">
                                    <InputLabel for="type" value="Tipo de Factura"/>
                                    <select
                                        @change="checkInvoiceType"
                                        v-model="form.invoice_type"
                                        class="border-gray-200 rounded-md"
                                        name="type"
                                        id="type">
                                        <option
                                            v-for="(item, index) in propsW.invoiceType" :key="index"
                                            :disabled="item.type == 'B04' && page.url == '/sale' "
                                            :value="item.type">
                                            {{item.type}} - {{ item.name }}
                                        </option>
                                        <!--                                        <option value="">Credito</option>-->
                                    </select>
                                    <InputError :message="form.errors.invoice_type"/>
                                </div>


                                <!--Tipo de factura-->
                                <div class="ml-3">
                                    <InputLabel for="type" value="Tipo de Venta"/>
                                    <select
                                        title="Tipo de Venta"
                                        v-model="form.type"
                                        class="border-gray-200 rounded-md"
                                        name="type"
                                        id="type">
                                        <option
                                            :disabled="propsW.refund"
                                            value="ventas">Ventas</option>
                                        <option
                                            :disabled="propsW.refund"
                                            value="contizacion">Cotizacion</option>
                                        <option
                                            :disabled="!propsW.refund"
                                            value="devolucion">Devolución</option>
                                    <!--<option value="">Credito</option>-->
                                    </select>
                                    <InputError :message="form.errors.type"/>
                                </div>
                                <!--Tipo de cuenta si abierta o cerrada-->
                                <div
                                    v-if="!propsW.refund"
                                    class="ml-3">
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

<!--                        Listado de los productos-->
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
                                        <th
                                            v-if="form.info_sale.length > 1">
                                            Atc
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class=" odd:bg-gray-300 border-2 border-b-gray-500"
                                        v-for="(item, index) in form.info_sale" :key="index">
                                        <td>{{index+1}}</td>
                                        <td>
                                            <div>
                                                <p>{{item.code}}</p>
                                                <p>{{item.product_name}}</p>
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
                                            v-if="form.info_sale.length > 1"
                                            class="text-xl w-[50px]">
                                            <i
                                                @click="deleteItem(item.name, index)"
                                                class=" icon-efect text-red-500 fa-solid fa-circle-xmark"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

<!--                            Mensaje generales-->
                            <div>
                                <InputError :message="form.errors.general"/>
                            </div>


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
                                            {{ form.comment ? 255 - form.comment.length : 255 }}
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

<!--                        Devuelta y demas detos-->
                        <div class=" mt-5 w-64 float-right">
<!--                            <div v-if="form.close_table && !propsW.refund ">-->

<!--                                <div>-->
<!--                                    <strong>-->
<!--                                        Devuelta :-->
<!--                                    </strong>-->
<!--                                    <span>-->
<!--                                        {{getMoney(form.returned)}}-->
<!--                                    </span>-->
<!--                                </div>-->

<!--                                <InputLabel-->
<!--                                    class="text-left"-->
<!--                                    for="received"-->
<!--                                    value="Monto Recibido"/>-->
<!--                                <TextInput-->
<!--                                    class="w-full"-->
<!--                                    type="number"-->
<!--                                    required-->
<!--                                    @blur="returned"-->
<!--                                    v-model="form.received"/>-->
<!--                                <InputError :message="form.errors.received"/>-->
<!--                            </div>-->

                            <div class="mt-5">
<!--                                <SecondaryButton-->
<!--                                    type="button">-->
<!--                                    Limpiar-->
<!--                                </SecondaryButton>-->
                                <PrimaryButton
                                    :disabled="form.processing"
                                    @click="checkSale"
                                    type="button">
                                    {{form.close_table ? 'Cerrar Venta' : 'Registrar'}}
                                </PrimaryButton>
                            </div>

                        </div>

                    </div>
                </form>

            </div>

            <Transition>
                <FloatBox
                    v-if="showReturn"
                    @close="showReturn = false">
                    <!--Datos de la ventana-->
                    <div class="bg-gray-200 p-5 rounded-md min-w-[600px] h-fit">
                        <h3 class="text-2xl text-center">
                            Datos de pagos
                        </h3>

                        <!--Tipo de apgo-->
                        <div class="mt-3">
                            <InputLabel
                                for="typePayment"
                                value="Tipo Pago" />
                            <select
                                autofocus
                                v-model="form.type_payment"
                                id="typePayment"
                                class="rounded-md border-gray-300 w-full">
                                <option
                                    v-for="(item, index) in typePaymentData" :key="index"
                                    :value="item.value">
                                    {{item.name}}
                                </option>
                            </select>
                        </div>
<!--                        Aplicar nota de credito-->
                        <div class="max-w-[590px] mt-3">
                            <InputLabel
                                for="credit_notes"
                                value="Notas Creditos"/>
                            <div class="relative">
                                <TextInput
                                    class="w-[calc(100%-3rem)]"
                                    v-model="form.credit_notes_value"
                                    type="search"/>
                                <i
                                    @click="getCreditNote"
                                    class=" bg-gray-400 hover:text-white duration-300 ease-linear rounded-md text-2xl p-2 absolute right-0 flex items-center inset-y-0 fa-solid fa-magnifying-glass"></i>
                            </div>
<!--                            Mensaje de error-->
                            <InputError :message="form.errors.credit_notes_value"/>
<!--                            Mostrar las notas de creditos asociada a esa venta-->
                            <table class="table-fixed w-full mt-3">
                                <caption>
                                    Notas De Credito
                                </caption>
                                <thead class="text-left">
                                    <tr class="border-2 border-b-gray-800">
                                        <th>Cod./NCF</th>
                                        <th>Disponible</th>
                                        <th class="w-1/12" >Act</th>
                                    </tr>
                                </thead>
<!--                                Cuerpod de los datos-->
                                <tbody>
                                    <tr v-for="(item, index) in form.credit_notes" :key="index">
                                        <td>{{item.code}}</td>
                                        <td>{{ getMoney(item.n_available)}}</td>
                                        <td class="text-center w-1/12">
                                            <i
                                            @click="deleteCreditNote(index)"
                                            class=" icon-efect fa-solid fa-trash"></i></td>
                                    </tr>
                                    <tr class=" border-t-2 border-gray-800">
                                        <th>Total :</th>
                                        <th>{{getMoney(form.credit_notes_amount)}}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


<!--                      Monto Recibido-->
                        <div class="w-full mt-3">
                            <InputLabel
                                for="received"
                                value="Recibido" />

                            <TextInput
                                @blur="returnedBlur"
                                @input="returned"
                                class="w-full"
                                type="number"
                                v-model="form.received"/>
                        </div>

<!--                        Datos pendiente para cobrar-->
                        <div class="mt-3 text-3xl">
                            Pendiente...: {{getMoney(form.pending)}}
                        </div>
<!--                        Datos Para devuelta-->
                        <div class="mt-3 text-3xl">
                            Devuelta......: {{getMoney(form.returned)}}
                        </div>

<!--                        Boton para cerrar la factura-->
                        <div class="mt-3 text-right">
                            <PrimaryButton
                                :disabled="form.processing"
                                @click="sendData()">
                                Cerrar Factura
                            </PrimaryButton>
                        </div>

<!--                        Mensaje de error-->
                        <div class="mt-3">
                            <InputError :message="form.errors.returned"/>
                        </div>


                    </div>
                </FloatBox>
            </Transition>


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

