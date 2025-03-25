<script setup lang="ts">
import {router, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import FloatBox from "@components/FloatBox.vue";
import FShow from "@/Pages/Products/FShow.vue";
import {computed, onMounted, onUpdated, Ref, ref} from "vue";
import {productFullI, productI} from "@/Interfaces/Product";
import {getMoney, getRncHelper, getSequenceType, moneyConfig, printPdf} from "@/Global/Helpers";
import Swal from "sweetalert2";
import InputError from "@components/InputError.vue";
import {clientBaseI, rncClientI} from "@/Interfaces/Client";
import FShowClient from "@/Pages/Clients/FShow.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {errorHttp, successHttp} from "@/Global/Alert";
import axios from "axios";
import SaleOpenShow from "@/Pages/Sale/SaleOpenShow.vue";
import {creditNotesSaleI, infoSaleI, saleDataI} from "@/Interfaces/Sale";
import {invoiceTypeI, sequenceDataI} from "@/Interfaces/Setting";
import PaymentInvoice from "@components/PaymentInvoice.vue";
import ReturnForm from "@components/ReturnForm.vue";
import {Money} from "v-money3";
import TabLink from "@components/TabLink.vue";
import {paginationI} from "@/Interfaces/Global";
import {isNullOrUndef} from "chart.js/helpers";



/*
Utilizar el page para los datos de la página
 */
const page = usePage();

/*
 * Datos del back end
 */
const propsW = defineProps<{
    products: productI,
    clients: paginationI<clientBaseI>,
    saleOpen : paginationI<saleDataI>,
    invoiceType: invoiceTypeI[],
    saleInfo?: saleDataI,
    refund?: boolean,
    pdfUuid?: string,
}>();

/*
 * Datos de la ventana
 */
const showClient:Ref<boolean> = ref<boolean>(false);
const showProduct:Ref<boolean> = ref(false);
const showSaleOpen:Ref<boolean> = ref<boolean>(false);
const sequenceData:Ref<sequenceDataI | undefined> = ref(undefined);
const showClientRnc:Ref<boolean> = ref(false);
const showReturn:Ref<boolean> = ref(false);
const showFormReturn:Ref<boolean> = ref(false);


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
    client_rnc_status:"",
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
    type_payment:"CONTADO",
    update: false,
    sequence:"",
    sequence_type: "",
    invoice_type: "B02",
    credit_notes_value: "",
    credit_notes: [] as creditNotesSaleI[],
    credit_notes_amount: 0,
    pending: 0,
});

/*
al momento de cargar
 */
onMounted( () => {
    //Verificar si existe los datos para devoluicion
    setDataForm();
    //Buscar la secuencia si está en la configuration
    if (page.props.setting.sequence)  getSequence(form.invoice_type);

    console.log(form.invoice_type);
    console.log(page.props.setting.sequence && (form.id === 0 || form.id === null))

    //Para verificar
    let msjError = "Este Codigo No es Validos, Introduzca Uno Validado";

    //Valizar si es igual
    if (page.props.errors.general === msjError)
    {
        showFormReturn.value = true;
    }

});

/*
 * al momento de cargar
 */
onUpdated( () => {
    //Buscar la secuencia si está en la configuracion
    setTimeout(()=>{
        if (page.props.setting.sequence) getSequence(form.invoice_type);
    },200);


    //Para verificar
    let msjError = "Este Codigo No es Validos, Introduzca Uno Validado";

    //Valizar si es igual
    if (page.props.errors.general === msjError)
    {
        showFormReturn.value = true;
    }

    // Enviar los datos
    setDataForm();

});



/*
Funciones
 */

/**
 * Poner los datos en el formuilario
 */
const setDataForm = () => {
    //Verificar si existe los datos para devoluicion
    if (propsW.refund && propsW.saleInfo)
    {
        form.id = propsW.saleInfo.id;
        form.ncf_m = propsW.saleInfo.ncf;
        form.client_name = propsW.saleInfo.client_name;
        form.client_id = propsW.saleInfo.client_id;
        form.client_rnc = propsW.saleInfo.client_rnc;
        form.info_sale = propsW.saleInfo.info_sale;
        form.invoice_type = page.props.setting.sequence ? "B04" : "";
        form.type = "devolucion";

        //Recorrer los datos
        form.info_sale.forEach((_,index )=> totalAmount(index));

        //calcular totales
        totalSale();
    }
}

