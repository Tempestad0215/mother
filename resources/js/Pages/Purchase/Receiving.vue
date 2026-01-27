<script setup lang="ts">

import {
    AutoComplete,
    AutoCompleteCompleteEvent,
    AutoCompleteOptionSelectEvent,
    Button,
    Card,
    Column,
    DataTable,
    DatePicker,
    Dialog,
    FloatLabel,
    InputGroup,
    InputGroupAddon,
    Select,
    useToast
} from "primevue";
import AppLayout from "@layout/AppLayout.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faTruckField} from "@fortawesome/free-solid-svg-icons";
import {PaginationI} from "@/Interfaces/GlobalInterface";
import {PurchaseBaseI, PurchaseFormI, PurchaseItemI, PurchaseSupplierI} from "@/Interfaces/PurchaseInterface";
import {SupplierI} from "@/Interfaces/SupplierInterface";
import {ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import {PurchaseStatusEnum} from "@/Enums/PurchaseEnum";
import {getMoney} from "@/Global/Helpers";


const toast = useToast()

interface PropsI {
    purchases: PaginationI<PurchaseSupplierI>
    suppliers: PaginationI<SupplierI>
    purchaseAvailable: PurchaseSupplierI[] | null
}

const propsW = withDefaults(defineProps<PropsI>(),{
    purchaseAvailable: null
})

const searchSupplier = ref("")
const filteredSuppliers = ref<SupplierI[]>([])
const showPurchaseAvailable = ref(false)
const purchaseAvailable = ref<PurchaseBaseI | null>(null)


const form = useForm<PurchaseFormI>({
    id: 0,
    code: "",
    supplier_id: 0,
    user_id: 0,
    supplier_name: "",
    items: [],
    doc_date: "",
    amount: 0,
    tax: 0,
    discount: 0,
    sub_total: 0,
    comment: "",
    status: PurchaseStatusEnum.Pendiente

})



const getSuppliers = async (event:AutoCompleteCompleteEvent) => {
    try {

        router.get(route("purchase.receiving.index",{search: event.query}),{},{
            onSuccess: () =>{
                filteredSuppliers.value = propsW.suppliers.data ?? [];
            },
            preserveState: true,
            preserveScroll: true
        })

    }catch (err){
        filteredSuppliers.value = [];
    }
}

const selectSupplier = async (event: AutoCompleteOptionSelectEvent) => {
    const params = new URLSearchParams(window.location.search)
    const search = params.get('search') ?? "";
    const supplier:SupplierI = event.value

    router.get(route("purchase.receiving.index",{search, supplier: supplier.id}),{},{
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {

            if (propsW.purchaseAvailable && propsW.purchaseAvailable?.length > 1)
            {
                showPurchaseAvailable.value = true;
            }else{
                const purchase = propsW.purchaseAvailable ? propsW.purchaseAvailable[0] : null;
                if (purchase)
                {
                    form.id = purchase.id;
                    form.code = purchase.code;
                    form.supplier_id = purchase.supplier_id;
                    form.supplier_name = supplier.company_name;
                    form.user_id = purchase.user_id;
                    form.doc_date = purchase.doc_date;
                    form.amount = purchase.amount;
                    form.tax = purchase.tax;
                    form.discount = purchase.discount;
                    form.sub_total = purchase.sub_total;
                    form.status = purchase.status;
                    form.items = purchase.items;
                }
            }

        }
    })
}

</script>

<template>
    <AppLayout>
        <Card>
            <template #title>
                <h3>Recepcion de Marcancia</h3>
            </template>
            <template #content>
                <form>
                    <div class="flex items-center justify-between">
                        <div>
                            <InputGroup>
                                <AutoComplete
                                    @itemSelect="selectSupplier"
                                    v-model="searchSupplier"
                                    @complete="getSuppliers"
                                    optionLabel="company_name"
                                    :suggestions="filteredSuppliers" />
                                <InputGroupAddon >
                                    <FontAwesomeIcon title="Mostrar Suplidores" class="text-3xl" :icon="faTruckField"/>
                                </InputGroupAddon>
                            </InputGroup>
                        </div>

                        <div class="flex gap-3">
                            <FloatLabel variant="on" >
                                <Select />
                            </FloatLabel>
                            <FloatLabel variant="on" >
                                <DatePicker />
                                <label for="">Fecha</label>
                            </FloatLabel>
                        </div>
                    </div>

                    <DataTable :value="form.items" >
                        <Column header="#" >
                            <template #body="{index}">
                                {{index+1}}
                            </template>
                        </Column>
                        <Column field="product_name" header="Producto/Servicio" />
                        <Column header="Cantidad" >
                            <template #body="{data}:{data:PurchaseItemI}">
                                {{getMoney(data.quantity)}}
                            </template>
                        </Column>
                        <Column header="Costo" >
                            <template #body="{data}:{data:PurchaseItemI}">
                                {{getMoney(data.cost)}}
                            </template>
                        </Column>
                        <Column header="Itbis" >
                            <template #body="{data}:{data:PurchaseItemI}">
                                {{getMoney(data.tax_amount)}}
                            </template>
                        </Column>
                        <Column field="warehouse_name" header="Almacen" />
                        <Column header="Descuento" >
                            <template #body="{data}:{data:PurchaseItemI}">
                                {{getMoney(data.discount)}}
                            </template>
                        </Column>
                        <Column header="Importe" >
                            <template #body="{data}:{data:PurchaseItemI}">
                                {{getMoney(data.amount)}}
                            </template>
                        </Column>
                        <template #footer>
                            <div class="float-right ">
                                <p>Descuento  : {{}}</p>
                                <p>Itbis  : {{}}</p>
                                <p>Sub Total  : {{}}</p>
                                <p class="text-white bg-blue-800 rounded-md px-6 py-1" >Total  : {{}}</p>
                            </div>
                            <div class="clear-both"></div>
                        </template>
                    </DataTable>
                    <div class="mt-3 space-x-3 text-right">
                        <Button severity="warn" outlined  icon="pi pi-exclamation-triangle"  label="Cancelar" />
                        <Button icon="pi pi-send"  label="Registrar" />
                    </div>
                </form>
            </template>
        </Card>

        <Dialog
            :header="`Compra Disponible Para Recibir de: ${form.supplier_name}`"
            v-model:visible="showPurchaseAvailable"
            modal>
            <DataTable :value="purchaseAvailable" >
                <Column field="code" header="Codigo" />
                <Column field="tax" header="Itbis" />
                <Column field="discount" header="Descuento" />
                <Column field="sub_total" header="Sub Total" />
                <Column header="Act">
                    <template #body>
                        <Button icon="pi pi-check" />
                    </template>
                </Column>
            </DataTable>
        </Dialog>
    </AppLayout>

</template>
