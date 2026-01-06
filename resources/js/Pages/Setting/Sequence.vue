<script setup lang="ts">
import {Head, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import LinkHeader from "@components/LinkHeader.vue";
import TextInput from "@components/TextInput.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import SecondaryButton from "@components/SecondaryButton.vue";
import {successHttp} from "@/Global/Alert";
import {sequenceDataI} from "@/Interfaces/SettingInterface";
import {onMounted, reactive} from "vue";
import Swal from "sweetalert2";
import ErrorComponent from "@components/ErrorComponent.vue";
import TabLink from "@components/TabLink.vue";
import {useRoute} from "ziggy-js";

const route = useRoute();
/*
Propiedades
 */
const propsW = defineProps<{
    sequenceType:  string[];
    sequencesData: sequenceDataI[],
    sequenceEdit?: sequenceDataI
}>();


const state = reactive({
    first_error: ""
})

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
        form.from = propsW.sequenceEdit.from;
        form.to =  propsW.sequenceEdit.to;
        form.next = propsW.sequenceEdit.next;
        form.advise = propsW.sequenceEdit.advise;
        form.num_request = propsW.sequenceEdit.num_request;
        form.num_authorization = propsW.sequenceEdit.num_authorization;
        form.date_request = propsW.sequenceEdit.date_request
            ? propsW.sequenceEdit.date_request
            : null;
        form.date_expire = propsW.sequenceEdit.date_expire
            ? propsW.sequenceEdit.date_expire
            : null;
    }
});


/*
Formulario
 */
const form = useForm({
    id:0,
    code:"",
    type:"B01",
    from: 1,
    next: 0,
    to: 0,
    advise:0,
    num_request:"",
    num_authorization:"",
    date_request: null as any,
    date_expire: null as any,
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
            state.first_error = "";


        },
        onFinish: () => {
            Object.entries(form.errors).forEach(([key, value]) => {
                if (!state.first_error)
                {
                    state.first_error = `El Campo ${key}, Mensaje: ${value}`;
                }
            })
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
            <TabLink
                :href="route('setting.index')">
                Ajustes
            </TabLink>
            <TabLink
                :active="true"
                :href="route('sequence.create')">
                Correlativos
            </TabLink>
            <TabLink
                :href="route('aco.index')">
                Cuentas
            </TabLink>
            <TabLink
                :href="route('wh.index')">
                Almacen
            </TabLink>
        </template>

        <!--        Conteneido de la ventana-->
        <div class="fondo p-5 rounded-md max-w-[1180px] mx-auto grid grid-cols-3 gap-3" >


            <div class="col-span-2">
                <!--            Tabla de las secuencias registrada-->
                <table class="w-full styleTable" >
                    <caption class=" text-2xl font-bold text-gray-50">
                        Secuencias
                    </caption>

                    <thead class="border-b-2  text-left">
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



            <form
                @submit.prevent="submit"  class=" ">
<!--                Generales-->
                <fieldset
                    class="field rounded-md p-3">
                    <legend class="px-3">
                        Secuencias Correlativos
                    </legend>

<!--                    Tipo de sequencia-->
                    <div>
                        <InputLabel
                            for="type"
                            value="Tipo"/>
                        <select
                            v-model="form.type"
                            class="inputGeneral py-1 w-full">
                            <option
                                v-for="(item, index) in propsW.sequenceType"
                                :key="index"
                                :value="item">
                                {{ item }}
                            </option>
                        </select>
                    </div>

<!--                    From-->
                    <div>
                        <InputLabel
                            for="from"
                            value="Desde"/>
                        <TextInput
                            class="w-full"
                            v-model="form.from"
                            type="number"/>
                    </div>

                    <!--Hasta-->
                    <div>
                        <InputLabel
                            for="to"
                            value="Hasta"/>
                        <TextInput
                            class="w-full"
                            v-model="form.to"
                            type="number"/>
                    </div>

                    <!--Aviso-->
                    <div>
                        <InputLabel
                            for="advise"
                            value="Aviso"/>
                        <TextInput
                            class="w-full"
                            v-model="form.advise"
                            type="number"/>
                    </div>

                </fieldset>
<!--                Informacion de numero-->
                <fieldset
                    class="field rounded-md p-3">
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
                    </div>
                </fieldset>


<!--                Fechas-->
                <fieldset class="field rounded-md p-3" >
                    <legend class="px-3">
                        Fechas
                    </legend>
                    <!--                    Fecha de solicitud-->
                    <div>
                        <InputLabel for="date_request" value="Fecha de Solicitud"/>
                        <TextInput
                            v-model="form.date_request"
                            class="w-full"
                            type="date"/>
                    </div>


                    <!--                    Fecha Expira-->
                    <div>
                        <InputLabel for="date_expire" value="Fecha de Vencimiento"/>
                        <TextInput
                            v-model="form.date_expire"
                            class="w-full"
                            type="date"/>
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
        <ErrorComponent
            :message="state.first_error"/>

    </AppLayout>

</template>

