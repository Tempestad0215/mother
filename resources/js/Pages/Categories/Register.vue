<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {PaginationI} from "@/Interfaces/GlobalInterface";
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {
    DataTable,
    Column,
    Button,
    InputGroup,
    InputGroupAddon,
    InputText,
    Dialog,
    useConfirm,
    useToast
} from "primevue";
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import {useRoute} from "ziggy-js";
import FRegister from "@/Pages/Categories/FRegister.vue";
import Pagination from "@components/Pagination.vue";
import {getSearchTable} from "@/Global/SearchTable";



const route = useRoute();
const confirm = useConfirm();
const toast = useToast();
const propsW = defineProps<{
    categories: PaginationI<categoryBaseI>,
    categoryEdit?: categoryBaseI,
}>();


const searchValue = ref("")
const createCategory = ref(false)
const categorySelected = ref<categoryBaseI | null>(null);
const isUpdate = ref<boolean>(false);
const form = useForm({
    name: "",
    description: "",
    status: true
})

const searchData = () => {
    getSearchTable(route("category.create",{search: searchValue.value, per_page: propsW.categories.per_page, page: propsW.categories.current_page}));
}
const submit = () =>{
    console.log("submit")
}

const editData = (data:categoryBaseI) =>  {
    categorySelected.value = data;
    createCategory.value = true;
    isUpdate.value = true;

}

const deleteData = (data:categoryBaseI, event:Event) =>  {
    confirm.require({
        target: event.currentTarget as HTMLElement,
        message: "Desea Eliminar Este Registro",
        rejectProps:{
            icon: 'pi pi-cancel',
            label: 'Cancelar',
            outlined: true,
        },
        acceptProps: {
            icon: "pi pi-check",
            severity: "danger",
            label: "Eliminar"
        },
        accept: () => {
            router.delete(route('category.destroy', {category: data.id}),{
                onSuccess: () => {
                    toast.add({
                        severity: "success",
                        summary: "Registro Eliminado",
                        life: 3000
                    })
                },
                onError: (err) => {
                    toast.add({
                        severity: "error",
                        summary: "Error",
                        detail: `Error al intentar eliminar los datos. Detalle : ${Object.values(err)[0]}`,
                        life: 500
                    })
                }
            })
        }

    })
}

const resetForm = () => {
    categorySelected.value = null;
    isUpdate.value = true;
}
</script>

<template>
    <AppLayout>
        <DataTable
            paginator
            :rows="propsW.categories.per_page ?? 0"
            :loading="!propsW.categories.data"
            :value="propsW.categories.data">
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
                        @click="createCategory = true">
                        Crear Cliente
                    </Button>
                </div>
            </template>
            <Column field="code" header="Código"  />
            <Column field="name" header="Nombre"  />
            <Column field="description" header="Description"  />
            <Column  header="Act"  >
                <template #body="{data}:{data:categoryBaseI}">
                    <div class="space-x-3">
                        <Button @click="editData(data)" class="pt-1 h-8"  title="Editar" icon="pi pi-file-edit" />
                        <Button @click="deleteData(data, $event)" class="pt-1 h-8"  title="Eliminar" severity="danger" icon="pi pi-trash" />

                    </div>
                </template>
            </Column>
            <template #paginatorcontainer>
                <Pagination
                    :search="searchValue"
                    :pag="propsW.categories"  />
            </template>
        </DataTable>
        <Dialog
            @hide="resetForm"
            v-model:visible="createCategory"
            modal>
            <FRegister
                :update="isUpdate"
                :categoryEdit="categorySelected"/>

        </Dialog>

    </AppLayout>
</template>

<style scoped>

</style>
