<script setup lang="ts">
import {Head, router, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import InputError from "@components/InputError.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {successHttp} from "@/Global/Alert";
import Swal from "sweetalert2";
import TabLink from "@components/TabLink.vue";
import {WHbaseI} from "@/Interfaces/Warehouse";


/**
 * Datos de la ventanan
 */
// const type = ref(['ACTIVO','PASIVO','INGRESO','GASTO','CAPITAL']);

/**
 * Formularios
 */
const form = useForm({
    id:0,
    name:"",
    description:"",
    location:"",
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
        form.put(route('wh.update',{wh: form.id}),{
            onSuccess: () => {
                successHttp('Datos Actualizado Correctamente');
            }
        });
    }else{
        form.post(route('wh.store'),{
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
const edit = (item:WHbaseI) => {
    form.id = item.id;
    form.name = item.name;
    form.description = item.description;
    form.location = item.location;
    form.update = true;
}


/**
 *
 * @param item
 */
const destroy = (item:WHbaseI) => {
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
            router.delete(route('wh.destroy',{wh: item.id}),{
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




        </div>
    </AppLayout>
</template>
