<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import LinkHeader from "@components/LinkHeader.vue";
import FormSearch from "@components/FormSearch.vue";
import {salePaginationI} from "@/Interfaces/Sale";
import {getMoney} from "@/Global/Helpers";
import Pagination from "@components/Pagination.vue";

const props = defineProps<{
    sales: salePaginationI,
}>()

/**
 * Datos del formulario
 */
const form = useForm({
    search: ""
})

//Enviar los datos
const submit = () => {
    form.get('',{
        preserveScroll: true,
        preserveState: true
    });
}

</script>

<template>
    <Head title="Mostrar Ventas"/>
    <AppLayout>
        <template #header>
            <LinkHeader
                :href="route('product-sale.create')">
                Ventas
            </LinkHeader>

            <LinkHeader
                :active="true"
                :href="route('product-sale.show')">
                Mostrar
            </LinkHeader>
        </template>

        <div class="bg-gray-200 rounded-md p-5 mx-auto overflow-hidden">

            <form
                @submit.prevent="submit">
                <FormSearch
                    v-model="form.search"/>
            </form>
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Itbis</th>
                        <th>Sub Total</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(item,index) in props.sales.data" :key="index">
                        <td>{{item.id}}</td>
                        <td>{{item.client_name}}</td>
                        <td>{{ getMoney(item.tax)}}</td>
                        <td>{{getMoney(item.sub_total)}}</td>
                        <td>{{getMoney(item.amount)}}</td>
                    </tr>
                </tbody>
            </table>

            <Pagination
                :current-page="props.sales.current_page"
                :total-page="props.sales.to"
                :prev="props.sales.prev_page_url"
                :next="props.sales.next_page_url"/>
        </div>

    </AppLayout>


</template>
