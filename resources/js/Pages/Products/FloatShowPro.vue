<script setup lang="ts">

import FormSearch from "@components/FormSearch.vue";
import Pagination from "@components/Pagination.vue";
import {computed, PropType} from "vue";
import {productDataI, productI} from "@/Interfaces/Product";
import {router, useForm, usePage} from "@inertiajs/vue3";

const page = usePage();

const props = defineProps({
    products: {
        type: Object as PropType<productI>,
        required: true
    }
});

const emit = defineEmits(['select']);


const form = useForm({
    search:''
});

// Funciones
const submit = () => {
    form.get( ``, {
        preserveScroll: true,
        preserveState: true
    });
}

const edit = (id:number) => {
    router.get(route('product.edit', id));
}

const selectData = (item:productDataI) => {
    emit('select',item);
}



//PRopiedades computada
const isSale = computed(()=>{
   if(page.component === 'Products/Sale')
   {
       return ' text-xl'
   }else{
       return ' w-[200px] max-w-[200px] truncate  text-xl px-3';
   }
});

</script>

<template>
    <div class="bg-gray-200 mt-5 rounded-md p-5">
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
                <th>Disp.</th>
                <th>Precio</th>
                <th class=" max-w-[200px]">Atc</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in props.products.data" :key="index">
                <td>{{item.id}}</td>
                <td>{{item.name}}</td>
                <td>{{item.unit}}</td>
                <td>{{item.stock}}</td>
                <td>{{item.price}}</td>
                <td
                    class=""
                    :class="isSale">
                    <div
                        class=" flex justify-between">
                        <!-- Entrada de producto -->
                        <i
                            @click="selectData(item)"
                            class=" icon-efect fa-solid fa-circle-check"></i>

                        <i
                            v-if="page.component !== 'Products/Sale' "
                            class="icon-efect fa-solid fa-arrows-down-to-line"></i>

                        <!-- Ver los productos -->
                        <i
                            v-if="page.component !== 'Products/Sale' "
                            class="icon-efect  fa-solid fa-eye"></i>

                        <!-- Editar -->
                        <i
                            v-if="page.component !== 'Products/Sale' "
                            @click="edit(item.id)"
                            class="icon-efect fa-solid fa-pen-to-square"></i>

                        <!-- Eliminar -->
                        <i
                            v-if="page.component !== 'Products/Sale' "
                            class="icon-efect fa-solid fa-trash"></i>
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
    </div>
</template>

