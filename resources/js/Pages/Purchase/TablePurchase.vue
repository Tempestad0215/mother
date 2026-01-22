<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {ref} from "vue";
import {DataTable, Column, Dialog, Card, Breadcrumb, Button, Fieldset} from "primevue";
import {PurchaseSupplierI} from "@/Interfaces/PurchaseInterface";
import {purchaseBreadCrumb} from "@/Helpers/PurchaseHelper";


/*
Propiedades
 */
const propsW = defineProps<{
    purchases: PurchaseSupplierI[]
}>();

/*
Datos de la ventana
 */
const showPurchase = ref<boolean>(false)
const purchaseSelected = ref<PurchaseSupplierI | null>(null);



const selectPurchase = (purchase: PurchaseSupplierI | null) => {
    showPurchase.value = true
    purchaseSelected.value = purchase
}


</script>

<template>
    <AppLayout>
        <Card>
            <template #title>
                <Breadcrumb :model="purchaseBreadCrumb" />
                <h3 class="text-2xl font-bold text-center" >Orden de Compra</h3>
            </template>
            <template #content >
                <DataTable :value="propsW.purchases" >
                    <Column header="ID" field="id" />
                    <Column header="Name" field="supplier.company_name"  />
                    <Column header="Item #" :field="(data:PurchaseSupplierI) => `${data.products.length}`"  />
                    <Column header="Descuento" field="discount"  />
                    <Column header="Itbis" field="tax"  />
                    <Column header="Sub Total" field="sub_total"  />
                    <Column header="Total" field="amount"  />
                    <Column header="Estado" field="status"  />
                    <Column header="Act">
                        <template #body="{data}: {data: PurchaseSupplierI}">
                            <Button @click="selectPurchase(data)" icon="pi pi-eye" />
                        </template>
                    </Column>
                </DataTable>
            </template>
        </Card>

        <Dialog
            v-model:visible="showPurchase"
            modal
            :header="`Mostrado Compra de : ${purchaseSelected?.supplier.company_name}, Numero ${purchaseSelected?.code}`">
            <Card>
                <template #title>

                </template>
                <template #content>
                    <Fieldset legend="Datos Proveedor" >
                        <p><strong>Empresa :</strong> {{purchaseSelected?.supplier.company_name}}</p>
                        <p><strong>Telefono :</strong> {{purchaseSelected?.supplier.phone}}</p>
                        <p><strong>Correo :</strong> {{purchaseSelected?.supplier.email}}</p>
                    </Fieldset>
                    <DataTable :value="purchaseSelected?.products">
                        <Column header="#"  >
                            <template #body="{index}">
                                {{index + 1 }}
                            </template>
                        </Column>
                        <Column header="Producto" field="producto"  />
                    </DataTable>
                </template>
            </Card>
        </Dialog>
    </AppLayout>
</template>
