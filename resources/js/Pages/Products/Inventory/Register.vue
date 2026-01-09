<script lang="ts" setup>
import {productBaseI, productI} from '@/Interfaces/ProductInterface';
import TabLink from '@components/TabLink.vue';
import {Head} from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import {ref, watch} from 'vue';
import {entryBaseI, entryProductI} from "@/Interfaces/EntryTransInterface";
import axios from "axios";
import {paginationI} from "@/Interfaces/GlobalInterface";
import FRegister from "@/Pages/Products/Inventory/FRegister.vue";
import FShowEntrie from "@/Pages/Products/Inventory/FShow.vue";
import {useRoute} from "ziggy-js";
import {DataTable, Column, InputText, Button, Dialog, InputGroupAddon, InputGroup} from "primevue";
import {clientBaseI} from "@/Interfaces/ClientInterface";
import Pagination from "@components/Pagination.vue";

const route = useRoute();
// Propiedades
const propsW = defineProps<{
    products: productBaseI[],
    productTable: productI,
    entry_edit?: entryBaseI,
    entries: paginationI<entryProductI>
}>();
//datos de la ventana
const productName = ref<string>();
const products = ref<productBaseI[] | null>(null);
const editData = ref<entryProductI | undefined>(undefined);

/**
 * Evento watch
 */

/**
 * Pra buscar los datos por cada cambio
 */
watch(productName, (newValue) => {
    if (newValue && newValue?.length > 3) {
        axios.get(route('product.get.json',{search: productName.value}))
            .then(res => {
                products.value = res.data;
            })
            .catch(() => {

            });
    }
});


// Editar los datos
const edit = (item:entryProductI) => {
    editData.value = {...item};
}



</script>

<template>
    <AppLayout>
        <DataTable
            paginator
            :rows="propsW.clientData.per_page ?? 0"
            :loading="!propsW.clientData.data"
            :value="propsW.clientData.data" >
            <template #header>
                <div class="flex justify-between items-center">
                    <form @submit.prevent="searchData">
                        <InputGroup class="max-w-60">
                            <InputText v-model="searchValue" placeholder="Buscar" type="search" />
                            <InputGroupAddon
                                @click="searchData">
                                <i  class="pi pi-search" ></i>
                            </InputGroupAddon>
                        </InputGroup>
                    </form>
                    <Button
                        class="h-8"
                        @click="createClient = true">
                        Crear Cliente
                    </Button>
                </div>

            </template>
            <Column field="code" header="Codigo"  />
            <Column field="name" header="Nombre"  />
            <Column field="rnc" header="RNC"  />
            <Column field="phone" header="Telefono"  />
            <Column field="email" header="Correo"  />
            <Column header="Act">
                <template #body="{data}:{data:clientBaseI}">
                    <div class="space-x-2">
                        <Button @click="editData(data)" class="pt-1 h-8"  title="Editar" icon="pi pi-file-edit" />
                        <Button @click="deleteData(data, $event)" class="pt-1 h-8"  title="Eliminar" severity="danger" icon="pi pi-trash" />

                    </div>
                </template>
            </Column>
            <template #paginatorcontainer>
                <Pagination
                    :search="searchValue"
                    :pag="propsW.clientData"
                />
            </template>
        </DataTable>
        <Dialog
            modal
            @hide="selectedClient = null"
            v-model:visible="createClient" >
            <FRegister
                :clientDocument="clientDocument"
                :clientPrice="clientPrice"
                :clientType="clientType"
                :typeRNC="typeRNC"
                :update="isUpdate"
                :client-edit="selectedClient"/>
        </Dialog>
    </AppLayout>
</template>
