<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {DataTable, Column, Button, Dialog, useToast, useConfirm,Breadcrumb} from "primevue";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import {itemsSettings} from "@/Helpers/SettingHelpers";
import {BranchInterfaceI} from "@/Interfaces/BranchInterface";
import FRegisterBranch from "@/Pages/Branch/FRegisterBranch.vue";


const confirm = useConfirm()
const toast = useToast();

defineProps<{
    branches: BranchInterfaceI[]
}>()

const createBranch = ref(false)
const selectedBranch = ref<BranchInterfaceI | null>(null)
const isUpdate = ref(false)


const editData = (data:BranchInterfaceI) => {
    selectedBranch.value = data
    createBranch.value = true;
    isUpdate.value = true;
}

const deleteData = (data:BranchInterfaceI, event: Event) => {
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
            router.delete(route('branch.destroy', {branch: data.id}),{
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
            :value="branches">
            <template #header>
                <div>
                    <Breadcrumb :model="itemsSettings" />
                </div>
                <div class="text-right">

                    <Button @click="createBranch = true" icon="pi pi-plus" label="Crear Unidad" />
                </div>
            </template>
            <Column field="name" header="Nombre" />
            <Column field="description" header="Descripcion" />
            <Column class="w-40" header="Act">
                <template #body="{data}:{data:BranchInterfaceI}">
                    <div class="space-x-3">
                        <Button @click="editData(data)" class="pt-1 h-8" title="Editar" icon="pi pi-file-edit" />
                        <Button @click="deleteData(data, $event)" class="pt-1 h-8" title="Elimianr" severity="danger"  icon="pi pi-trash" />
                    </div>
                </template>
            </Column>
        </DataTable>
        <Dialog
            modal
            v-model:visible="createBranch"
            header="Registro de Unidad">
            <FRegisterBranch
                :branch-edit="selectedBranch"
                :is-update="isUpdate"/>

        </Dialog>
    </AppLayout>
</template>
