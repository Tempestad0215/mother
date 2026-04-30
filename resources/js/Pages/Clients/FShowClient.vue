<script setup lang="ts">
import Pagination from "@components/Pagination.vue";
import {clientBaseI} from "@/Interfaces/ClientInterface";
import {router, useForm} from "@inertiajs/vue3";
import {PaginationI} from "@/Interfaces/GlobalInterface";
import {onMounted, ref} from "vue";
import {useRoute} from "ziggy-js";
import {Button, Card, Column, DataTable, InputGroup, InputGroupAddon, InputText, useConfirm, useToast} from "primevue";
import {getSearchTable} from "@/Global/SearchTable";
import {FilePenLine, Shredder, UserRoundPlus} from '@lucide/vue';


const route = useRoute();
const toast = useToast();
const confirm = useConfirm();


/**
 * Datos del back end
 */
const propsW = defineProps<{
    clientData: PaginationI<clientBaseI>;
    otherComponent?: boolean;
}>();
const searchValue = ref("")
const showClient = defineModel<boolean>('showClient',{
    default: false
})
const clientSelected = defineModel<clientBaseI | null>('clientSelected',{
    default: null
})
const updateClient = defineModel<boolean>('updateClient', {
    default: false
})



/**
 * Para emitir los eventos
 */
// const emit = defineEmits<{
//     (e: 'getData', item:clientBaseI):void
// }>();


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


// Para editar los datos
const editData = (data:clientBaseI) => {
    clientSelected.value = data;
    showClient.value = true;
    updateClient.value = true;
}

// Elimianr los datos
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
            severity: "warn",

        },
        accept: () => {
            router.delete(route('client.destroy', {client: data.uuid}),{
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

const showClientBox = () =>{
    showClient.value = true;
    updateClient.value = false;
    clientSelected.value = null;
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
                    v-if="!propsW.otherComponent"
                    class="h-8"
                    @click="showClientBox">
                    <template #icon>
                        <UserRoundPlus/>
                    </template>
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
                            <Button @click="editData(data)" class="pt-1 h-8"  title="Editar">
                                <FilePenLine/>
                            </Button>
                            <Button @click="deleteData(data, $event)" class="pt-1 h-8"  title="Eliminar" severity="danger" >
                                <Shredder />
                            </Button>

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
