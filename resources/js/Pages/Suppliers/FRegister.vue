<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import {supplierI} from "@/Interfaces/SupplierInterface";
import {computed, onMounted} from "vue";
import {getMoney} from "@/Global/Helpers";
import {useRoute} from "ziggy-js";
import {paymentTypeEnumI} from "@/Interfaces/GlobalInterface";
import {Select, Card, FloatLabel, InputText, ToggleSwitch, Button, useToast} from "primevue";



const route = useRoute()
const toast = useToast()

const propsW = defineProps<{
    supplierEdit: supplierI | null,
    update: boolean,
    paymentTypes: paymentTypeEnumI
}>();

/*
Al momento de cargar
 */
onMounted(()=>{
    if (propsW.supplierEdit)
    {
        form.id = propsW.supplierEdit.id;
        form.contact = propsW.supplierEdit.contact ?? "";
        form.company_name = propsW.supplierEdit.company_name;
        form.phone = propsW.supplierEdit.phone ?? "";
        form.email = propsW.supplierEdit.email ?? "";
        form.comment = propsW.supplierEdit.comment ?? "";
    }
});


/*
Formulario
 */
const form = useForm({
    id: 0,
    contact:"",
    company_name:"",
    phone:"",
    email:"",
    type_payment:"Contado",
    receive_email: false,
    is_recurring: false,
    payment_day: null,
    account_bank: "",
    comment: "",
    amount: 0,
    due_date: 0,
    late_fee: 0,
    consumed: 0
});



/*
Propiedades computada
 */
const balance = computed(()=>{
    return getMoney((form.amount - form.consumed))
});

const getPaymentTypes = computed(()=>{
    return Object.entries(propsW.paymentTypes).map(([key, value]) => ({
        label: key,
        value: value
    }))
})




/**
 *Enviar los datos
 */
const submit = () => {
    // Si es actualziar
    if(propsW.update)
    {
        form.patch(route('supplier.update', {supplier: form.id}),{
            onSuccess:()=>{
                toast.add({
                    severity: "success",
                    summary: "Registro Actualizado Correctamente.",
                    life: 3000,
                })
            }
        });
    }else{
        // Enviar los datos
        form.post(route('supplier.store'),{
            onSuccess:()=>{
                toast.add({
                    severity: "success",
                    summary: "Registro Creado Correctamente.",
                    life: 3000,
                })
                form.reset();
            },
            onError:(err)=>{
                toast.add({
                    severity: "error",
                    summary: `Erro al intentar crear registro. Detalle: ${Object.values(err)[0]}.`,
                    life: 3000,
                })
            }
        });
    }
}

</script>

<template>
    <Card>
        <template #header>
            <h3 class="text-2xl font-bold text-center" >{{propsW.update ? "Actualizar" : "Crear"}} Cliente</h3>
        </template>
        <template #content>
            <form @submit.prevent="submit"  class="grid grid-cols-2 gap-4 w-150">
                <FloatLabel variant="on" >
                    <InputText class="w-full" id="company_name" v-model="form.company_name" />
                    <label for="company_name">Nombre Comercial <span class="text-red-500" >*</span> </label>
                </FloatLabel>
                <FloatLabel variant="on" >
                    <InputText class="w-full"  id="contact" v-model="form.contact" />
                    <label for="contact">Representante</label>
                </FloatLabel>
                <FloatLabel variant="on" >
                    <InputText class="w-full"  id="phone" v-model="form.phone" />
                    <label for="phone">Teléfono</label>
                </FloatLabel>
                <FloatLabel variant="on" >
                    <InputText class="w-full"  id="email" v-model="form.email" />
                    <label for="email">Correo Electrónico</label>
                </FloatLabel>
                <FloatLabel variant="on" >
                    <InputText class="w-full"  id="account_bank" v-model="form.account_bank" />
                    <label for="account_bank">Cuenta de Banco</label>
                </FloatLabel>
                <FloatLabel variant="on" >
                    <InputText class="w-full"  id="payment_day" v-model="form.payment_day" />
                    <label for="payment_day">Dia de pago</label>
                </FloatLabel>
                <FloatLabel class="col-span-full" variant="on" >
                    <InputText class="w-full"  id="comment" v-model="form.comment" />
                    <label for="comment">Comentario</label>
                </FloatLabel>
                <div class="flex justify-around col-span-full">
                    <div class="flex items-center justify space-x-3">
                        <ToggleSwitch v-model="form.receive_email" id="has_email" />
                        <label for="has_email">Rec. Correo</label>
                    </div>
                    <div class="flex items-center justify space-x-3">
                        <ToggleSwitch v-model="form.is_recurring" id="is_recurring" />
                        <label for="is_recurring">Pago Recurrente</label>
                    </div>
                    <Select class="w-60" v-model="form.type_payment" option-label="label" option-value="value" :options="getPaymentTypes" />
                </div>
                <div class="mt-5 space-x-3 text-right col-span-full">
                    <Button icon="pi pi-eraser" severity="warn" type="reset" label="Limpiar" />
                    <Button icon="pi pi-send" type="submit" :label="propsW.update ? 'Actualizar' : 'Registrar'" />
                </div>
            </form>
        </template>
    </Card>
</template>
