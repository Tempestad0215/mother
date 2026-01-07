<script setup lang="ts">
import {Head, router} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import TabLink from "@components/TabLink.vue";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import ToggleButton from "@components/ToggleButton.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {currencyI} from "@/Interfaces/CurrencyInterface";
import Swal from "sweetalert2";
import {useRoute} from "ziggy-js";


const route = useRoute()
/*
Propiedades de la ventana
 */
const propW = defineProps<{
    currencies: currencyI[];
}>()


/*
Formulario
 */
const form = useForm({
    code: null,
    name: null,
    symbol: null,
    is_base: false,
    status: true
});




/*
Funcions
 */
/**
 * enviar los datos
 */
const submit = ()=>{
    form.post(route('currency.store'),{
        onSuccess: ()=>{
            form.reset();
        }
    });
}

/**
 * Eliminar la moneda
 */
const destroy = (item: currencyI) => {
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
            router.delete(route('currency.destroy',{currency: item.uuid}),{
                onSuccess: () => {

                }
            });
        }
    });
}

/**
 *
 */
const restore = (item: currencyI) => {
    Swal.fire({
        title: "Desea Activar?",
        text: "Los Cambios Realizados Son Irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, Activar!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(route('currency.restore',{currency: item.uuid}),{},{
                onSuccess: () => {

                }
            });
        }
    });
}
</script>

<template>
    <Head title="Monedas"/>
    <AppLayout>
        <template #header>
            <TabLink
                :href="route('setting.index')">
                Ajustes
            </TabLink>
            <TabLink
                :active="true"
                :href="route('currency.index')">
                Tasa Cambio
            </TabLink>
        </template>
        <div class="max-w-[70rem] mx-auto">
            <form
                @submit.prevent="submit"
                class="bg-blue-300 p-5 rounded-md max-w-[70rem] mx-auto">
                <h3 class="title text-center">
                    Monedas Disponible
                </h3>
                <fieldset
                    class="field grid grid-cols-4 gap-3">
                    <legend>Registro de Moneda</legend>
                    <!-- codigo -->
                    <div>
                        <InputLabel
                            for="code"
                            value="Codigo"/>
                        <TextInput
                            class="w-full"
                            v-model="form.code"
                            name="code"/>
                    </div>
                    <!-- Nombre -->
                    <div>
                        <InputLabel
                            for="name"
                            value="Nombre"/>
                        <TextInput
                            class="w-full"
                            v-model="form.name"
                            name="name"/>
                    </div>
                    <!-- Nombre -->
                    <div>
                        <InputLabel
                            for="symbol"
                            value="Simbolo"/>
                        <TextInput
                            class="w-full"
                            v-model="form.symbol"
                            name="symbol"/>
                    </div>
                    <!-- Por Defecto -->
                    <div class="flex gap-3">
                        <div>
                            <ToggleButton
                                v-model="form.is_base"
                                label="Predeterminado"
                                on-label="SI"
                                off-label="NO"/>
                        </div>
                        <!-- Por Defecto -->
                        <div>
                            <ToggleButton
                                v-model="form.status"
                                label="Estado"
                                on-label="SI"
                                off-label="NO"/>
                        </div>

                    </div>

                    <div class="col-span-full">
                        <ol
                            class="text-red-800"
                            v-for="item in form.errors">
                            <li>{{item}}</li>
                        </ol>
                    </div>

                </fieldset>
                <!-- Boton para enviar los daots -->
                <div class="mt-5 text-right">
                    <PrimaryButton>
                        Registrar
                    </PrimaryButton>
                </div>

            </form>

            <!-- Informacion de los registro de monedas -->
            <div
                class="bg-blue-300 mt-3 rounded-md mx-auto p-5">
                <table class="styleTable table-auto">
                    <thead>
                        <tr>
                            <th class="w-[20rem]" >Code</th>
                            <th class="w-[20rem]">Nombre</th>
                            <th class="w-[20rem]">Simbolo</th>
                            <th class="w-[10rem]">Act.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(item, index) in propW.currencies" :key="index">
                            <td>{{item.code}}</td>
                            <td>{{item.name}}</td>
                            <td>{{item.symbol}}</td>
                            <td
                                v-if="item.deleted_at == null"
                                class="space-x-3">
                                <i
                                    title="Editar"
                                    class="icon-efect fa-solid fa-file-pen"></i>
                                <i
                                    title="Eliminar"
                                    @click="destroy(item)"
                                    class="icon-efect fa-solid fa-trash"></i>
                            </td>
                            <td v-if="item.deleted_at !== null">
                                <i
                                    title="Activar"
                                    @click="restore(item)"
                                    class="icon-efect fa-solid fa-check-to-slot"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
