<script setup lang="ts">
import Pagination from "@components/Pagination.vue";
import {clientBaseI} from "@/Interfaces/ClientInterface";
import {router, useForm, usePage} from "@inertiajs/vue3";
import {PaginationI} from "@/Interfaces/GlobalInterface";
import {onMounted, ref} from "vue";
import {useRoute} from "ziggy-js";
import {Button, Column, DataTable, InputGroup, InputGroupAddon, InputText, Card, useToast, useConfirm} from "primevue";


const route = useRoute();
const toast = useToast();
const confirm = useConfirm();
/**
 * Datos de la ventana
 */
const page = usePage();

/**
 * Datos del back end
 */
const propsW = defineProps<{
    clientData: PaginationI<clientBaseI>;
}>();
const searchValue = ref("")
const showClient = defineModel<boolean>('showClient',{
    default: false
})
const clientSelected = defineModel<clientBaseI | null>('clientSelected',{
    default: null
})



/**
 * Para emitir los eventos
 */
const emit = defineEmits<{
    (e: 'getData', item:clientBaseI):void
}>();


/**
 * Formulario
 */
const form = useForm({
    search:"",
    perPage:30,
    field: "name",
});



// Al momento de cargar
onMounted(()=>{
   //  Obtener el campo del local storage
   form.field = localStorage.getItem('field') || 'name';
});



const searchData = ()=> {
    getSearchTable(route("client.create", {search: searchValue.value, per_page: propsW.clientData.per_page, page: propsW.clientData.current_page}))
}


const editData = (data:clientBaseI) => {
    clientSelected.value = data;
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


</script>

<template>
    <Card>
        <template #title>
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
                    @click="showClient = !showClient">
                    Crear Cliente
                </Button>
            </div>
        </template>
        <template #content>
            <DataTable
                paginator
                :rows="propsW.clientData.per_page ?? 0"
                :loading="!propsW.clientData.data"
                :value="propsW.clientData.data" >
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
        </template>
    </Card>

</template>
