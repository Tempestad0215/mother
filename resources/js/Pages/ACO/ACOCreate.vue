<script setup lang="ts">
import {Head, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import {ref} from "vue";
import InputError from "@components/InputError.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import FormSearch from "@components/FormSearch.vue";
import {successHttp} from "@/Global/Alert";
import {acoBaseI, acoTableI} from "@/Interfaces/ACO";
import Swal from "sweetalert2";


/*
Propiedades
 */
const propsW = defineProps<{
    aco: acoTableI
}>()

/**
 * Datos de la ventanan
 */
const type = ref(['ACTIVO','PASIVO','INGRESO','GASTO','CAPITAL']);

/**
 * Formularios
 */
const form = useForm({
    uuid:"",
    code:"",
    name:"",
    type:"",
    update: false,
});

const formSearch = useForm({
    search:"",
    perPage: 30
});



/*
funciones
 */
const search = () => {

}

/**
 * Enviar los datos
 */
const submit = () => {
    form.post(route('aco.store'),{
        onSuccess: () => {
            successHttp('Datos Registrado Correctamente');
            form.reset();
        }
    });
}

/**
 *
 * @param item
 */
const edit = (item:acoBaseI) => {
    form.uuid = item.uuid;
    form.code = item.code;
    form.name = item.name;
    form.type = item.type;
    form.update = true;
}

const destroy = (item:acoBaseI) => {
    Swal.fire({
        title: "Desea Eliminar?",
        text: "Los Cambios Realizados Son Irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Eliminar!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('aco.destroy',{aco: item.uuid}),{
                onSuccess: () => {
                    successHttp('Datos Eliminado Correctamente');
                }
            });
        }
    });
}



</script>

<template>
    <Head title="Cuentas Contables" />
    <AppLayout>
        <template #header>

        </template>

<!--        Contenido de la vantana-->
        <div class="max-w-[70rem] mx-auto">
            <form
                @submit.prevent="submit"
                class="grid grid-cols-3 gap-3 bg-blue-300 p-5 rounded-md">
                <h3
                    class="title text-center col-span-full">
                    Cuentas Contables
                </h3>
<!--                codigo-->
                <div>
                    <InputLabel
                        for="code"
                        value="Codigo"/>
                    <TextInput
                        class="w-full"
                        v-model="form.code"
                    />
                    <InputError :message="form.errors?.code" />
                </div>
                <!--                codigo-->
                <div>
                    <InputLabel
                        for="name"
                        value="Nombre"/>
                    <TextInput
                        class="w-full"
                        v-model="form.name"
                    />
                    <InputError :message="form.errors?.name" />
                </div>
                <!--                codigo-->
                <div>
                    <InputLabel
                        for="type"
                        value="Nombre"/>
                    <select
                        class="inputGeneral py-1 w-full"
                        v-model="form.type">
                        <option value=""> -- Seleccione --</option>
                        <option
                            v-for="(item, index) in type"
                            :key="index"
                            :value="item">
                            {{item}}
                        </option>
                    </select>
                    <InputError :message="form.errors?.type" />
                </div>

<!--                Botones para enviar-->
                <div class="col-end-4 text-right">
                    <PrimaryButton>
                        Registar
                    </PrimaryButton>
                </div>
            </form>


<!--            Cuentas registrada -->
            <div class="mt-3 p-5 bg-blue-300 rounded-md">
                <form @submit.prevent="search">
                    <FormSearch
                        v-model:search="formSearch.search"
                        v-model:per-page="formSearch.perPage"
                        />
                </form>
                <table
                    class="styleTable table-auto w-full mt-3">
                    <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, index) in propsW.aco.data"
                            :key="index">
                            <td>{{item.code}}</td>
                            <td>{{item.name}}</td>
                            <td>{{item.type}}</td>
                            <td class="space-x-3">
                                <i
                                    @click="edit(item)"
                                    class=" icon-efect fa-solid fa-file-pen"></i>
                                <i
                                    @click="destroy(item)"
                                    class=" icon-efect fa-solid fa-trash-can"></i>
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

        </div>
    </AppLayout>
</template>

