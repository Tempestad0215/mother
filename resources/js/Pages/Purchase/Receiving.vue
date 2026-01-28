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
    useToast,
    Textarea,
    InputNumber, useConfirm, Breadcrumb
} from "primevue";
import AppLayout from "@layout/AppLayout.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faTruckField} from "@fortawesome/free-solid-svg-icons";
import {PaginationI} from "@/Interfaces/GlobalInterface";
import {
    PurchaseBaseI,
    PurchaseFormI,
    PurchaseItemFormI,
    PurchaseItemI,
    PurchaseSupplierI
} from "@/Interfaces/PurchaseInterface";
import {SupplierI} from "@/Interfaces/SupplierInterface";
import {onMounted, ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";
import {PurchaseStatusEnum} from "@/Enums/PurchaseEnum";
import {getMoney} from "@/Global/Helpers";
import {PreciseCalculator} from "@/utils/Decimal";
import {purchaseBreadCrumb} from "@/Helpers/PurchaseHelper";


const toast = useToast()
const confirm = useConfirm()

interface PropsI {
    purchases: PaginationI<PurchaseSupplierI>
    suppliers: PaginationI<SupplierI>
    purchaseAvailable: PurchaseSupplierI[] | null
    purchaseStatus: {
        name: string
        value: string
    }[]
}

const propsW = withDefaults(defineProps<PropsI>(),{
    purchaseAvailable: null
})

const searchSupplier = ref("")
const filteredSuppliers = ref<SupplierI[]>([])
const showPurchaseAvailable = ref(false)
const purchaseAvailable = ref<PurchaseBaseI | null>(null)
const docDate = ref<Date | null>(new Date())

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
    status: PurchaseStatusEnum.Parcial

})



onMounted(()=>{
    getDate(new Date())
})

const getSuppliers = async (event:AutoCompleteCompleteEvent) => {
    try {
        setTimeout(()=>{
            router.get(route("purchase.receiving.index",{search: event.query}),{},{
                onSuccess: () =>{
                    filteredSuppliers.value = propsW.suppliers.data ?? [];
                },
                preserveState: true,
                preserveScroll: true
            })
        },250)


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
                    form.amount = purchase.amount;
                    form.tax = purchase.tax;
                    form.discount = purchase.discount;
                    form.sub_total = purchase.sub_total;
                    form.items = purchase.items.map(item => ({
                        ...item,
                        isReadOnly: true
                    }));
                }
            }

        }
    })
}

const deleteItem = (index:number) => {
    confirm.require({
        message: '¿Estás seguro de que deseas eliminar este artículo?',
        header: 'Confirmar eliminación',
        icon: 'pi pi-exclamation-triangle',
        accept: () => {
            form.items.splice(index, 1);
            toast.add({severity:'success', summary: 'Éxito', detail:'Artículo eliminado correctamente', life: 3000});
        },
    });
}

const sumSubTotalByLine = ()=>{
    const discountTotal = form.items.reduce((acc:number, curr:PurchaseItemFormI) => acc + curr.discount , 0)
    const subTotal = form.items.reduce((acc:number, curr:PurchaseItemFormI) => acc + curr.amount , 0)
    const taxTotal = form.items.reduce((acc:number, curr:PurchaseItemFormI) => acc + curr.tax_amount , 0)

    form.tax = taxTotal;
    form.discount = discountTotal;

    form.sub_total = Number(PreciseCalculator.subtract(
        subTotal,
        taxTotal,
    ))

    form.amount = Number(
        PreciseCalculator.add(
            taxTotal,
            form.sub_total
        )
    )
}

