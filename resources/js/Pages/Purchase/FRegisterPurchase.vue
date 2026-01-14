<script setup lang="ts">
import {
    AutoComplete,
    AutoCompleteOptionSelectEvent,
    Button,
    Card,
    Column,
    DataTable,
    DatePicker,
    FloatLabel,
    InputNumber,
    Select,
    SelectChangeEvent,
    useToast
} from "primevue";
import {router, useForm} from "@inertiajs/vue3";
import {purchaseInfoI} from "@/Interfaces/PurchaseInterface";
import {supplierI} from "@/Interfaces/SupplierInterface";
import AppLayout from "@layout/AppLayout.vue";
import {PreciseCalculator} from "@/utils/Decimal";
import {ProductBaseI} from "@/Interfaces/ProductInterface";
import debounce from "lodash/debounce";
import {TaxInterfaceI} from "@/Interfaces/TaxInterface";
import {useProductStore} from "@/stores/ProductStore";

const toast = useToast();

const propsW = defineProps<{
    suppliers: supplierI[],
    products: ProductBaseI[]
    taxes: TaxInterfaceI[]
}>()

const productStore = useProductStore()
/*
   Formulario
 */
const form = useForm({
    id:0,
    info: [{
        id: 0,
        code:"",
        name: "",
        quantity: 0,
        cost: 0,
        tax_id: 0,
        tax: 0,
        discount_rate: 0,
        amount: 0,
    }] as purchaseInfoI[],
    tax_total: 0,
    discount_total: 0,
    sub_total: 0,
    amount: 0,
    comment:"",
});


const searchProduct = debounce((index: number) => {
    const productSearch = form.info[index].name;
    router.get(route('purchase.index',{productSearch}),{},{
        preserveScroll: true,
        preserveState: true,
    });
},500)


const getInfoName = (event:AutoCompleteOptionSelectEvent, index: number) => {
    const info = event.value as purchaseInfoI;
    form.info[index].id = info.id;
    form.info[index].code = info.code;
    form.info[index].name = info.name;
}


const submit = () => {
    form.post(route('purchase.store'),{
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Registro Completado",
                life: 3000
            })
        },
        onError: (err) => {
            toast.add({
                severity: "error",
                summary: "Registro Completado",
                detail: `Error en esta peticions, Detalle : ${Object.values(err)[0]}`,
                life: 5000
            })
        }
    })
}


const getTaxInfo = (event: SelectChangeEvent) => {
    const taxInfo:TaxInterfaceI | undefined = propsW.taxes.find((el) => el.id === event.value);
    productStore.setTaxRateFromPercent(Number(taxInfo?.rate) ?? 0)
}


const sumSubTotalByLine = ()=>{
    const discountTotal = form.info.reduce((acc:number, curr:purchaseInfoI) => acc + curr.discount_amount , 0)
    const subTotal = form.info.reduce((acc:number, curr:purchaseInfoI) => acc + curr.amount , 0)
    const taxTotal = form.info.reduce((acc:number, curr:purchaseInfoI) => acc + curr.tax , 0)


    form.tax_total = taxTotal;
    form.discount_total = discountTotal;

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
    const taxPercent = productStore.taxRate
    const info = form.info[index];
    const cost = info.cost;
    const quantity = info.quantity;
    const discountRate = Number(PreciseCalculator.divide(
        info.discount_rate,
        100
    ));
    const taxPerProduct = PreciseCalculator.multiply(cost, taxPercent)
    form.info[index].tax = Number(PreciseCalculator.multiply(taxPerProduct.toString(), quantity));
    const base = PreciseCalculator.multiply(quantity, cost);
    const discountAmount = Number(PreciseCalculator.multiply(
        base.toString(),
        discountRate,
    ))

    form.info[index].discount_amount = discountAmount
    form.info[index].amount = Number(
        PreciseCalculator.subtract(
            base.toString(),
            discountAmount

        )
    )

    sumSubTotalByLine()
}


const addLine = () => {
    const info = form.info[form.info.length - 1];
    if(info.name === "" || info.amount === 0)
    {
        toast.add({
            severity: "warn",
            summary: "Informacion",
            detail: "Por favor, revisar el ultimo registro",
            life: 6000
        })
        return false;
    }
    form.info.push({
        id: 0,
        code: "",
        name: "",
        quantity: 0,
        cost: 0,
        tax_id: 0,
        tax: 0,
        discount_rate: 0,
        amount: 0,
        discount_amount: 0
    })

}

