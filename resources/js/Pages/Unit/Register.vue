<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {DataTable, Column, Button, Dialog, FloatLabel, InputText, useToast, useConfirm,Breadcrumb} from "primevue";
import type {UnitInterfaceI} from "@/Interfaces/UnitInterface";
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import {itemsSettings} from "@/Helpers/SettingHelpers";


const confirm = useConfirm()
const toast = useToast();

const propsW = defineProps<{
    units: UnitInterfaceI[]
}>()

const createUnit = ref(false)
const selectedUnit = ref<UnitInterfaceI | null>(null)
const isUpdate = ref(false)

const form = useForm({
    id: 0,
    name: "",
    description: "",
})


const formReset = () => {
    form.reset('name','description','id')
}

const submit = () => {
    if (isUpdate.value) {
        form.put(route('unit.update', {id: form.id}),{
            onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: "Actualizado",
                    life: 3000,
                })
                formReset()
            },
            onError: (err) => {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: `Error en esta peticion. Detalle : ${Object.values(err)[0]}`,
                    life: 5000,
                })
            }
        })
    }else{
        form.post(route('unit.store'),{
            onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: "Registrado",
                    life: 3000,
                })
                formReset()
            },
            onError: (err) => {
                toast.add({
                    severity: "error",
                    summary: "Error",
                    detail: `Error en esta peticion. Detalle : ${Object.values(err)[0]}`,
                    life: 5000,
                })
            }
        })
    }
}

const editData = (data:UnitInterfaceI) => {
    form.id = data.id!!;
    form.name = data.name;
    form.description = data.description;
    createUnit.value = true;
    isUpdate.value = true;
}

const deleteData = (data:UnitInterfaceI, event: Event) => {
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
            router.delete(route('unit.destroy', {unit: data.id}),{
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
            :value="units">
            <template #header>
                <div>
                    <Breadcrumb :model="itemsSettings" />
                </div>
                <div class="text-right">

                    <Button @click="createUnit = true"  icon="pi pi-plus"  label="Crear Unidad" />
                </div>
            </template>
            <Column field="name" header="Nombre" />
            <Column field="description" header="Descripcion" />
            <Column class="w-40" header="Act">
                <template #body="{data}:{data:UnitInterfaceI}">
                    <div class="space-x-3">
                        <Button @click="editData(data)" class="pt-1 h-8" title="Editar" icon="pi pi-file-edit" />
                        <Button @click="deleteData(data, $event)" class="pt-1 h-8" title="Elimianr" severity="danger"  icon="pi pi-trash" />
                    </div>
                </template>
            </Column>
        </DataTable>
        <Dialog
            modal
            v-model:visible="createUnit"
            header="Registro de Unidad">
            <form class="w-100" @submit.prevent="submit">
                <FloatLabel class="mt-5" variant="on">
                    <InputText v-model="form.name" class="w-full" id="name"  />
                    <label for="name">Nombre</label>
                </FloatLabel>
                <FloatLabel variant="on" class="mt-5">
                    <InputText v-model="form.description" class="w-full" id="name"  />
                    <label  for="name">Descripcion</label>
                </FloatLabel>
                <div class="mt-5 text-right">
                    <Button type="submit" icon="pi pi-send" label="Registrar" />
                </div>
            </form>

        </Dialog>
    </AppLayout>
</template>