const calculateAmount = (index:number) => {
    const info = form.items[index];
    const taxPercent = PreciseCalculator.divide(info.tax_rate, 100)
    const cost = info.cost;
    const quantity = info.quantity;
    const discountRate = Number(PreciseCalculator.divide(
        info.discount,
        100
    ));
    const taxPerProduct = PreciseCalculator.multiply(cost, taxPercent.toString())
    form.items[index].tax_amount = Number(PreciseCalculator.multiply(taxPerProduct.toString(), quantity));
    const base = PreciseCalculator.multiply(quantity, cost);
    const discountAmount = Number(PreciseCalculator.multiply(
        base.toString(),
        discountRate,
    ))

    form.items[index].discount = discountAmount
    form.items[index].amount = Number(
        PreciseCalculator.subtract(
            base.toString(),
            discountAmount

        )
    )

    sumSubTotalByLine()
}

const getDate = (date: Date) => {
    form.doc_date = date.toISOString()
}

const submit = () => {
    form.post(route('purchase.receiving.store'),{
        onSuccess: () => {
            console.log("Esta es nueva")
        },
        onError: (err) =>{
            toast.add({
                severity: "error",
                summary: "Error",
                detail: `Error en Esta Peticion, Detalle : ${Object.values(err)[0]}`,
                life: 5000
            })
        }
    })
}

</script>

<template>
    <AppLayout>
        <Card>
            <template #title>
                <h3 class="text-center text-2xl font-bold">Recepcion de Marcancia</h3>
                <div>
                    <Breadcrumb :model="purchaseBreadCrumb" />
                </div>
            </template>
            <template #content>
                <form @submit.prevent="submit" >
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
                            <div class="w-50">
                                <FloatLabel  variant="on" >
                                    <Select v-model="form.status" fluid optionLabel="name" optionValue="value" :options="propsW.purchaseStatus"  />
                                    <label for="">Estado Recepcion</label>
                                </FloatLabel>
                            </div>

                            <div>
                                <FloatLabel variant="on" >
                                    <DatePicker @dateSelect="getDate" v-model="docDate" />
                                    <label for="">Fecha</label>
                                </FloatLabel>
                            </div>

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
                            <template #body="{index, data}:{index:number, data:PurchaseItemFormI}">
                                <InputNumber @blur="calculateAmount(index)" :readonly="data.isReadOnly" locale="en-US" :minFractionDigits="2" :maxFractionDigits="2" v-model="form.items[index].quantity" />
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
                            <template #body="{data}:{data:PurchaseItemFormI}">
                                {{getMoney(data.amount)}}
                            </template>
                        </Column>
                        <Column header="Act" >
                            <template #body="{index, data}:{index:number, data:PurchaseItemFormI}">
                                <Button title="Editar" :severity="data.isReadOnly ? 'danger' : 'success' "  @click="form.items[index].isReadOnly = false"  icon="pi pi-pencil" class="mr-2" />
                                <Button v-if="form.items.length > 1" @click="deleteItem(index)" icon="pi pi-trash" severity="danger" />
                            </template>
                        </Column>
                        <template #footer>
                            <div class="flex items-center justify-between">
                                <div>
                                    <FloatLabel variant="on" >
                                        <Textarea :cols="30" :rows="2" class="max-w-60 min-w-20 min-h-15 max-h-30"  v-model="form.comment" />
                                        <label for="">Comentario</label>
                                    </FloatLabel>

                                </div>
                                <div class=" ">
                                    <p>Descuento  : {{getMoney(form.discount)}}</p>
                                    <p>Itbis  : {{getMoney(form.tax)}}</p>
                                    <p>Sub Total  : {{getMoney(form.sub_total)}}</p>
                                    <p class="text-white bg-blue-800 rounded-md px-6 py-1" >Total  : {{getMoney(form.amount)}}</p>
                                </div>
                            </div>
                        </template>
                    </DataTable>
                    <div class="mt-3 space-x-3 text-right">
                        <Button severity="warn" outlined  icon="pi pi-exclamation-triangle"  label="Cancelar" />
                        <Button @click="submit" :disabled="form.processing" icon="pi pi-send"  label="Registrar" />
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