/*
 * Obtener los datos de la sequencia
 */
/**
 * Obtner los comprobantes
 * @param type
 */
const getSequence = async (type: string) => {
    try {
        //Verificar si existe la secuencia
        if (page.props.setting.sequence)
        {
            //Realizar la buqued
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
                    form.clearErrors("ncf");
                    form.ncf = sequenceData.value.type+sequenceData.value.next.toString().padStart(8, '0');

                }
                //Crear la secuencia

            }else{
                //Mensaje de error
                form.setError("sequence", "Este Comprobante No Puedo Ser");
            }
        }
    }catch (err) {

        console.log(err);
        form.ncf = "";
        form.setError("ncf", "No Existe NCF Disponible, Para Esta Serie");
    }

}


/**
 * Return blir
 */
const returnedBlur = ():boolean => {
    //Primero verifica la cantidad
    returned()

    //Verificar el cálculo
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

    //Verificar el cálculo de los datos
    let received:number = form.received ;
    let amount:number = form.amount;
    let creditAmount:number = form.credit_notes_amount;
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

    // Solo buscar los datos si es igual a 0 el ID. eso quiere decir que debe generar un comprobante
    if (form.id == 0)
    {
        //llamar el tipo de boleta
        await getSequence(form.invoice_type);
    }
};


/**
 * Obtener los datos de productos
 * @param item
 */
const getData = (item:productFullI) => {
    //Obtener los datos de productos
    let info:infoSaleI | undefined = form.info_sale.find((el) => el.product_id === item.id);

    // Verificar si el producto exite
    if (info?.product_id  === item.id)
    {
        info.stock += 1.00;
        showProduct.value = false;

    }else{

       //Pasar los datos al formulario
       form.info_sale.push({
           amount: 0,
           discount: item.discount,
           discount_amount: 0,
           price: item.price,
           min_price: item.min_price,
           special_price: item.special_price,
           product_id: item.id,
           product_name: item.name,
           stock: 1,
           reserved: 1,
           tax: item.tax,
           tax_rate: item.tax_rate / 100,
           type: item.type
       });

       //Cerrar la ventana
        showProduct.value = false;
    }

    // //Conseguir el index para poder realizar el cálculo
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
        //Tomar datos la venta
        let info:infoSaleI = form.info_sale[index];


        //Eliminar el producto seleccionado
        form.info_sale.splice(index,1);

        //Verificar si es diferente a devuelta
        if (!propsW.refund)
        {

            if(form.id !== 0)
            {
                //Enviar los datos para actualizar
                form.transform((data) => ({
                    ...data,
                    info: info,
                    info_new: data.info_sale,
                })).patch(route('sale.destroy.item',{product: info.product_id, sale: form.id},{
                    preserveScroll: true,
                    preserveState: true,
                    onFinish: ()=>{
                    },
                    onSuccess: () => {
                        successHttp(`Item : ${info.product_name} Eliminado Correctamente` );
                    }
                }));
            }
        }
        //REalizar el cálculo de nuevo
        totalSale();
    }
}

/**
 * Calcular el itbis y otros datos de la ventana
 * @param index
 */
const totalAmount = (index:number) => {

    // Sacar los datos del produtos
    let info:infoSaleI = form.info_sale[index];
    let discountRate = info.discount / 100;

    //Para calcular los datos
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
    form.discount_amount = form.info_sale.reduce((discount, item:infoSaleI) => discount + item.discount_amount, 0);
    form.amount = form.sub_total - form.discount_amount;

    //calcular el retorno
    returned();

}

/**
 * Seleccionar el cliente
 * @param item
 */
//Seleccionar el cliente
const selectClient = (item:clientBaseI) =>  {
    //Pasar los datos al formulario
    form.client_name = item.name;
    form.client_id = item.id;
    form.invoice_type = item.type_rnc;


    // Si es diferente a b02, colocar el comprobante
    if (item.type_rnc !== "B02")
    {
        form.client_rnc = item.personal_id || "";
        showClientRnc.value = true;
    }


    // Obtener la secuencia del comprobante
    getSequence(item.type_rnc);

    //
    showClient.value = false;
}


