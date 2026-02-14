<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {ref} from "vue";
import {Breadcrumb, Button, Card, Column, DataTable, Dialog, Fieldset, Tag, useConfirm, useToast} from "primevue";
import {PurchaseItemI, PurchaseSupplierI} from "@/Interfaces/PurchaseInterface";
import {purchaseBreadCrumb, PurchaseStatusSeverity} from "@/Helpers/PurchaseHelper";
import {getMoney} from "@/Global/Helpers";
import {PurchaseStatusEnum} from "@/Enums/PurchaseEnum";
import {router} from "@inertiajs/vue3";


const confirm = useConfirm();
const toast = useToast();
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


const getSeverityTag = (status: PurchaseStatusEnum) => {
    return PurchaseStatusSeverity[status] ?? 'secondary'
}




const selectPurchase = (purchase: PurchaseSupplierI | null) => {
    showPurchase.value = true
    purchaseSelected.value = purchase
}

const clearAll = () => {
    purchaseSelected.value = null;
    showPurchase.value = false;
}

const approve = () => {
    router.patch(route('purchase.approve', {purchase: purchaseSelected.value?.id}),{},{
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Aprobado',
                detail: 'Orden Aprobada Correctamente',
                life: 3000
            });
            clearAll();
        },
        onError: (err) => {
            toast.add({
                severity: 'danger',
                summary: 'Error',
                detail: `Error En esta Solicitud, Detalle ${Object.values(err)[0]}`,
                life: 5000
            });
        }
    })
}

const approveOrder = (event:Event) => {
    confirm.require({
        target: event.target as HTMLElement,
        message: "Desea Aprobar Esta orden",
        icon: "pi pi-info-circle",
        rejectProps: {
            label: 'Cancelar',
            severity: 'warn',
            outlined: true
        },
        acceptProps: {
            label: 'Aprobar'
        },
        accept: () => {
            approve()
        }

    })
}

const cancel = () => {
    router.patch(route('purchase.cancel', {purchase: purchaseSelected.value?.id}),{},{
        onSuccess: () => {
            toast.add({
                severity: 'success',
                summary: 'Cancelado',
                detail: 'Orden Cancelada Correctamente',
                life: 3000
            });
            clearAll();
        },
        onError: (err) => {
            toast.add({
                severity: 'danger',
                summary: 'Error',
                detail: `Error En esta Solicitud, Detalle ${Object.values(err)[0]}`,
                life: 5000
            });
        }
    })
}

const cancelOrder = (event:Event) => {
    confirm.require({
        target: event.target as HTMLElement,
        message: "Desea Cancelar Esta Orden, Los Cambios Relizados Son Irreversibles",
        icon: "pi pi-exclamation-triangle",
        rejectProps: {
            label: 'Cancelar',
            severity: 'warn',
            outlined: true
        },
        acceptProps: {
            severity: 'danger',
            label: 'Cancelar'
        },
        accept: () => {
            cancel()
        }

    })
}

const createReception = (data: PurchaseSupplierI) => {
    router.get(route('purchase.receiving.index', {supplier: data.supplier_id}))
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
                    <Column header="Item #" :field="(data:PurchaseSupplierI) => `${data.items.length}`"  />
                    <Column header="Descuento" :field="(data:PurchaseSupplierI) => `${getMoney(data.discount)}`"  />
                    <Column header="Itbis" :field="(data:PurchaseSupplierI) => `${getMoney(data.tax)}`"  />
                    <Column header="Sub Total" :field="(data:PurchaseSupplierI) => `${getMoney(data.sub_total)}`" />
                    <Column header="Total" :field="(data:PurchaseSupplierI) => `${getMoney(data.amount)}`"   />
                    <Column header="Estado"  >
                        <template #body="{data}:{data:PurchaseSupplierI}">
                            <Tag :severity="getSeverityTag(data.status)" :value="data.status" />
                        </template>
                    </Column>
                    <Column header="Act">
                        <template #body="{data}: {data: PurchaseSupplierI}">
                            <div class="space-x-3">
                                <Button
                                    v-if="data.status !== PurchaseStatusEnum.Borrador && data.status !== PurchaseStatusEnum.Completada"
                                    title="Entrada"
                                    severity="info"
                                    outlined
                                    @click="createReception(data)"
                                    icon="pi pi-cart-arrow-down" />
                                <Button title="Ver Detalle" @click="selectPurchase(data)" icon="pi pi-eye" />
                            </div>

                        </template>
                    </Column>
                </DataTable>
            </template>
        </Card>

        <Dialog
            v-model:visible="showPurchase"
            modal
            @hide="clearAll"
            :header="`Mostrado Compra de : ${purchaseSelected?.supplier.company_name}, Numero ${purchaseSelected?.code}`">
            <Card>
                <template #title>
                    <div>
                        <span>Estado Orden : </span>
                        <Tag :severity="getSeverityTag(purchaseSelected?.status!!)" :value="purchaseSelected?.status"/>
                    </div>
                </template>
                <template #content>
                    <Fieldset legend="Datos Proveedor" >
                        <div class="grid grid-cols-2 gap-3">
                            <p ><strong>Empresa :</strong> {{purchaseSelected?.supplier.company_name}}</p>
                            <p><strong>Telefono :</strong> {{purchaseSelected?.supplier.phone}}</p>
                            <p><strong>Correo :</strong> {{purchaseSelected?.supplier.email}}</p>
                            <p><strong>Fecha :</strong> {{purchaseSelected?.doc_date}}</p>

                        </div>
                    </Fieldset>
                    <DataTable :value="purchaseSelected?.items">
                        <Column header="#"  >
                            <template #body="{index}">
                                {{index + 1 }}
                            </template>
                        </Column>
                        <Column header="Producto" field="product_name"  />
                        <Column header="Costo" :field="(data:PurchaseItemI) => `${getMoney(data.cost)}`"  />
                        <Column header="Cantidad" :field="(data:PurchaseItemI) => `${getMoney(data.quantity)}`"  />
                        <Column header="Itbis" :field="(data:PurchaseItemI) => `${data.tax_rate} %`"  />
                        <Column header="Descuento" :field="(data:PurchaseItemI) => `${getMoney(data.discount)}`"  />
                        <Column header="Almacen" field="warehouse_name"  />
                        <Column header="Importe" :field="(data:PurchaseItemI) => `${getMoney(data.amount)}`"  />

                        <template #footer>
                            <div class="float-right ">
                                <p>Descuento  : {{getMoney(purchaseSelected?.discount)}}</p>
                                <p>Itbis  : {{getMoney(purchaseSelected?.tax)}}</p>
                                <p>Sub Total  : {{getMoney(purchaseSelected?.sub_total)}}</p>
                                <p class="text-white bg-blue-800 rounded-md px-6 py-1" >Total  : {{getMoney(purchaseSelected?.amount)}}</p>
                            </div>
                            <div class="clear-both"></div>
                            <div class="text-right mt-5 space-x-3">
                                <Button
                                    @click="cancelOrder($event)" severity="warn" outlined label="Cancelar" />
                                <Button
                                    v-if="purchaseSelected?.status === PurchaseStatusEnum.Borrador"
                                    @click="approveOrder($event)" label="Aprobar" />
                            </div>
                        </template>
                    </DataTable>
                </template>
            </Card>
        </Dialog>
    </AppLayout>
</template>
