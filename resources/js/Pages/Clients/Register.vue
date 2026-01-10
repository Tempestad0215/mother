<script setup lang="ts">
import {clientBaseI, clientDocumentI, clientPriceI, clientTypeI} from "@/Interfaces/ClientInterface";
import AppLayout from "@layout/AppLayout.vue";
import {DataTable, Column, Button, Dialog, useConfirm, useToast, InputText, InputGroup, InputGroupAddon} from "primevue";
import {onMounted, ref} from "vue";
import {PaginationI} from "@/Interfaces/GlobalInterface";
import FRegister from "@/Pages/Clients/FRegister.vue";
import {router} from "@inertiajs/vue3";
import {useRoute} from "ziggy-js";
import Pagination from "@components/Pagination.vue";
import {getSearchTable} from "@/Global/SearchTable";

/**
 * propsW de la vantana
 */
const route = useRoute();
const confirm = useConfirm();
const toast = useToast();

const createClient = ref<boolean>(false);
const selectedClient = ref<clientBaseI | null>(null);
const isUpdate = ref(false);
const searchValue = ref<string>("");
const propsW = defineProps<{
    typeRNC: string[]
    clientData: PaginationI<clientBaseI>
    clientType: clientTypeI
    clientPrice: clientPriceI
    clientDocument: clientDocumentI
}>();


onMounted(()=>{
    window.addEventListener("keydown", handleKeyDown);
})

const handleKeyDown = (event: KeyboardEvent) => {
    if(event.ctrlKey && event.key.toLowerCase() === "k"){
        event.preventDefault();
        createClient.value = true;
    }
}

const editData = (data:clientBaseI) => {
    selectedClient.value = data;
    createClient.value = true;
    isUpdate.value = true;
}

const deleteData = (data:clientBaseI, event: Event) => {
    confirm.require({
        target: event.currentTarget as HTMLElement,
        message: "Desea eliminar este registro, los cambios son irreversible",
        rejectProps:{
            label: "Cancelar",
            severity: "secondary",
            outlined: true

        },
        acceptProps:{
            label: "Eliminar",

        },
        accept: () => {
            router.delete(route('un.destroy', {client: data.id}),{
                onSuccess: () => {
                    toast.add({
                        severity: "success",
                        summary: "Eliminado ",
                        detail: "El Registro Fue Eliminado Correctamente.",
                        life: 3000
                    })
                }
            })
        }

    })
}

const searchData = ()=> {
    getSearchTable(route("client.create", {search: searchValue.value, per_page: propsW.clientData.per_page, page: propsW.clientData.current_page}))
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
