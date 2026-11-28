<script setup lang="ts">
import TaxController from '@/actions/App/Http/Controllers/TaxController';
import { TaxBaseI } from '@/Interfaces/TaxInterface';
import { TaxColumn } from '@/Pages/Setting/Tax/TaxColumn';
import tax from '@/routes/tax';
import DataTableField from '@components/DataTableField.vue';
import InputError from '@components/InputError.vue';
import { Button } from '@components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@components/ui/card';
import { Input } from '@components/ui/input';
import { Label } from '@components/ui/label';
import {
    NumberField,
    NumberFieldContent,
    NumberFieldDecrement,
    NumberFieldIncrement,
    NumberFieldInput,
} from '@components/ui/number-field';
import { Separator } from '@components/ui/separator';
import { Form } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    taxes: TaxBaseI[];
}>();

const taxForEdit = ref<TaxBaseI | null>(null);
const isEditing = ref(false);

//
// const createTax = ref(false);
// const isUpdate = ref(false);
//
// const form = useForm({
//   uuid: '',
//   name: '',
//   description: '',
//   rate: 0,
// });
//
// const formReset = () => {
//   form.reset('name', 'description', 'uuid', 'rate');
// };
//
// const submit = () => {
//   if (isUpdate.value) {
//     form.put(route('tax.update', { tax: form.uuid }), {
//       onSuccess: () => {
//         toast.add({
//           severity: 'success',
//           summary: 'Actualizado',
//           life: 3000,
//         });
//         formReset();
//       },
//       onError: (err) => {
//         toast.add({
//           severity: 'error',
//           summary: 'Error',
//           detail: `Error en esta peticion. Detalle : ${Object.values(err)[0]}`,
//           life: 5000,
//         });
//       },
//     });
//   } else {
//     form.post(route('tax.store'), {
//       onSuccess: () => {
//         toast.add({
//           severity: 'success',
//           summary: 'Registrado',
//           life: 3000,
//         });
//         formReset();
//       },
//       onError: (err) => {
//         toast.add({
//           severity: 'error',
//           summary: 'Error',
//           detail: `Error en esta peticion. Detalle : ${Object.values(err)[0]}`,
//           life: 5000,
//         });
//       },
//     });
//   }
// };
//
const editData = (data: TaxBaseI) => {
    if (!data.uuid) return;

    taxForEdit.value = { ...data };
};

//
// const deleteData = (data: TaxBaseI, event: Event) => {
//   confirm.require({
//     target: event.currentTarget as HTMLElement,
//     message: 'Desea eliminar este registro, los cambios son irreversible',
//     rejectProps: {
//       label: 'Cancelar',
//       severity: 'secondary',
//       outlined: true,
//     },
//     acceptProps: {
//       label: 'Eliminar',
//     },
//     accept: () => {
//       router.delete(route('unit.destroy', { unit: data.uuid }), {
//         onSuccess: () => {
//           toast.add({
//             severity: 'success',
//             summary: 'Eliminado ',
//             detail: 'El Registro Fue Eliminado Correctamente.',
//             life: 3000,
//           });
//         },
//       });
//     },
//   });
// };
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle>Registro de Impuesto</CardTitle>
            <CardDescription>Formulario de Impuesto</CardDescription>
        </CardHeader>
        <CardContent>
            <Form
                resetOnSuccess
                :action="TaxController.store()"
                #default="{ errors }"
                class="space-y-3"
            >
                <div class="space-y-2">
                    <Label for="name">Nombre</Label>
                    <Input name="name" />
                    <InputError :message="errors.name" />
                </div>
                <div class="space-y-2">
                    <Label for="description">Descripcion</Label>
                    <Input name="description" />
                    <InputError :message="errors.description" />
                </div>
                <div>
                    <NumberField
                        id="percent"
                        :step="0.01"
                        :formatOptions="{
                            style: 'decimal',
                        }"
                    >
                        <Label for="percent">Porcentaje</Label>
                        <NumberFieldContent>
                            <NumberFieldDecrement />
                            <NumberFieldInput name="rate" />
                            <NumberFieldIncrement />
                        </NumberFieldContent>
                    </NumberField>
                    <InputError :message="errors.rate" />
                </div>
                <div class="text-right mt-5">
                    <Button>Registrar</Button>
                </div>
            </Form>
        </CardContent>
    </Card>
    <Separator />
    <DataTableField :columns="TaxColumn" :data="props.taxes" />

    <!-- <AppLayout>
        <Card>
            <template #title>
                <div>
                    <BreadCrumbComponent :itemOptions="itemsSettings" />
                </div>
                <div class="text-right">
                    <Button @click="createTax = true" title="Nuevo">
                        <template #icon>
                            <SquarePlus />
                        </template>
                    </Button>
                </div>
            </template>
            <template #content>
                <DataTable :value="taxes">
                    <Column field="name" header="Nombre" />
                    <Column field="description" header="Descripcion" />
                    <Column :field="(data: TaxBaseI) => `${data.rate} %`" header="Tasa" />
                    <Column class="w-40" header="Act">
                        <template #body="{ data }: { data: TaxBaseI }">
                            <div class="space-x-3">
                                <Button
                                    @click="editData(data)"
                                    class="pt-1 h-8"
                                    title="Editar"
                                    icon="pi pi-file-edit"
                                />
                                <Button
                                    @click="deleteData(data, $event)"
                                    class="pt-1 h-8"
                                    title="Elimianr"
                                    severity="danger"
                                    icon="pi pi-trash"
                                />
                            </div>
                        </template>
                    </Column>
                </DataTable>
            </template>
        </Card>

        <Dialog modal v-model:visible="createTax" header="Registro de Unidad">
            <form class="w-100" @submit.prevent="submit">
                <FloatLabel class="mt-5" variant="on">
                    <InputText v-model="form.name" class="w-full" id="name" />
                    <label for="name">Nombre</label>
                </FloatLabel>
                <FloatLabel variant="on" class="mt-5">
                    <InputText v-model="form.description" class="w-full" id="name" />
                    <label for="name">Descripcion</label>
                </FloatLabel>
                <FloatLabel variant="on" class="mt-5">
                    <InputNumber
                        suffix="%"
                        v-model="form.rate"
                        :min="0"
                        :max="100"
                        class="w-full"
                        id="name"
                    />
                    <label for="name">Descripcion</label>
                </FloatLabel>
                <div class="mt-5 text-right">
                    <Button type="submit" label="Registrar">
                        <template #icon>
                            <Forward />
                        </template>
                    </Button>
                </div>
            </form>
        </Dialog>
    </AppLayout> -->
</template>
