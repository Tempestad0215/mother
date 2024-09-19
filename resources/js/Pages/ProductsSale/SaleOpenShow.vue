<script setup lang="ts">

import {PropType} from "vue";
import {saleDataI, saleDataPaginationI} from "@/Interfaces/Sale";
import {getMoney} from "@/Global/Helpers";
import Pagination from "@components/Pagination.vue";
import FormSearch from "@components/FormSearch.vue";
import {useForm} from "@inertiajs/vue3";

/**
 * Propiedades de la ventana
 */
const props = defineProps({
    saleOpen: {
        type: Object as PropType<saleDataPaginationI>,
        required: true
    }
});

/**
 * Formulario
 */

const form = useForm({
    search: ""
})


/**
 * Emitir los datos al padre
 */

const emit = defineEmits<{
    (e: 'senData', item:saleDataI):void
}>()


/**
 * Funciones
 */

//Buscar los datos
const submit = () => {
    form.get(`?search=${form.search}`,{
        preserveScroll: true,
        preserveState: true
    });
}


</script>

<template>
    <div class="">
        <div class="flex items-center justify-between">
            <form @submit.prevent="submit()">
                <FormSearch
                    v-model="form.search"
                />
            </form>

            <h3 class="text-3xl font-bold mt-5">
                Cuentas Abiertas
            </h3>
        </div>


        <table class="w-full mt-5">
            <thead class="text-left">
                <tr>
                    <th>Code</th>
                    <th>Cliente</th>
                    <th>Itbis</th>
                    <th>Total</th>
                    <th>Act</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="odd:bg-gray-400"
                    v-for="(item, index) in props.saleOpen?.data"  :key="index">
                    <td>
                        {{item.code}}
                    </td>
                    <td>
                        {{item.client_name ? item.client_name : "N/A"}}
                    </td>
                    <td>
                        {{ getMoney(item.tax)}}
                    </td>
                    <td>
                        {{ getMoney(item.amount)}}
                    </td>
                    <td>
                        <i
                            @click="$emit('senData', item)"
                            class="icon-efect fa-solid fa-circle-check"></i>
                    </td>
                </tr>
            </tbody>
        </table>

        <Pagination
            :current-page="props.saleOpen?.current_page"
            :total-page="props.saleOpen?.to"
            :next="props.saleOpen?.next_page_url"
            :prev="props.saleOpen?.prev_page_url"/>
    </div>
</template>

<style scoped>

</style>
