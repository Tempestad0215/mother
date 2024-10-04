<script setup lang="ts">
import {Head, router} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import {productDataI} from "@/Interfaces/Product";
import {getMoney} from "@/Global/Helpers";



/*
Propiedades
 */
const propsW = defineProps<{
    products: productDataI[],
    amount: number
}>();



/*
Funciones
 */

/**
 * Retornar hacia atras
 */
const back = ():void => {
    router.get(route('report.index'))
}


</script>

<template>
    <Head title="Invenatario Bajo"/>
    <AppLayout>
        <template #header>
        </template>

        <div class="bg-gray-200 p-5 rounded-md">
            <i
                @click="back()"
                class=" icon-efect float-left fa-solid fa-circle-arrow-left">
            </i>
            <h3 class="text-3xl font-bold text-center mb-5">
                Producto < 10 Disponible
            </h3>
            <table class="w-full mt-5">
                <thead>
                    <tr class="border-b-2 border-gray-800 p-5 text-left">
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Disponible</th>
                        <th>Reservado</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="odd:bg-gray-400"
                        v-for="(item, index) in propsW.products" :key="index">
                        <td>{{item.code}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.stock}}</td>
                        <td>{{item.reserved}}</td>
                        <td>{{ getMoney(item.price)}}</td>
                    </tr>
                </tbody>
            </table>
            <div class=" text-right pt-5 border-t-2 border-gray-800">
                Total : {{ getMoney(propsW.amount)}}
            </div>

        </div>
    </AppLayout>

</template>