/**
 * Enviar los datos para guardar
 */
const sendData = ():void => {
    // Verificar si esta el retorno
    if (propsW.refund)
    {

        // Enviar los datos para las devoluciones
        form.patch(route('credit-note.store', {sale: form.id}))
        // axios.patch(route('credit-note.store',{sale: form.id}),form)
        //     .then(res => {
        //         if (res.data.success)
        //         {
        //             //Imprimir el pdf
        //             printPdf(route('invoice.belt.note',{creditNote: res.data.id}));
        //             //Limpiar el pdf
        //             // router.get(route('sale.create'));
        //             router.visit(route('sale.create'));
        //         }
        //     })
        //     .catch(err => {
        //         console.log(err)
        //         // errorHttp('Error :' + err.response.data.message);
        //     });
    }else{
        //Verificar si no hay problema con nada
        if (!returnedBlur() && form.close_table)
        {
            return;
        }
            //si es para actualizar
        if (form.update)
        {
            // Actualizar los datos y capturar
            axios.patch(route('sale.update', {sale: form.id}), form)
                .then((res) => {

                    if (res.status === 200)
                    {
                        //si esta cerrada se vas a imprimir
                        if (form.close_table)
                        {
                            //Mostrar el pdf de impresion
                            printPdf(route('invoice.belt.sale', {sale: res.data.pdfUuid}));
                        }
                        successHttp('Registro Actualizado Correctamente');
                        //Limpiar el fomulario
                        form.reset();
                        showReturn.value = false;
                        //Recargar los datos
                        router.reload({only:['products','clients','saleOpen','invoiceType','refund']});

                    }
                }).catch((err) => {
                    //Mensaje de error
                    // errorHttp(`Error : ${err.message}`);
            });

        }else{

            //Guardar los datos por primera vez
            axios.post(route('sale.create'), form)
                .then((res) => {
                    // La cuenta es cerrada
                    if (form.close_table)
                    {

                        // Imprimir el pdf
                        printPdf(route('invoice.belt.sale', {sale: res.data.pdfUuid}));
                    }
                    //Limpiar el fomulario
                    successHttp('Registro Creado Correctamente');
                    form.reset();
                    showReturn.value = false;
                    //Recargar los datos
                    router.reload({only:['products','clients','saleOpen','invoiceType','refund']});
                })
                .catch((err) => {
                    // form.errors = err.response?.data.errors;
                    //Mensaje de error
                    // errorHttp(`Error : ${err.message}`);
                });

        }
    }
}


/**
 * Obtener el producto por codigo
 */
const getBycode = () => {

    //Verificar que tenga más de 6 caracter
    if(form.code_value.length > 0)
    {
        //realizar la busqueda en automatico
        axios.get(route('product.get.code', {search: form.code_value}))
            .then((res) =>{
                //Formatear los datos
                const product:productFullI = res.data;
                //Pasar los datos al metodo
                getData(product);
                //Limpiar campo y errores en caso de tenerlo
                form.reset('code_value');
                form.clearErrors('code_value');
            })
            .catch(()=>{
                //Mensjae de que no existe en la base de datos
                form.setError('code_value','Este Producto no existe en la Base de Datos');
            })
    }
}

//Obtener los datos de las cuentas abiertas
const getSaleOpen = (item:saleDataI) => {

    //Colocar la variable en nada al principio
    form.info_sale = [];
    form.id = item.id;
    form.update = true;

    setTimeout(()=>{
        //Verificar Pasar los datos a la variable
        item.info_sale.map((el, index) => {

            //colocar la informacion en la lista
            form.info_sale.push({
                transID: el.transID,
                product_id: el.product_id,
                product_name: el.product_name,
                price: el.price,
                min_price: el.min_price,
                special_price: el.special_price,
                stock:  el.stock,
                reserved: el.reserved,
                amount: el.amount,
                discount: el.discount,
                discount_amount: el.discount_amount,
                tax: el.tax,
                type: el.type,
                trans_type: el.trans_type,
                tax_rate: el.tax_rate,
                status: el.status
            });
            //Calcular el total
            totalAmount(index);
        })
    },2);

    //calcular el total de las ventas
    totalSale();

    //colocar los datos en el formulario
    form.client_id = item.client_id;
    form.client_rnc = item.client_document ?? "";
    form.ncf = item.ncf;
    form.invoice_type = item.invoice_type;
    form.client_name = item.client_name;
    form.close_table = item.close_table;
    form.comment = item.comment ?? "";

    //Cerra la ventana
    showSaleOpen.value = false;

    // Ejecutar el metodo de invoice
    checkInvoiceType();

}

