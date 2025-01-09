<script setup lang="ts">
import {Head, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import {ref} from "vue";
import InputError from "@components/InputError.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {successHttp} from "@/Global/Alert";
import {acoBaseI} from "@/Interfaces/ACO";
import Swal from "sweetalert2";
import TabLink from "@components/TabLink.vue";


/*
Propiedades
 */
const propsW = defineProps<{
    warehouse: any
}>()

/**
 * Datos de la ventanan
 */
const type = ref(['ACTIVO','PASIVO','INGRESO','GASTO','CAPITAL']);

/**
 * Formularios
 */
const form = useForm({
    id:0,
    name:"",
    description:"",
    update: false,
});



/*
funciones
 */
/**
 * Enviar los datos
 */
const submit = () => {
    if (form.update) {
        form.put(route('aco.update',{aco: form.id}),{
            onSuccess: () => {
                successHttp('Datos Actualizado Correctamente');
            }
        });
    }else{
        form.post(route('aco.store'),{
            onSuccess: () => {
                successHttp('Datos Registrado Correctamente');
                form.reset();
            }
        });
    }



}

/**
 *
 * @param item
 */
const edit = (item:any) => {
    form.id = item.id;
    form.name = item.name;
    form.description = item;
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
            router.delete(route('aco.destroy',{aco: item.id}),{
                onSuccess: () => {
                    successHttp('Datos Eliminado Correctamente');
                }
            });
        }
    });
}



</script>

<template>
    <Head title="Almacenes" />
    <AppLayout>
        <template #header>
            <TabLink
                :href="route('setting.index')">
                Ajustes
            </TabLink>
            <TabLink
                :href="route('aco.index')">
                Cuentas
            </TabLink>
            <TabLink
                :active="true"
                :href="route('wh.index')">
                Almacen
            </TabLink>
        </template>

        <!--        Contenido de la vantana-->
        <div class="max-w-[70rem] mx-auto">
            <form
                @submit.prevent="submit"
                class="grid grid-cols-2 gap-3 bg-blue-300 p-5 rounded-md">
                <h3
                    class="title text-center col-span-full">
                    Almacenes
                </h3>
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
                        for="description"
                        value="Descripcion"/>
                    <TextInput
                        class="w-full"
                        v-model="form.description"
                    />
                    <InputError :message="form.errors?.description" />
                </div>
                <!--                Botones para enviar-->
                <div class="col-span-full text-right">
                    <PrimaryButton>
                        Registar
                    </PrimaryButton>
                </div>
            </form>


            <!--            Cuentas registrada -->
            <div class="mt-3 p-5 bg-blue-300 rounded-md">
                <h3 class="title text-center">
                    Listado de Almacenes
                </h3>
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
                        v-for="(item, index) in propsW.warehouse"
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

