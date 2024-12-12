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
import ToggleButton from "@components/ToggleButton.vue";
import TabLink from "@components/TabLink.vue";
import {Money} from "v-money3";
import {configPercent, moneyConfig} from "@/Global/Helpers";


/**
 * propsW de la vantana
 */
const propsW = defineProps<{
    clientEdit?: clientEditI,
    update?: boolean,
}>();

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
    {
        name: "Credito",
        code: 'credito'
    },
    {
        name: "Anticipo",
        code: 'anticipo'
    }
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
 * DAtos del formulario
 */
const form = useForm({
    uuid:"",
    name:"",
    personal_id:"",
    phone:"",
    email:"",
    address:"",
    type: propsW.clientEdit ? propsW.clientEdit.type : "credito",
    document: propsW.clientEdit ? propsW.clientEdit.document : "cedula",
    limit: "",
    due_date: "",
    balance:"",
    consumed:"",
    late_fee_interest:"",
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
                        <select
                            v-model="form.type"
                            class="inputGeneral py-1">
                            <option
                                v-for="(item, index) in typeClient" :key="index"
                                :value="item.code">
                                {{item.name}}
                            </option>
                        </select>
                        <InputError :message="form.errors.type"/>
                    </div>


                    <!--Tipo de documento-->
                    <div class="ml-3">
                        <InputLabel for="document" value="Documento"/>
                        <select
                            v-model="form.document"
                            class="inputGeneral py-1">
                            <option
                                v-for="(item, index) in typeDocument" :key="index"
                                :value="item.code">
                                {{item.name}}
                            </option>
                        </select>
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
                            :placeholder="selectedMask" />
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
                <div class="">
                    <fieldset
                        v-if="form.type !== 'contado'"
                        class="field flex justify-between">
                        <legend>
                            Informacion de Pagos
                        </legend>
                        <div>
                            <InputLabel for="credit_limit" value="Limite de credito"/>
                            <Money
                                class="inputGeneral"
                                v-bind="moneyConfig"
                                v-model="form.limit" />
                            <InputError :message="form.errors.limit"/>
                        </div>

                        <div>
                            <InputLabel for="credit_day" value="Dias para pagar"/>
                            <Money
                                class="inputGeneral"
                                v-bind="moneyConfig"
                                v-model="form.due_date" />
                            <InputError :message="form.errors.due_date" />
                        </div>
                        <div>
                            <InputLabel for="credit_day" value="Interes por Mora"/>
                            <Money
                                class="inputGeneral"
                                v-bind="configPercent"
                                v-model="form.late_fee_interest" />
                            <InputError :message="form.errors.due_date" />
                        </div>
<!--                        Balance-->
                        <div>
                            <InputLabel for="" value="Balance"/>
                            <span>125.32</span>
                        </div>
<!--                        Consumido-->
                        <div>
                            <InputLabel for="" value="Consumido"/>
                            <span>125.32</span>
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
