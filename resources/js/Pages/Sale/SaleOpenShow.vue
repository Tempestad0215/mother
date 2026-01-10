<script setup lang="ts">
import {saleDataI} from "@/Interfaces/SaleInterface";
import {getMoney} from "@/Global/Helpers";
import Pagination from "@components/Pagination.vue";
import FormSearch from "@components/FormSearch.vue";
import {useForm} from "@inertiajs/vue3";
import {PaginationI} from "@/Interfaces/GlobalInterface";

/**
 * Propiedades de la ventana
 */
const props = defineProps<{
    saleOpen: PaginationI<saleDataI>
}>();

/**
 * Formulario
 */

const form = useForm({
    search: "",
    per_page: 15,
    field: "name"
})


/**
 * Emitir los datos al padre
 */
defineEmits<{
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
                    holder="Buscar"
                    v-model:select-value="form.per_page"
                    v-model="form.search"
                />
            </form>
        </div>


        <table class="w-full mt-5 styleTable">
            <thead class="text-left">
                <tr>
                    <th>Cliente</th>
                    <th>Itbis</th>
                    <th>Total</th>
                    <th>Act</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class=""
                    v-for="(item, index) in props.saleOpen?.data"  :key="index">
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
            :field="form.field"
            :search="form.search"
            :per-page="form.per_page"
            :current-page="props.saleOpen?.current_page"
            :total-page="props.saleOpen?.to"
            :next="props.saleOpen?.next_page_url"
            :prev="props.saleOpen?.prev_page_url"/>
    </div>
</template>

<style scoped>

</style>
