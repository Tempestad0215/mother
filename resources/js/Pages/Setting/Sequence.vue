<script setup lang="ts">
import {Head, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import LinkHeader from "@components/LinkHeader.vue";
import InputLabel from "@components/InputLabel.vue";
import InputError from "@components/InputError.vue";
import TextInput from "@components/TextInput.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import SecondaryButton from "@components/SecondaryButton.vue";
import {successHttp} from "@/Global/Alert";
import {sequenceDataI} from "@/Interfaces/Setting";
import {onMounted} from "vue";
import Swal from "sweetalert2";


/*
Propiedades
 */
const propsW = defineProps<{
    sequenceType:  string[];
    sequencesData: sequenceDataI[],
    sequenceEdit?: sequenceDataI
}>();


/*
Al momentod de cargar
 */
onMounted(() => {

    //Verificar si existe la secuencia para editar
    if (propsW.sequenceEdit)
    {
        form.id = propsW.sequenceEdit.id;
        form.code = propsW.sequenceEdit.code;
        form.type = propsW.sequenceEdit.type;
        form.from = propsW.sequenceEdit.from.toFixed(2);
        form.to =  propsW.sequenceEdit.to.toFixed(2);
        form.next = propsW.sequenceEdit.next.toFixed(2);
        form.advise = propsW.sequenceEdit.advise.toFixed(2);
        form.num_request = propsW.sequenceEdit.num_request;
        form.num_authorization = propsW.sequenceEdit.num_authorization;
        form.date_request = propsW.sequenceEdit.date_request;
        form.date_expire = propsW.sequenceEdit.date_expire;
    }
});


/*
Formulario
 */
const form = useForm({
    id:0,
    code:"",
    type:"",
    from:"",
    next:"",
    to:"",
    advise:"",
    num_request:"",
    num_authorization:"",
    date_request:"",
    date_expire:"",
    status:true,
    general:"",
});


/*
Funciones
 */

/**
 * Enviar los datos
 */
const submit = ():void => {
    form.post(route('sequence.store'),{
        onSuccess:() => {
            //Mensjae de exito
            successHttp('Registro Guiardado Correctamente');

            //Limpiar el formulario
            form.reset();
        },
        onError:() => {
            console.log('error');
        }
    });
}

/**
 * Editar las secuncia
 */
const edit = (id:number):void => {
    router.get(route('sequence.edit',{sequence: id}),{},{

    });
}

/**
 * Eliminar la secuencia
 */
const destroy = (id:number):void => {
    Swal.fire({
        title: "Desea Eliminar?",
        text: "Los Cambios Realizados Son Irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('sequence.destroy',{sequence: id}),{
                preserveState: true,
                preserveScroll: true,
                onSuccess:() => {
                    successHttp('Registro Eliminado Correctamente')
                },
                onError:() => {
                    console.log('error');
                }
            });
        }
    });


}


</script>

<template>
<!--    Titulo de la ventana-->
    <Head title="Correlativos"/>

<!--  Contenido general-->
    <AppLayout>
        <template #header>
            <LinkHeader
                :href="route('setting.index')">
                Ajustes
            </LinkHeader>
            <LinkHeader
                :active="true"
                :href="route('sequence.create')">
                Correlativos
            </LinkHeader>
            {{form.errors.general}}
        </template>

        <!--        Conteneido de la ventana-->
        <div class=" bg-gray-200 p-5 rounded-md max-w-[1180px] mx-auto grid grid-cols-3 gap-3" >


            <div class="col-span-2">
                <!--            Tabla de las secuencias registrada-->
                <table class="w-full" >
                    <caption class=" text-2xl font-bold">
                        Secuencias
                    </caption>

                    <thead class="border-b-2 border-gray-800 text-left">
                    <tr>
                        <th>Código</th>
                        <th>Tipo</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                        <th>Sig.</th>
                        <th>Avi.</th>
                        <th>Act</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr
                        v-for="(item, index) in propsW.sequencesData"
                        :key="index"
                        class="odd:bg-gray-400">
                        <td>{{item.code}}</td>
                        <td>{{item.type}}</td>
                        <td>{{item.from}}</td>
                        <td>{{item.to}}</td>
                        <td>{{item.next}}</td>
                        <td>{{item.advise}}</td>
                        <td >
                            <i
                                @click="edit(item.id)"
                                class="icon-efect fa-solid fa-pen-to-square"></i>
                            <i
                                @click="destroy(item.id)"
                                class=" ml-3 icon-efect fa-solid fa-trash"></i>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>



            <form @submit.prevent="submit"  class=" ">
