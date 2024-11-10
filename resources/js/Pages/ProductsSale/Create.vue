<script setup lang="ts">
import {Head, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import FloatBox from "@components/FloatBox.vue";
import FloatShowPro from "@/Pages/Products/FloatShowPro.vue";
import {computed, onMounted, onUpdated, Ref, ref} from "vue";
import {productDataI, productI} from "@/Interfaces/Product";
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
import ShowPdf from "@components/ShowPdf.vue";
import PaymentInvoice from "@components/PaymentInvoice.vue";
import ReturnForm from "@components/ReturnForm.vue";
import InputNumber from 'primevue/inputnumber';
import Textarea from 'primevue/textarea';


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
    pdf: string | null,
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
    setDataForm();
    //Buscar la secuencia si esta en la configuracion
    if (page.props.setting.sequence)  getSequence(form.invoice_type);

    //Pasar los datos a la variable si existe
    if (propsW.pdf != undefined && propsW.pdf != "") pdfString.value = propsW.pdf;


    //Para verificar
    let msjError = "Este Codigo No es Validos, Introduzca Uno Validado";

    //Valizar si es igual
    if (page.props.errors.general === msjError)
    {
        showFormReturn.value = true;
    }
    //Para enviar a imprimir de una vez
    // const iFrame = document.getElementById("pdfA") as HTMLIFrameElement;
    //
    // if (iFrame && iFrame.contentWindow)
    // {
    //     iFrame.contentWindow.focus(); //Poner el foco en la ventana
    //     iFrame.contentWindow.print();//Imprimir la veentana
    // }




});



/*
 * al momento de cargar
 */
onUpdated( () => {
    //Buscar la secuencia si esta en la configuracion
    if (page.props.setting.sequence) getSequence(form.invoice_type);

    //Pasar los datos a la variable si existe
    if (propsW.pdf != undefined && propsW.pdf != "") pdfString.value = propsW.pdf;

    //Para verificar
    let msjError = "Este Codigo No es Validos, Introduzca Uno Validado";

    //Valizar si es igual
    if (page.props.errors.general === msjError)
    {
        showFormReturn.value = true;
    }

    //Verificar para actualizar los datos
    setDataForm();
});

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

//DDATOS DEL dpf
const pdfString:Ref<string | null> = ref(null);
const showPdf:Ref<boolean> = ref(false);


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
    type_payment:"contado",
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
Propidades computada
 */
