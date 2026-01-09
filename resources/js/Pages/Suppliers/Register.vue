<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {supplierI} from "@/Interfaces/SupplierInterface";
import {useRoute} from "ziggy-js";
import {
    DataTable,
    Column,
    InputText,
    Button,
    InputGroupAddon,
    InputGroup,
    Dialog,
    useConfirm,
    useToast
} from "primevue";
import {ref} from "vue";
import FRegister from "@/Pages/Suppliers/FRegister.vue";
import {paginationI, paymentTypeEnumI} from "@/Interfaces/GlobalInterface";
import Pagination from "@components/Pagination.vue";
import {getSearchTable} from "@/Global/SearchTable";
import {router} from "@inertiajs/vue3";


const route = useRoute();
const confirm = useConfirm();
const toast = useToast();
// Propiedades
const propsW = defineProps<{
    suppliers: paginationI<supplierI>
    update?: boolean
    paymentTypes: paymentTypeEnumI
}>();

const searchValue = ref("")
const createSupplier = ref(false)
const selectedSupplier = ref<supplierI | null>(null)
const isUpdate = ref(false)


const searchData = () => {
    getSearchTable(route('supplier.create',{search: searchValue.value, per_page: propsW.suppliers.per_page}))
}

const editData = (data:supplierI) => {
    selectedSupplier.value = data;
    createSupplier.value = true;
    isUpdate.value = true;
}

const deleteData = (data:supplierI, event:Event) => {
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
            router.delete(route('supplier.destroy', {supplier: data.id}),{
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
</script>

<template>
    <AppLayout>
        <DataTable
            :rows="propsW.suppliers.per_page"
            paginator
            :value="propsW.suppliers.data">
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
                        @click="createSupplier = true">
                        Crear Suplido
                    </Button>
                </div>

            </template>
            <Column field="code" header="Codigo" />
            <Column field="company_name" header="Nombre Comercial " />
            <Column field="contact" header="Contacto" />
            <Column field="phone" header="Telefono" />
            <Column field="email" header="Correo" />
            <Column header="Act">
                <template #body="{data}:{data:supplierI}">
                    <div class="space-x-2">
                        <Button @click="editData(data)" class="pt-1 h-8"  title="Editar" icon="pi pi-file-edit" />
                        <Button @click="deleteData(data, $event)" class="pt-1 h-8"  title="Eliminar" severity="danger" icon="pi pi-trash" />

                    </div>
                </template>
            </Column>
            <template #paginatorcontainer>
                <Pagination
                    :search="searchValue"
                    :pag="propsW.suppliers"/>
            </template>
        </DataTable>
        <Dialog
            modal
            @hide="selectedSupplier = null"
            v-model:visible="createSupplier" >
            <FRegister
                :paymentTypes="propsW.paymentTypes"
                :update="isUpdate"
                :supplierEdit="selectedSupplier"/>
        </Dialog>
    </AppLayout>
</template>