<!--                Generales-->
                <fieldset
                    class="border-2 border-gray-800 rounded-md p-3">
                    <legend class="px-3">
                        Secuencias Correlativos
                    </legend>

<!--                    Tipo de sequencia-->
                    <div>
                        <InputLabel for="type"  value="Tipo"/>
                        <select
                            class="border-gray-300 rounded-md w-full"
                            v-model="form.type"
                            name="type" id="type"> >
                            <option value="">------ Seleccione -----</option>
                            <option
                                v-for="(item, index) in propsW.sequenceType" :key="index"
                                :value="item">
                                {{item}}
                            </option>
                        </select>
                        <InputError :message="form.errors.type"/>
                    </div>

<!--                    From-->
                    <div>
                        <InputLabel
                            for="from"
                            value="Desde"/>
                        <TextInput
                            class="w-full"
                            type="number"
                            name="from"
                            v-model="form.from"
                            id="from"/>
                        <InputError
                            :message="form.errors.from"/>
                    </div>

                    <!--Hasta-->
                    <div>
                        <InputLabel
                            for="to"
                            value="Hasta"/>
                        <TextInput
                            class="w-full"
                            type="number"
                            name="to"
                            v-model="form.to"
                            id="to"/>
                        <InputError
                            :message="form.errors.to"/>
                    </div>

                    <!--Aviso-->
                    <div>
                        <InputLabel
                            for="advise"
                            value="Aviso"/>
                        <TextInput
                            class="w-full"
                            v-model="form.advise"
                            type="number"
                            name="to"
                            id="to"/>
                        <InputError
                            :message="form.errors.advise"/>
                    </div>

<!--                    siguiente-->
                    <div>
                        <InputLabel for="next" value="Siguiente"/>
                        <span>{{form.next || 0}}</span>
                    </div>





                </fieldset>
<!--                Informacion de numero-->
                <fieldset
                    class="border-2 border-gray-800 rounded-md p-3">
                    <legend class="px-3">
                        Números
                    </legend>
                    <!--                    Numero de solicitud-->
                    <div>
                        <InputLabel
                            for="num_request"
                            value="Número de Solicitud"/>
                        <TextInput
                            class="w-full"
                            name="num_request"
                            v-model="form.num_request" />
                        <InputError :message="form.errors.num_request"/>
                    </div>

                    <!--                    Numero de aprobacion-->
                    <div>
                        <InputLabel
                            for="num_authorization"
                            value="Número de Autorización"/>
                        <TextInput
                            class="w-full"
                            name="num_authorization"
                            v-model="form.num_authorization" />
                        <InputError
                            :message="form.errors.num_authorization"/>
                    </div>
                </fieldset>


<!--                Fechas-->
                <fieldset class="border-2 border-gray-800 rounded-md p-3" >
                    <legend class="px-3">
                        Fechas
                    </legend>
                    <!--                    Fecha de solicitud-->
                    <div>
                        <InputLabel for="date_request" value="Fecha de Solicitud"/>
                        <TextInput
                            class="w-full"
                            type="date"
                            name="date_request"
                            v-model="form.date_request"/>
                        <InputError :message="form.errors.date_request"/>
                    </div>


                    <!--                    Fecha Expira-->
                    <div>
                        <InputLabel for="date_expire" value="Fecha de Vencimiento"/>
                        <TextInput
                            class="w-full"
                            type="date"
                            name="date_expire"
                            v-model="form.date_expire"/>
                        <InputError :message="form.errors.date_expire"/>
                    </div>
                </fieldset>

<!--                Botones para enviar-->
                <div class="mt-5 text-right">
                    <SecondaryButton>
                        Limpiar
                    </SecondaryButton>
                    <PrimaryButton
                        class="ml-5">
                        Registrar
                    </PrimaryButton>
                </div>
            </form>
        </div>

    </AppLayout>

</template>

