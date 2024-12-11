<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@components/TextInput.vue';
import InputError from '@components/InputError.vue';
import PrimaryButton from '@components/PrimaryButton.vue';
import {computed, onMounted, reactive, ref, Ref} from 'vue';
import {clientEditI} from '@/Interfaces/Client';
import { successHttp } from '@/Global/Alert';
// import {getRncHelper} from "@/Global/Helpers";
// import {rncUserI} from "@/Interfaces/Setting";
// import Swal from "sweetalert2";
import ToggleButton from "@components/ToggleButton.vue";
import SelectOption from "@components/SelectOption.vue";
import TabLink from "@components/TabLink.vue";

/**
 * propsW de la vantana
 */
const propsW = defineProps<{
    clientEdit?: clientEditI,
    update?: boolean,
}> ();







/**
 * Al momento de cargar
 */
onMounted(()=>{


    //colocar datos por defecto
    form.document = "cedula";
    form.type = "contado";
    //Verificar si existe datos para poner en el formulario
    if(propsW.clientEdit)
    {
        form.uuid = propsW.clientEdit.uuid;
        form.name = propsW.clientEdit.name;
        form.document = propsW.clientEdit.document
        form.personal_id = propsW.clientEdit.personal_id ? propsW.clientEdit.personal_id : "";
        form.phone = propsW.clientEdit.phone ? propsW.clientEdit.phone : "";
        form.email = propsW.clientEdit.email ? propsW.clientEdit.email : "" ;
        form.address = propsW.clientEdit.address ? propsW.clientEdit.address : "";
        form.comment = propsW.clientEdit.comment.content;
        form.status = propsW.clientEdit.status;
        form.type = propsW.clientEdit.type;
    }


});


/*
Datos de la ventana
 */
// const classRnc:Ref<string> = ref("");
const typeClient:Ref<Array<any>> = ref([
    {
        name: "Contado",
        code: 'contado'
    },
    // {
    //     name: "Credito",
    //     code: 'credito'
    // }
])
const typeDocument:Ref<Array<any>> = ref([
    {
        name: "Cédula",
        code: 'cedula'
    },{
        name: "RNC",
        code: 'rnc'
    },{
        name: "Pasaporte",
        code: 'pasaporte'
    },
    // {
    //     name: "Credito",
    //     code: 'credito'
    // }
]);


//Posibles mascara para docuemntos
const masks = reactive<Record<string, string>>({
    cedula: '###-#######-#',
    pasaporte: 'A########',
    rnc: '###-######'
});


const selectedMask = computed(()=>{
    return masks[form.document] || '';
})



/**
 * Propiedades computada
 */

//Veriificar si es credito o contado
// const isMandatory = computed(()=>{
//     //Retorna true cuando es credito o anticipo
//    if(form.type === "credito" || form.type === "anticipo")
//    {
//        return true;
//    }
//
//    //Retorna true cuando es a contado
//    return false;
// });

/**
 * DAtos del formulario
 */
const form = useForm({
    uuid:"",
    name:"",
    personal_id:"",
    phone:"",
    email:"",
    address:"",
    type: propsW.clientEdit ? propsW.clientEdit.type : "contado",
    document: propsW.clientEdit ? propsW.clientEdit.document : "cedula",
    credit_limit: "",
    credit_day:"",
    credit_balance:"",
    credit_expired:"",
    advance_amount:"",
    advance_date:"",
    advance_expire:"",
    advance_balance:"",
    status: true,
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
        form.patch(route('client.update', form.uuid),{
            onSuccess:()=>{
                successHttp('Datos actualizado correctamente');
            }
        });

    //Enviar los datos por post
    }else{

        // Enviar los datos
        form.post(route('client.store'),{
            onSuccess:()=>{
                successHttp('Datos registrado correctamente');
                form.reset();
            }
        });
    }
}


/**
 * Otener el RNC
 */
