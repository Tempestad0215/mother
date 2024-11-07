<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@components/TextInput.vue';
import InputError from '@components/InputError.vue';
import PrimaryButton from '@components/PrimaryButton.vue';
import {onMounted, ref, Ref} from 'vue';
import {clienteEditI} from '@/Interfaces/ClientInterface';
import { successHttp } from '@/Global/Alert';
import LinkHeader from "@components/LinkHeader.vue";
import {getRncHelper} from "@/Global/Helpers";
import {rncUserI} from "@/Interfaces/Setting";
import Swal from "sweetalert2";
import Select from "primevue/select";
import InputText from "primevue/inputtext";
import ToggleButton from 'primevue/togglebutton';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import InputMask from 'primevue/inputmask';
import Textarea from 'primevue/textarea';

/**
 * propsW de la vantana
 */
const propsW = defineProps<{
    clientEdit?: clienteEditI,
    update?: boolean,
}> ();

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
        form.comment = propsW.clientEdit.comment.content;
        form.status = propsW.clientEdit.status;
        form.type = propsW.clientEdit.type;
    }
});


/*
Datos de la ventana
 */
const classRnc:Ref<string> = ref("");
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
const typoDocument:Ref<Array<any>> = ref([
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
])



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
    id:0,
    name:"",
    personal_id:"",
    phone:"",
    email:"",
    address:"",
    type: 'contado',
    document:"cedula",
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
const getRnc = async () => {
    if (form.document === 'rnc')
    {

        //Obtener la informacion del RNC
        let info:string = await getRncHelper(form.personal_id);


        if (info === "SUSPENDIDO")
        {
            form.setError("personal_id", "Este Contribuyente Esta Suspendido, Por Favor Elegir Otro");
            //Variable de error
            classRnc.value = "border-red-800 text-red-500 animate-pulse";
        }else if (info === "ERROR")
        {
            form.setError("personal_id", "Este Contribuyente No Pudo Ser Encontrado");
            //Variable de error
            classRnc.value = "border-red-800 text-red-500 animate-pulse";

        }else if (info === "CANCELLED")
        {

        }
        else{

            //Pasar los datos del json y transformar
            let infoParse:rncUserI = JSON.parse(info);
            //Poner los datos en verde
            classRnc.value = "border-green-800 text-green-500";
            //Mostrar el mensaje de la razon social
            await Swal.fire({
                title: "Datos Contribuyente",
                html: `
                <p>
                    <strong>RNC :</strong>
                    ${infoParse.rnc}
                </p>
                <p>
                    <strong>Razon Social :</strong>
                    ${infoParse.razon_social}
                </p>
            `,
                icon: "info"
            });

            //Cambiar el nombre a razon social
            form.name = infoParse.razon_social;

        }
        //Limpiar el error luego de 5 segundo
        setTimeout(() => {
            form.clearErrors("personal_id");
            classRnc.value = "";
        },5000);
    }


}




</script>



<template>
    <!-- Contenido -->
    <AppLayout
        title="Cliente">
        <template #header >
            <LinkHeader
                :active="true"
                :href="route('client.create')">
                Registrar
            </LinkHeader>
            <LinkHeader
                :href="route('client.show')">
                Mostrar
            </LinkHeader>

        </template>

        <!-- Formulario de registro -->
        <div>
            <form
                class="bg-gray-200  rounded-md p-5 max-w-[1100px] mx-auto"
                @submit.prevent="submit">

<!--                Titulo del formulario-->
                <h2 class="col-span-full text-2xl font-bold text-center mb-4">
                    {{ propsW.update ? 'Actualización' :  'Registro'}} de cliente
                </h2>



                <div class="flex justify-end items-center">
                    <!--                Tipo de cliente-->
                    <div>
                        <InputLabel for="tye" value="Tipo"/>
                        <Select
                            v-model="form.type"
                            :options="typeClient"
                            placeholder="Tipo Cliente"
                            option-label="name"
                            option-value="code"/>
                        <InputError :message="form.errors.type"/>
                    </div>


                    <!--Tipo de documento-->
                    <div class="ml-3">
                        <InputLabel for="document" value="Documento"/>
                        <Select
                            v-model="form.document"
                            :options="typoDocument"
                            default-value="cedula"
                            option-label="name"
                            option-value="code"/>
                        <InputError :message="form.errors.document"/>
                    </div>


                    <!-- Estatus del cliente -->
                    <div class="ml-3">
                        <InputLabel value="Activo"/>
                        <ToggleButton
                            v-model="form.status"
                            onLabel="SI" offLabel="NO" />
                        <InputError :message="form.errors.status" />
                    </div>

                </div>


<!--                Datos personales-->
                <fieldset class="border-2 border-gray-400 p-5 rounded-md grid grid-cols-2 gap-3">
                    <legend>
                        Datos Personales
                    </legend>
                    <div>
                        <InputLabel
                            for="name"
                            value="Nombre Completo *"/>
                        <InputText
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

                        <InputGroup>
                            <InputMask
                                v-if="form.document === 'cedula'"
                                id="basic"
                                v-model="form.personal_id"
                                mask="999-9999999-9"
                                placeholder="125-6536895-6" />

                            <InputMask
                                v-if="form.document === 'rnc'"
                                id="basic"
                                v-model="form.personal_id"
                                mask="999-99999-9"
                                placeholder="563-54569-8" />

                            <InputMask
                                v-if="form.document === 'pasaporte'"
                                id="basic"
                                v-model="form.personal_id"
                                mask="a*?9999999"
                                placeholder="AA1234567" />
                            <InputGroupAddon>
                                <i
                                    @click="getRnc()"
                                    title="Buscar RNC"
                                    class=" fa-solid fa-magnifying-glass"></i>
                            </InputGroupAddon>

                        </InputGroup>



                        <!-- Error -->
                        <InputError :message="form.errors.personal_id" />
                    </div>

                    <!-- Telefono -->
                    <div class="">
                        <InputLabel
                            for="phone"
                            value="Teléfono"/>
                        <InputMask
                            id="basic"
                            v-model="form.phone"
                            fluid
                            mask="+9 (999) 999-9999"
                            placeholder="+1 (829) 352-6526" />

                        <!-- Error -->
                        <InputError :message="form.errors.phone" />
                    </div>

                    <!-- correo -->
                    <div class="">
                        <InputLabel
                            for="phone"
                            value="Correo"/>
                        <InputText
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

                        <InputText
                            placeholder="Puerto Plata, Padres Las Casas #12"
                            fluid
                            v-model="form.address"
                            type="email"
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
                        class="border-2 border-gray-400 p-5 rounded-md grid grid-cols-2 gap-3">
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
                        class="border-2 border-gray-400 p-5 rounded-md grid grid-cols-2 gap-3">
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
<!--                        <div>-->
<!--                            <InputLabel for="advance_balance" value="Balance Disponible"/>-->
<!--                            <p>-->
<!--                                {{getMoney(12530.12)}}-->
<!--                            </p>-->
<!--                            <InputError />-->
<!--                        </div>-->

                    </fieldset>

                </div>



                <!-- Datos de comentario y  -->
                <div class="flex mt-5">

                    <div>
                        <InputLabel for="comment" value="Comentario"/>

                        <Textarea
                            v-model="form.comment"
                            rows="2"
                            auto-resize
                            cols="60" />
                        <InputError :message="form.errors.comment"/>
                    </div>





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
