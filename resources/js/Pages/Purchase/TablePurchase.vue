<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {ref} from "vue";
import {DataTable, Column, Dialog} from "primevue";
import {PurchaseSupplierI} from "@/Interfaces/PurchaseInterface";


/*
Propiedades
 */
const propsW = defineProps<{
    purchases: PurchaseSupplierI[]
}>();

/*
Datos de la ventana
 */
const showProduct = ref<boolean>(false)




</script>

<template>
    <AppLayout>
        <DataTable :value="propsW.purchases" >
            <Column header="ID" field="id" />
            <Column header="Name" field="supplier.company_name"  />
            <Column header="Item #" :field="(data:PurchaseSupplierI) => `${data.products.length}`"  />
            <Column header="Descuento" field="discount"  />
            <Column header="Itbis" field="tax"  />
            <Column header="Sub Total" field="sub_total"  />
            <Column header="Total" field="amount"  />
        </DataTable>
        <Dialog
            modal
            header="Creacion de Compra">

        </Dialog>
    </AppLayout>
</template>
