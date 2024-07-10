<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import HeaderBox from '@components/HeaderBox.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { productI } from '@/Interfaces/Product';
import FormSearch from '@components/FormSearch.vue';
import ContentBox from '@components/ContentBox.vue';
import { PropType } from 'vue';
import Pagination from '@components/Pagination.vue';
import NavLink from '@components/NavLink.vue';


const props = defineProps({
    products: {
        type: Object as PropType<productI>,
        required: true
    }
})


const form = useForm({
    search:''
})




// Funciones
const submit = () => {
    form.get(route('product.show'),{
        preserveScroll: true,
        preserveState: true
    });
}

const edit = (id:number) => {
    router.get(route('product.edit', id));
}

</script>


<template>
    <Head title="Mostrar" />
    <AppLayout>
        <template #header >
            <HeaderBox>
                <h2>
                    Mostrar productos
                </h2>
                <template #link>
                    <NavLink
                        :href="route('product.create')" >
                        Registrar
                    </NavLink>
                    <NavLink
                        :active="true"
                        :href="route('product.show')" >
                        Mostrar
                    </NavLink>
                    <NavLink
                        :href="route('product.create')" >
                        Entrada
                    </NavLink>


                </template>
            </HeaderBox>
        </template>

        <div>
            <ContentBox class="md:max-w-full mx-5" >
                <div>
                    <form @submit.prevent="submit"  >
                        <FormSearch
                            v-model="form.search" />
                    </form>
                </div>

                <table class=" table-auto w-full">
                    <thead>
                        <tr class=" text-left">
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Uni.</th>
                            <th>Stock</th>
                            <th class=" max-w-[200px]">Atc</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in props.products.data" :key="index">
                            <td>{{item.id}}</td>
                            <td>{{item.name}}</td>
                            <td>{{item.unit}}</td>
                            <td>{{item.stock}}</td>
                            <td class=" w-[200px] max-w-[200px] truncate  text-xl px-3">
                                <div class=" flex justify-between">
                                    <!-- Entrada de producto -->
                                    <i class="icon-efect fa-solid fa-arrows-down-to-line"></i>

                                    <!-- Ver los productos -->
                                    <i class="icon-efect  fa-solid fa-eye"></i>

                                    <!-- Editar -->
                                    <i
                                        @click="edit(item.id)"
                                        class="icon-efect fa-solid fa-pen-to-square"></i>

                                    <!-- Eliminar -->
                                    <i class="icon-efect fa-solid fa-trash"></i>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination
                    :current-page="props.products.current_page"
                    :total-page="props.products.to"
                    :next="props.products.next_page_url ? props.products.next_page_url : ''"
                    :prev="props.products.prev_page_url ? props.products.prev_page_url : '' "/>

            </ContentBox>

        </div>

    </AppLayout>

</template>