const checkShowPdf = computed(()=>{
    showPdf.value = propsW.pdf != '';
    //PAsar el valos de los datos
    return pdfString.value != null && pdfString.value != '';
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

    //Verifocar so existe la secuencia
    if (!page.props.setting.sequence)
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
    //Primero verifica la cantidad
    returned()

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

    //Verificar el calculo de los datos
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

    //llamar el tipo de boleta
    await getSequence(form.invoice_type);
};


/**
 * Obtener los datos de productos
 * @param item
 */
const getData = (item:productDataI) => {
    //Obtener los datos de productos
    let info:infoSaleI | undefined = form.info_sale.find((el) => el.product_id === item.id);

    // Verificar si el producto exite
    if (info?.product_id  === item.id)
    {
        info.stock += 1;
        showProduct.value = false;

    }else{

       //Pasar los datos al formulario
       form.info_sale.push({
           amount: 0,
           code: item.code,
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
                    onSuccess: () => {
                        successHttp(`Item : ${info.product_name} Eliminado Correctamente` );
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
            only: ['products','clients','saleOpen','invoiceType','pdf'],
            onSuccess: () => {
                form.reset();
                successHttp('Nota de Credito Creada Correctamente');
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
                        //Verificar si fue cerrado la mesa

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
        axios.get(route('product.get.code', {search: form.code_value}))
            .then((res) =>{
                //Formatear los datos
                const product:productDataI = res.data;
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
                code: el.code,
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
    form.client_name = item.client_name;
    form.close_table = item.close_table;
    form.comment = item.comment ? item.comment.content : "";
    form.comment_id = item.comment ? item.comment.id : 0;

    //Cerra la ventana
    showSaleOpen.value = false;

}


/*
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

    //Llamar el metodo para el calculo
    returned();

}


/*
 * Calcular la nota de credito
 */
const amountCreditNote = () => {
    //REalizar el calculo de notas de credito
    form.credit_notes_amount = form.credit_notes.reduce((acc, cur) => acc + cur.n_available, 0);
    //Datos pendiente por pagar
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
            form.client_rnc_status = info.status;
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
            <div
                class=" bg-gray-200 p-5 max-w-[1180px] rounded-md mx-auto overflow-hidden">
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
                                                :readonly="form.invoice_type === 'B04' "
                                                class=" w-[400px] pr-10"
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
                                        class="icon-efect text-3xl fa-solid fa-box-open"></i>

<!--                                    Btn de Cuentas abierta-->
                                    <i
                                        title="Cuentas Abiertas"
                                        @click="showSaleOpen = !showSaleOpen"
                                        class=" ml-3 icon-efect text-3xl  fa-solid fa-table-cells-row-unlock"></i>

<!--                                 BTN Devolucion-->
                                    <i
                                        title="Devoluciones"
                                        @click="showFormReturn = !showFormReturn"
                                        class=" ml-3 icon-efect text-3xl fa-solid fa-arrow-rotate-left"></i>

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
                        <div
                            class="max-h-[400px] border-t-2 mt-3 border-black overflow-y-auto shadow-lg p-3 rounded-md">
                            <table class="w-full">
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
                                    <tr v-for="(item, index) in form.info_sale" :key="index">
                                        <td>
                                            {{index+1}}
                                        </td>
                                        <td>
                                            {{item.product_name}}
                                        </td>
                                        <td class="max-w-[5rem]">
                                            <InputNumber
                                                @valueChange="totalAmount(index)"
                                                class="!bg-transparent"
                                                v-model="item.stock"
                                                inputId="locale-us"
                                                locale="en-US"
                                                :max-fraction-digits="2"
                                                :minFractionDigits="2"
                                                fluid/>
                                        </td>
                                        <td>
                                            {{getMoney(item.tax)}}
                                        </td>
                                        <td class="max-w-[5rem]">
                                            <InputNumber
                                                @valueChange="totalAmount(index)"
                                                v-model="item.price"
                                                inputId="locale-us"
                                                locale="en-US"
                                                :max-fraction-digits="2"
                                                :minFractionDigits="2"
                                                fluid/>
                                        </td>
                                        <td class="max-w-[4rem]">
                                            <InputNumber
                                                @valueChange="totalAmount(index)"
                                                v-model="item.discount"
                                                :min="0"
                                                :max="100"
                                                :allow-empty="false"
                                                prefix="%"
                                                fluid/>
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
                                <div class=" col-span-2">
                                    <fieldset class=" relative max-w-[400px]">
                                        <legend>
                                            Comentario
                                        </legend>
                                        <Textarea
                                            v-model="form.comment"
                                            autoResize
                                            rows="3"
                                            maxlength="250"
                                            cols="50" />
                                        <InputError :message="form.errors.comment"/>

                                    </fieldset>
                                </div>

                                <div class=" col-end-7 col-span-2">
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

<!--            Ventana de Devuelta-->
            <FloatBox
                header="Retornos"
                v-model:show="showReturn">
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

<!--            Monstrar los PDF-->
            <FloatBox
                header="Ventana de Imprsión"
                v-model:show="showPdf">
                <ShowPdf
                    ref="pdfBox"
                    id="pdfBox"
                    :pdf="pdfString"
                    v-show="checkShowPdf"
                    @close-window="pdfString = null "/>
            </FloatBox>


            <!-- Mostrar flotante los clientes --->

            <FloatBox
                header="Clientes"
                v-model:show="showClient">
                <FloatShowCli
                    class=" w-4/5 rounded-md py-5"
                    @get-data="selectClient"
                    :clients="propsW.clients"/>

            </FloatBox>

            <!-- Ventana de productos-->

            <FloatBox
                header="Productos"
                v-model:show="showProduct">
                <FloatShowPro
                    class=" bg-gray-200 rounded-md px-10 py-5"
                    @select="getData"
                    :products="propsW.products"/>
            </FloatBox>


            <!-- Vetana de las ordenes abierta -->
            <FloatBox
                header="Cuentas Abiertas"
                v-model:show="showSaleOpen">
                <SaleOpenShow
                    @sen-data="getSaleOpen"
                    class=" bg-gray-200 rounded-md px-10 py-5"
                    :sale-open="propsW.saleOpen"/>
            </FloatBox>


<!--            Formulario para la nota de credito-->

            <FloatBox
                header="Devolución"
                v-model:show="showFormReturn">
                <ReturnForm
                    :error="page.props.errors.general"
                    @closeFormReturn="showFormReturn = false"/>
            </FloatBox>
        </div>

    </AppLayout>
</template>

