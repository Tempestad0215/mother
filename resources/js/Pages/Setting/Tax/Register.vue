<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {DataTable, Column, Button, Dialog, FloatLabel, InputText, useToast, useConfirm,Breadcrumb, InputNumber} from "primevue";
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import {itemsSettings} from "@/Helpers/SettingHelpers";
import {TaxInterfaceI} from "@/Interfaces/TaxInterface";


const confirm = useConfirm()
const toast = useToast();

defineProps<{
    taxes: TaxInterfaceI[]
}>()

const createTax = ref(false)
const isUpdate = ref(false)

const form = useForm({
    id: 0,
    name: "",
    description: "",
    rate: 0
})


const formReset = () => {
    form.reset('name','description','id','rate')
}

const submit = () => {
    if (isUpdate.value) {
        form.put(route('tax.update', {tax: form.id}),{
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
        form.post(route('tax.store'),{
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

const editData = (data:TaxInterfaceI) => {
    form.id = data.id!!;
    form.name = data.name;
    form.description = data.description ?? "";
    form.rate = Number(data.rate) ?? 0
    createTax.value = true;
    isUpdate.value = true;
}

const deleteData = (data:TaxInterfaceI, event: Event) => {
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
            :value="taxes">
            <template #header>
                <div>
                    <Breadcrumb :model="itemsSettings" />
                </div>
                <div class="text-right">

                    <Button @click="createTax = true" icon="pi pi-plus" label="Crear Unidad" />
                </div>
            </template>
            <Column field="name" header="Nombre" />
            <Column field="description" header="Descripcion" />
            <Column :field="(data:TaxInterfaceI) => `${data.rate} %`" header="Tasa" />
            <Column class="w-40" header="Act">
                <template #body="{data}:{data:TaxInterfaceI}">
                    <div class="space-x-3">
                        <Button @click="editData(data)" class="pt-1 h-8" title="Editar" icon="pi pi-file-edit" />
                        <Button @click="deleteData(data, $event)" class="pt-1 h-8" title="Elimianr" severity="danger"  icon="pi pi-trash" />
                    </div>
                </template>
            </Column>
        </DataTable>
        <Dialog
            modal
            v-model:visible="createTax"
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
                <FloatLabel variant="on" class="mt-5">
                    <InputNumber suffix="%" v-model="form.rate" :min="0" :max="100" class="w-full" id="name"  />
                    <label  for="name">Descripcion</label>
                </FloatLabel>
                <div class="mt-5 text-right">
                    <Button type="submit" icon="pi pi-send" label="Registrar" />
                </div>
            </form>

        </Dialog>
    </AppLayout>
</template>