/**
 * verificar la venta
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

    //Llamar el metodo para el cálculo
    returned();

}


/*
 * Calcular la nota de credito
 */
const amountCreditNote = () => {
    //REalizar el cálculo de notas de credito
    form.credit_notes_amount = form.credit_notes.reduce((acc, cur) => acc + cur.n_available, 0);
    //Datos pendientes por pagar
    form.returned = form.credit_notes_amount - form.amount;
    form.pending = (form.credit_notes_amount - form.amount) < 0 ?(form.credit_notes_amount - form.amount) : 0;

}

/*
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

        //Verificar el estado del RNC
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
            const info:rncClientI = result;

            //Poner cada dato en su lugar
            form.client_name = info.razon_social;
            form.client_rnc_status = info.status;

            // Limpiar el formulario
            form.clearErrors();
        }
    }
}

</script>

<template>
<!--    Contenido general-->
    <AppLayout>
<!--        Cabecera de la ventana-->
    <template #header >
        <TabLink
            :active="true"
            :href="route('sale.create')">
            Registrar
        </TabLink>
        <TabLink
            :href="route('sale.show')">
            Mostrar
        </TabLink>
        <TabLink
            :href="route('credit-note.show')">
            N. Credito
        </TabLink>
        <TabLink
            :href="route('sale.close')">
            Cierre
        </TabLink>
        <TabLink
            :href="route('sale.counter')">
            Conteo
        </TabLink>
    </template>

<!--        //contenido-->
    <div>
        <div
            class="fondo p-5 rounded-md mx-auto overflow-hidden">
            <form
                class=" max-w-3/5">
                <div >
                    <div class="grid grid-cols-3 gap-2">
                        <div class="">
<!--                                Botones para buscar datos-->
                            <div class="space-x-5 items-center w-full">
                                <div class="relative">
                                    <input-label
                                        for="product"
                                        value="Cliente" />

                                    <div class="relative">
                                        <TextInput
                                            type="search"
                                            :readonly="form.invoice_type === 'B04' "
                                            class=" w-full pr-10"
                                            v-model.trim="form.client_name"
                                            placeholder="Cliente"/>
<!--                                            Colocar al lado esto-->
                                        <div
                                            class="absolute inset-y-0 right-0 flex items-center">
                                            <i
                                                v-if="form.invoice_type !== 'B04'"
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
                            <div v-if="showClientRnc && page.props.setting.sequence && !form.errors.ncf" >
                                <InputLabel
                                    for="client_rnc"
                                    value="RNC" />
                                <div class="relative">
                                    <TextInput
                                        v-model="form.client_rnc"
                                        class="w-full pr-[32px]"
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
                            v-if="form.sequence_type == '' && page.props.setting.sequence || form.errors.ncf"
                            class="grid grid-cols-1 w-full justify-items-center ">
                            <div
                                class="animate-pulse text-gray-50 "
                                v-if="form.sequence_type == '' && page.props.setting.sequence">
                                Cargando....
                            </div>
                            <!--                        Error de los NCF si no existe-->
                            <div
                                class="justify-items-center"
                                v-if="form.errors.ncf">
                                <InputError :message="form.errors.ncf"/>
                            </div>
                        </div>


                        <fieldset
                            class="field block rounded-md"
                            v-if="form.sequence_type !== '' && page.props.setting.sequence && !form.errors.ncf">
                            <legend>
                                {{form.sequence_type}}
                            </legend>
                            <p class=""><strong>NCF :</strong> {{form.ncf}}</p>
                            <p
                                v-if="form.invoice_type === 'B04'"
                                class="truncate"><strong>NCF M. :</strong> {{form.ncf_m}}</p>
                        </fieldset>

                        <!--Numero de comprobantes-->
                        <fieldset
                            class="field block rounded-md"
                            v-if="showClientRnc && !form.errors.ncf">
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

<!--                        Datos del formulario-->
                    <div class=" flex justify-between items-center mt-3">
                        <div class="flex">
                            <form
                                v-if="form.invoice_type !== 'B04' "
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

                                <InputError :message="form.errors.code_value"/>
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
                                    class="icon-efect text-cyan-400 text-3xl fa-solid fa-box-open"></i>

<!--                                    Btn de Cuentas abierta-->
                                <i
                                    title="Cuentas Abiertas"
                                    @click="showSaleOpen = !showSaleOpen"
                                    class=" ml-3 icon-efect text-cyan-400 text-3xl  fa-solid fa-table-cells-row-unlock"></i>

<!--                                 BTN Devolucion-->
                                <i
                                    title="Devoluciones"
                                    @click="showFormReturn = !showFormReturn"
                                    class=" ml-3 icon-efect text-cyan-400 text-3xl fa-solid fa-arrow-rotate-left"></i>

                            </div>
                        </div>


                        <div class="flex">
                            <!--Tipo de factura-->
                            <div
                                v-if="page.props.setting.sequence"
                                class="ml-3">
                                <InputLabel for="type" value="Tipo de Factura"/>
                                <select
                                    :disabled="form.invoice_type == 'B04'"
                                    @change="checkInvoiceType"
                                    v-model="form.invoice_type"
                                    class="inputGeneral py-0"
                                    name="type"
                                    id="type">
                                    <option
                                        v-for="(item, index) in propsW.invoiceType"
                                        :key="index"
                                        :disabled="false"
                                        :value="item.type">
                                        {{item.type}} - {{ item.name }}
                                    </option>
                                    <!--                                        <option value="">Credito</option>-->
                                </select>
                                <InputError :message="form.errors.invoice_type"/>
                            </div>


                            <!--Tipo de factura-->
                            <div class="ml-2">
                                <InputLabel for="type" value="Tipo de Venta"/>
                                <select
                                    class="inputGeneral py-0"
                                    v-model="form.type">
                                    <option
                                        :disabled="propsW.refund"
                                        value="ventas" >CONTADO</option>
                                    <option
                                        :disabled="propsW.refund"
                                        value="cotizacion" >CREDITO</option>
                                    <option
                                        :disabled="!propsW.refund"
                                        value="devolucion" >Devolucion</option>
                                </select>
                                <InputError :message="form.errors.type"/>
                            </div>
                            <!--Tipo de cuenta si abierta o cerrada-->
                            <div
                                v-if="!propsW.refund"
                                class="ml-2">
                                <InputLabel
                                    for="type_account"
                                    value="Cuenta"/>
                                <select
                                    v-model="form.close_table"
                                    class="inputGeneral py-0">
                                    <option :value="false">ABIERTA</option>
                                    <option :value="true">CERRADA</option>
                                </select>
                            </div>
                        </div>

                    </div>

<!--                        Listado de los productos-->
                    <div
                        class="max-h-[400px] border-t-2 mt-3 border-black overflow-y-auto shadow-lg p-3 rounded-md">
                        <table class="styleTable w-full">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Producto/Servicio</th>
                                    <th>Cantidad</th>
                                    <th>Itbis</th>
                                    <th>Precio</th>
                                    <th>Desc.</th>
                                    <th>Importe</th>
                                    <th>Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in form.info_sale" :key="index">
                                    <td>
                                        {{index+1}}
                                    </td>
                                    <td>
                                        {{item.product_name}}
                                    </td>
                                    <td class="max-w-[5rem]">
                                        <Money
                                            class=" bg-transparent h-[2rem] max-w-[6rem] rounded-md border-none"
                                            @blur="totalAmount(index)"
                                            v-bind="moneyConfig"
                                            v-model.number="item.stock"/>
                                    </td>
                                    <td>
                                        {{getMoney(item.tax)}}
                                    </td>

<!--                                        Precio solo modificar si es servicio-->
                                    <td class="max-w-[5rem]">
                                        <span v-if="item.type === 'producto'">
                                            {{getMoney(item.price)}}
                                        </span>
                                        <Money
                                            class=" bg-transparent h-[2rem] max-w-[6rem] rounded-md border-none"
                                            v-if="item.type === 'servicio'"
                                            @blur="totalAmount(index)"
                                            v-bind="moneyConfig"
                                            v-model.number="item.price"/>
                                    </td>
                                    <td class="max-w-[4rem]">
                                        <Money
                                            class=" bg-transparent h-[2rem] max-w-[5rem] rounded-md border-none"
                                            @blur="totalAmount(index)"
                                            v-bind="moneyConfig"
                                            :min="0"
                                            :max="100"
                                            v-model.number="item.discount"/>
                                    </td>
                                    <td>
                                        {{getMoney(item.amount)}}
                                    </td>

                                    <td>
                                        <i
                                            @click="deleteItem(item.product_name, index)"
                                            class=" icon-efect text-red-500 fa-solid fa-circle-xmark"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
<!--                        Dato de la ventas-->
                    <div>
                        <!--                            Mensaje generales-->
                        <div>
                            <InputError :message="form.errors.general"/>
                        </div>

                        <div>
                            <InputError :message="form.errors.info_sale"/>
                        </div>


                        <!--                            Comentario de la venta-->
                        <div class="grid grid-cols-4 items-center gap-4">
                            <textarea
                                placeholder="Comentario"
                                v-model.trim="form.comment"
                                cols="60"
                                class="area col-span-2">
                            </textarea>
                            <div class=" col-end-7 col-span-2 text-white">
                                <table>
                                    <tbody>
                                        <tr>
                                            <th class="text-left">Itbis :</th>
                                            <td>{{getMoney(form.tax)}}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-left">Sub Total :</th>
                                            <td>{{getMoney(form.sub_total)}}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-left">Decuento :</th>
                                            <td>{{getMoney(form.discount_amount)}}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-left">Total :</th>
                                            <td class="w-[15rem]" >{{getMoney(form.amount)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

<!--                        Mensaje de erro-->
                    <div>
                        <InputError :message="form.errors.general"/>
                    </div>
<!--                        Devuelta y demas detos-->
                    <div class=" mt-2 w-64 float-right">


                        <div class="">
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

        <!-- Ventana de Devuelta-->
        <FloatBox
            header="Retornos"
            @close="showReturn = false"
            v-if="showReturn">
            <PaymentInvoice
                @amount-credit-note="amountCreditNote()"
                @returned-blur="returnedBlur()"
                @returned="returned()"
                @sen-data="sendData()"
                :form="form"
                v-model:type-payment="form.type_payment"
                v-model:credit-note="form.credit_notes_value"
                v-model:credit-notes="form.credit_notes"
                v-model:returned="form.returned"
               />
        </FloatBox>

        <!-- Mostrar flotante los clientes --->
        <FloatBox
            header="Clientes"
            @close="showClient = false"
            v-if="showClient">
            <FShowClient
                class=" max-w-4/5 rounded-md py-5"
                @get-data="selectClient"
                :clients="propsW.clients"/>

        </FloatBox>

        <!-- Ventana de productos-->
        <FloatBox

            header="Productos"
            @close="showProduct = false"
            v-if="showProduct">
            <FShow
                :stock="true"
                class=" fondo  rounded-md px-10 py-5 w-[65rem]"
                @select="getData"
                :products="propsW.products"/>
        </FloatBox>


        <!-- Vetana de las ordenes abierta -->
        <FloatBox
            header="Cuentas Abiertas"
            @close="showSaleOpen = false"
            v-if="showSaleOpen">
            <SaleOpenShow
                @sen-data="getSaleOpen"
                class=" fondo w-[65rem] rounded-md px-10 py-5"
                :sale-open="propsW.saleOpen"/>
        </FloatBox>


        <!-- Formulario para la nota de credito-->
        <FloatBox
            header="Devolución"
            @close="showFormReturn = false"
            v-if="showFormReturn">
            <ReturnForm
                class="w-[40rem]"
                @closeFormReturn="showFormReturn = false"
                :error="page.props.errors.general"/>
        </FloatBox>
    </div>
    </AppLayout>
</template>