</script>

<template>
    <AppLayout>
        <Card>
            <template #header>
                <h3 class="text-2xl font-bold text-center" >Orden de Compra</h3>
            </template>

            <template #content>
                <form
                    @submit.prevent="submit">
                    <div class="flex gap-3 justify-between">
                        <FloatLabel class="max-w-80" variant="on">
                            <Select id="supplier_id" fluid :options="propsW.suppliers" optionValue="id" optionLabel="company_name" />
                            <label for="supplier_id">Suplidor</label>
                        </FloatLabel>
                        <FloatLabel variant="on" >

                            <DatePicker id="doc_date" />
                            <label for="doc_date">Fecha Documento</label>
                        </FloatLabel>

                    </div>


                    <DataTable size="small" striped-rows show-gridlines class="mt-5" :value="form.info">
                        <Column header="#"  >
                            <template #body="{index}">
                                {{index + 1 }}
                            </template>
                        </Column>
                        <Column header="codigo"  >
                            <template #body="{index}">
                                {{ form.info[index].code }}
                            </template>
                        </Column>
                        <Column class="w-80" header="Producto/Servicio">
                            <template #body="{index}">
                                <AutoComplete
                                    @option-select="getInfoName($event, index)"
                                    @complete="searchProduct(index)"
                                    option-label="name"
                                    :suggestions="products"
                                    v-model="form.info[index].name"
                                    fluid />
                            </template>
                        </Column>
                        <Column class="w-30" header="Cantidad" >
                            <template #body="{index}">
                                <InputNumber
                                    @blur="calculateAmount(index)"
                                    v-model="form.info[index].quantity"
                                    fluid />
                            </template>
                        </Column>
                        <Column class="w-30" header="Costo" >
                            <template #body="{index}">
                                <InputNumber
                                    @blur="calculateAmount(index)"
                                    v-model="form.info[index].cost"
                                    fluid />
                            </template>
                        </Column>
                        <Column header="Imp." >
                            <template #body="{index}">
                                <Select
                                    @blur="calculateAmount(index)"
                                    placeholder="Itbis"
                                    :options="taxes"
                                    @change="getTaxInfo($event)"
                                    option-value="id"
                                    option-label="name"
                                    fluid />
                            </template>
                        </Column>
                        <Column header="Descuento" >
                            <template #body="{index}">
                                <InputNumber
                                    suffix="%"
                                    :min="0"
                                    :max="100"
                                    @blur="calculateAmount(index)"
                                    v-model="form.info[index].discount_rate"
                                    fluid />
                            </template>
                        </Column>
                        <Column header="Importe" >
                            <template #body="{index}">
                                <InputNumber  v-model="form.info[index].amount" readonly fluid />
                            </template>
                        </Column>
                        <Column header="Act" />
                        <template #footer>
                            <div class="text-center">
                                <Button @click="addLine" class="h-8" icon="pi pi-plus"/>
                            </div>
                        </template>
                    </DataTable>
                    <div>
                        <div class="float-right max-w-72 mt-5 bg-white text-gray-800 rounded-xl p-4 shadow-lg border">
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">Descuento : </span>
                                <span class="text-green-600 font-semibold">
                                      {{ PreciseCalculator.formatCurrency(form.discount_total) }}
                                    </span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">Impuestos : </span>
                                <span class="text-blue-600 font-semibold">
                                      {{ PreciseCalculator.formatCurrency(form.tax_total) }}
                                    </span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">Sub Total : </span>
                                <span class="text-gray-700 font-semibold">
                                      {{ PreciseCalculator.formatCurrency(form.sub_total) }}
                                    </span>
                            </div>
                            <div class="flex justify-between pt-2 border-t mt-2">
                                <span class="font-bold text-lg">Total :</span>
                                <span class="font-bold text-lg text-red-600">
                                      {{ PreciseCalculator.formatCurrency(form.amount) }}
                                    </span>
                            </div>
                        </div>
                        <div class="clear-both"></div>
                    </div>
                    <div class="text-right mt-5">
                        <Button :disabled="form.processing" icon="pi pi-send" type="submit" label="Registrar" />
                    </div>
                </form>
            </template>
        </Card>
    </AppLayout>

</template>