// const getRnc = async () => {
//     if (form.document === 'rnc')
//     {
//
//         //Obtener la informacion del RNC
//         let info:string = await getRncHelper(form.personal_id);
//
//
//         if (info === "SUSPENDIDO")
//         {
//             form.setError("personal_id", "Este Contribuyente Esta Suspendido, Por Favor Elegir Otro");
//             //Variable de error
//             classRnc.value = "border-red-800 text-red-500 animate-pulse";
//         }else if (info === "ERROR")
//         {
//             form.setError("personal_id", "Este Contribuyente No Pudo Ser Encontrado");
//             //Variable de error
//             classRnc.value = "border-red-800 text-red-500 animate-pulse";
//
//         }else if (info === "CANCELLED")
//         {
//
//         }
//         else{
//
//             //Pasar los datos del json y transformar
//             let infoParse:rncUserI = JSON.parse(info);
//             //Poner los datos en verde
//             classRnc.value = "border-green-800 text-green-500";
//             //Mostrar el mensaje de la razon social
//             await Swal.fire({
//                 title: "Datos Contribuyente",
//                 html: `
//                 <p>
//                     <strong>RNC :</strong>
//                     ${infoParse.rnc}
//                 </p>
//                 <p>
//                     <strong>Razon Social :</strong>
//                     ${infoParse.razon_social}
//                 </p>
//             `,
//                 icon: "info"
//             });
//
//             //Cambiar el nombre a razon social
//             form.name = infoParse.razon_social;
//
//         }
//         //Limpiar el error luego de 5 segundo
//         setTimeout(() => {
//             form.clearErrors("personal_id");
//             classRnc.value = "";
//         },5000);
//     }
//
//
// }




</script>



<template>
    <!-- Contenido -->
    <AppLayout
        title="Cliente">
        <template #header >
            <TabLink
                :active="true"
                :href="route('client.create')">
                Registrar
            </TabLink>
            <TabLink
                :href="route('client.show')">
                Mostrar
            </TabLink>

        </template>

        <!-- Formulario de registro -->
        <div>
            <form
                class="bg-blue-300  rounded-md p-5 max-w-[1100px] mx-auto"
                @submit.prevent="submit">

<!--                Titulo del formulario-->
                <h2 class="col-span-full text-2xl font-bold text-center mb-4">
                    {{ propsW.update ? 'Actualización' :  'Registro'}} de cliente
                </h2>


                <div class="flex justify-end items-center">
                    <!--                Tipo de cliente-->
                    <div>
                        <InputLabel for="tye" value="Tipo"/>
                        <SelectOption
                            label-value="Tipo"
                            option-label="name"
                            v-model="form.type"
                            :default-value="form.type"
                            :is-read-only="true"
                            option-value="code"
                            placeholder="--Tipo--"
                            :options="typeClient"/>
                        <InputError :message="form.errors.type"/>
                    </div>


                    <!--Tipo de documento-->
                    <div class="ml-3">
                        <InputLabel for="document" value="Documento"/>
                        <SelectOption
                            v-model="form.document"
                            :default-value="form.document"
                            label-value="Tipo"
                            :is-read-only="true"
                            option-label="name"
                            option-value="code"
                            placeholder="--Documento--"
                            :options="typeDocument"/>
                        <InputError :message="form.errors.document"/>
                    </div>


                    <!-- Estatus del cliente -->
                    <div class="ml-3">
                        <ToggleButton
                            v-model="form.status"
                            off-label="Inactivo"
                            on-label="Activo"
                            label="Estado"/>
<!--                        <ToggleButton-->
<!--                            v-model="form.status"-->
<!--                            onLabel="SI" offLabel="NO" />-->
                        <InputError :message="form.errors.status" />
                    </div>

                </div>


<!--                Datos personales-->
                <fieldset class="field">
                    <legend>
                        Datos Personales
                    </legend>
                    <div>
                        <InputLabel
                            for="name"
                            value="Nombre Completo *"/>
                        <TextInput
                            placeholder="Nombre Completo"
                            fluid
                            v-model="form.name"
                            maxlength="75"
                            />

                        <!-- Error -->
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="relative">
                        <InputLabel
                            for="personal_id"
                            value="Cédula / Pasaporte /RNC"/>

                        <TextInput
                            id="basic"
                            v-model="form.personal_id"
                            v-mask="selectedMask"
                            placeholder="125-6536895-6" />
                        <!-- Error -->
                        <InputError :message="form.errors.personal_id" />
                    </div>

                    <!-- Telefono -->
                    <div class="">
                        <InputLabel
                            for="phone"
                            value="Teléfono"/>
                        <TextInput
                            id="basic"
                            v-model="form.phone"
                            fluid
                            v-mask="['+# (###) ###-####', '+## (###) ###-####']"
                            placeholder="+1 (829) 352-6526" />

                        <!-- Error -->
                        <InputError :message="form.errors.phone" />
                    </div>

                    <!-- correo -->
                    <div class="">
                        <InputLabel
                            for="phone"
                            value="Correo"/>
                        <TextInput
                            placeholder="example@example.com"
                            fluid
                            v-model="form.email"
                            type="email"
                            maxlength="150"/>

                        <!-- Error -->
                        <InputError :message="form.errors.email" />
                    </div>

                    <!-- direccion -->
                    <div class=" col-span-full">
                        <InputLabel
                            for="phone"
                            value="Dirección"/>

                        <TextInput
                            placeholder="Puerto Plata, Padres Las Casas #12"
                            fluid
                            v-model="form.address"
                            type="text"
                            maxlength="150"/>

                        <!-- Error -->
                        <InputError :message="form.errors.address" />
                    </div>
                </fieldset>
                <!-- Nombre -->



