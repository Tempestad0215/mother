<script setup lang="ts">
import {computed, onMounted, reactive, ref, Ref} from "vue";
import {clientDocumentI, clientEditI, clientPriceI, clientTypeI} from "@/Interfaces/ClientInterface";
import {useForm} from "@inertiajs/vue3";
import {useRoute} from "ziggy-js";
import {Card, Select, ToggleSwitch, FloatLabel, InputText, Divider, Button, InputMask} from "primevue"




const route = useRoute()

/**
 * propsW de la vantana
 */
const propsW = defineProps<{
    clientEdit?: clientEditI,
    update?: boolean,
    typeRNC: string[],
    clientType: clientTypeI,
    clientPrice: clientPriceI,
    clientDocument: clientDocumentI
}>();

/**
 * Al momento de cargar
 */
onMounted(()=>{
    //Verificar si existe datos para poner en el formulario
    if(propsW.clientEdit)
    {
        form.id = propsW.clientEdit.id;
        form.name = propsW.clientEdit.name;
        form.document = propsW.clientEdit.document
        form.personal_id = propsW.clientEdit.personal_id ? propsW.clientEdit.personal_id : "";
        form.phone = propsW.clientEdit.phone ? propsW.clientEdit.phone : "";
        form.email = propsW.clientEdit.email ? propsW.clientEdit.email : "" ;
        form.address = propsW.clientEdit.address ? propsW.clientEdit.address : "";
        form.comment = propsW.clientEdit?.comment || "";
        form.status = propsW.clientEdit.status;
        form.type = propsW.clientEdit.type;
        form.type_price = propsW.clientEdit.type_price

        //     Informacion de datos de credito
        form.amount = propsW.clientEdit.amount ?? 0;
        form.due_date = propsW.clientEdit.due_date ?? 0;
        form.late_fee = propsW.clientEdit.late_fee ?? 0;
        form.balance = propsW.clientEdit.balance ?? 0;


    }
});


//Posibles máscara para documents
const masks = reactive<Record<string, string>>({
    cedula: '999-9999999-9',
    pasaporte: 'A99999999',
    rnc: '999-999999'
});


const getClientType = computed(()=>{
    return Object.entries(propsW.clientType).map(([key, value])=>({
        label: key,
        value: value
    }))
})
const getClientPrice = computed(()=>{
    return Object.entries(propsW.clientPrice).map(([key, value])=>({
        label: key,
        value: value
    }))
})
const getClientDocument = computed(()=>{
    return Object.entries(propsW.clientDocument).map(([key, value])=>({
        label: key,
        value: value
    }))
})

const selectedMask = computed(()=>{
    return masks[form.document] || '';
})

/**
 * DAtos del formulario
 */
const form = useForm({
    id:0,
    type_rnc:"B02",
    name:"",
    personal_id:"",
    phone:"",
    email:"",
    address:"",
    type: propsW.clientEdit ? propsW.clientEdit.type : "contado",
    document: propsW.clientEdit ? propsW.clientEdit.document : "cedula",
    amount: 0,
    due_date: 0,
    balance: 0,
    consumed: 0,
    late_fee:0,
    status: true,
    receive_email: false,
    type_price: 1,
    comment:"",
    image: ""
});

/*
Funciones
 */

/**
 * Enviar los datos
 */
const submit = ():void => {

    // Si es actualziar
    if(propsW.update)
    {
        form.patch(route('client.update', form.id),{
            onSuccess:()=>{

            }
        });

        //Enviar los datos por post
    }else{

        // Enviar los datos
        form.post(route('client.store'),{
            onSuccess:()=>{

                form.reset();
            }
        });
    }
}

// Buscar el RNc si el tipo es diferente a B02
const searchRNC = async () => {

    // si el rnc es diferete, debe buscar el rnc registrado para cambiar el nombre de la razon socials
    // if (form.type_rnc !== "B02" && form.personal_id.length > 7)
    // {
    //
    //     // Buscar el rnc
    //     // const data = await getRncHelper(form.personal_id);
    //
    //     // Si el resultado es suspendido
    //     if (data == "SUSPENDIDO")
    //     {
    //         form.setError("type_rnc", "Comprobante suspendido");
    //         return;
    //     }
    //
    //     // Si el resultado es error
    //     if (data == "ERROR")
    //     {
    //         form.setError("type_rnc", "Error al consultar comprobante, intente nuevamente");
    //         return;
    //     }
    //
    //
    //     /*Verificar si es tipo objecto y no es nuelo*/
    //     if (typeof data === "object" && data !== null)
    //     {
    //         Swal.fire({
    //             title: "Desea Cambiar?",
    //             text: "RNC Valido, ¿ Prefiere Cambiar El Nombre a la Razon Social ?",
    //             icon: "question",
    //             showCancelButton: true,
    //             confirmButtonColor: "#3085d6",
    //             cancelButtonColor: "#d33",
    //             confirmButtonText: "Si, Cambiar!",
    //             cancelButtonText: "Cancelar",
    //         }).then((result) => {
    //             if (result.isConfirmed) {
    //                 form.name = data.razon_social;
    //             }
    //         });
    //     }

    // }
}



</script>

<template>
    <Card class="max-w-250">
        <template #header>
            <h3>Crear Cliente</h3>
        </template>
        <template #content>
            <form @submit.prevent="submit">
                <div class="flex flex-wrap gap-4 justify-center">
                    <FloatLabel variant="on">
                        <Select class="w-40" placeholder="Tipo de Cliente" v-model="form.type" :options="getClientType" option-label="label" option-value="value" />
                        <label for="type">Tipo Cliente</label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <Select class="w-40" placeholder="Precio de Ventas" v-model="form.type_price" :options="getClientPrice" option-label="label" option-value="value" />
                        <label for="">Precio</label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <Select class="w-50" placeholder="Documento" v-model="form.document" :options="getClientDocument" option-label="label" option-value="value" />
                        <label for="">Documento</label>
                    </FloatLabel>
                    <div class="flex items-center gap-2">
                        <ToggleSwitch v-model="form.receive_email" />
                        <label for="">Recibir Email</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <ToggleSwitch v-model="form.status"/>
                        <label for="">Estado</label>
                    </div>
                </div>
                <Divider/>
                <div class="grid grid-cols-2 gap-4 items-center justify-center">
                    <FloatLabel variant="on">
                        <InputText id="name" class="w-full" v-model="form.name" />
                        <label for="name">Nombre Completo <span class="text-red-500">*</span></label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <InputMask class="w-full" id="personal_id" v-model="form.personal_id" :mask="selectedMask" />
                        <label for="personal_id">Identificación</label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <InputText id="phone" class="w-full" v-model="form.phone" />
                        <label for="phone">Teléfono </label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <InputText type="email" id="email" class="w-full" v-model="form.email" />
                        <label for="email">Correo Electrónico </label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <InputText id="address" class="w-full" v-model="form.address" />
                        <label for="address">Dirección</label>
                    </FloatLabel>
                    <FloatLabel variant="on">
                        <InputText id="comment" class="w-full" v-model="form.comment" />
                        <label for="comment">Comentario </label>
                    </FloatLabel>


                </div>

                <div class="mt-5 text-right space-x-3">
                    <Button severity="warn" icon="pi pi-eraser" type="reset"  label="limpiar" />
                    <Button icon="pi pi-send" label="Registrar" type="submit" />
                </div>
            </form>


        </template>
    </Card>
</template>