<!--             Datos de credito-->
                <div class="grid grid-cols-2 gap-4">
                    <fieldset
                        v-if="form.type === 'credito'"
                        class="field">
                        <legend>
                            Datos Credito
                        </legend>
                        <div>
                            <InputLabel for="credit_limit" value="Limite de credito"/>
                            <TextInput
                                v-model="form.credit_limit"
                                class="w-full"
                                type="number" />
                            <InputError :message="form.errors.credit_limit"/>
                        </div>

                        <div>
                            <InputLabel for="credit_day" value="Dias para pagar"/>
                            <TextInput
                                v-model="form.credit_day"
                                class="w-full"
                                type="number" />
                            <InputError :message="form.errors.credit_day" />
                        </div>

<!--                        <div>-->
<!--                            <InputLabel for="curren_balance" value="Balance Actual"/>-->
<!--                            <p>-->
<!--                                {{getMoney(1253.26)}}-->
<!--                            </p>-->
<!--                            <InputError/>-->
<!--                        </div>-->
<!--                        <div>-->
<!--                            <InputLabel for="credit_expired" value="Balance Vencido"/>-->
<!--                            <p>-->
<!--                                {{getMoney(0.0)}}-->
<!--                            </p>-->
<!--                            <InputError/>-->
<!--                        </div>-->
                    </fieldset>


<!--                  Datos de anticipo  -->
                    <fieldset
                        v-if="form.type === 'anticipo'"
                        class="field">
                        <legend>
                            Datos Anticipo
                        </legend>
                        <div>
                            <InputLabel for="advance_amount" value="Cantidad"/>
                            <TextInput
                                v-model="form.advance_amount"
                                class="w-full"
                                type="number" />
                            <InputError :message="form.errors.advance_amount" />
                        </div>
                        <div>
                            <InputLabel for="advance_date" value="Fecha"/>
                            <TextInput
                                v-model="form.advance_date"
                                class="w-full"
                                type="date" />
                            <InputError :message="form.errors.advance_date" />
                        </div>
                        <div>
                            <InputLabel for="advance_expire" value="Fecha de vencimiento"/>
                            <TextInput
                                v-model="form.advance_expire"
                                type="date" />
                            <InputError :message="form.errors.advance_expire" />
                        </div>

                    </fieldset>
                </div>


<!--                Informacion extra-->
                <fieldset class="field flex">
                    <legend>
                        Info Extra
                    </legend>
                    <div>
                        <InputLabel for="comment" value="Comentario"/>
                        <textarea
                            name="comment"
                            class="area"
                            v-model="form.comment"
                            rows="2"
                            cols="60" />
                        <InputError :message="form.errors.comment"/>
                    </div>

                    <div class="">
                        <InputLabel for="comment" value="Imagen" />
                        <TextInput
                            @input=" form.image = $event.target.files[0]"
                            class="file"
                            type="file"
                            name="image" />
                    </div>
                </fieldset>



                <!-- Datos de comentario y  -->
                <div class="flex mt-5 gap-3">







                    <!-- Botones para enviar -->
                    <div class="flex flex-1 justify-end items-center space-x-5">
                        <PrimaryButton
                            :disabled="form.processing">
                            {{ propsW.update ? 'Actualizar' :  'Registrar'}}
                        </PrimaryButton>

                    </div>
                </div>



            </form>

        </div>
    </AppLayout>
</template>
