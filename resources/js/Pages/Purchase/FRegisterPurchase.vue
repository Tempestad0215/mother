<script setup lang="ts">

import {Card, Select, DatePicker, DataTable, Column, FloatLabel} from "primevue";
import {moneyConfig} from "@/Global/Helpers";
import {Money} from "v-money3";
import InputLabel from "@components/InputLabel.vue";
import SelectOption from "@components/SelectOption.vue";
import TextInput from "@components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import {purchaseInfoI} from "@/Interfaces/PurchaseInterface";
import {supplierI} from "@/Interfaces/SupplierInterface";
import AppLayout from "@layout/AppLayout.vue";
import {PreciseCalculator} from "@/utils/Decimal";


const propsW = defineProps<{
    suppliers: supplierI[]
}>()

/*
   Formulario
 */
const form = useForm({
    id:0,
    info: [{
        uuid:"",
        name: "",
        quantity: 0,
        tax: 0,
        price: 0,
        tax_rate: 0,
        discount: 0,
        amount: 0,
        status: 0,
    }] as purchaseInfoI[],
    tax_total: 0,
    discount_total: 0,
    sub_total: 0,
    amount: 0,
    comment:"",
});

</script>

<template>
    <AppLayout>
        <Card>
            <template #header>

            </template>

            <template #content>
                <form
                    action="">
                    <div class="flex gap-3">
                        <FloatLabel variant="on">
                            <Select id="supplier_id" fluid :options="propsW.suppliers" optionValue="id" optionLabel="company_name" />
                            <label for="supplier_id">Suplidor</label>
                        </FloatLabel>
                        <FloatLabel variant="on" >

                            <DatePicker id="doc_date" />
                            <label for="doc_date">Fecha Documento</label>
                        </FloatLabel>

                    </div>


                    <DataTable :value="form.info">
                        <Column header="#" />
                        <Column header="Producto/Servicio" />
                        <Column header="Cantidad" />
                        <Column header="Costo" />
                        <Column header="Precio" />
                        <Column header="Descuento" />
                        <Column header="Importe" />
                        <Column header="Act" />
                        <template #footer>

                            <div class="float-right max-w-72 bg-white text-gray-800 rounded-xl p-4 shadow-lg border">
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium">Descuento : </span>
                                    <span class="text-green-600 font-semibold">
                                      {{ PreciseCalculator.formatCurrency(25) }}
                                    </span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium">Impuestos : </span>
                                    <span class="text-blue-600 font-semibold">
                                      {{ PreciseCalculator.formatCurrency(1235) }}
                                    </span>
                                </div>
                                <div class="flex justify-between mb-2">
                                    <span class="font-medium">Sub Total : </span>
                                    <span class="text-gray-700 font-semibold">
                                      {{ PreciseCalculator.formatCurrency(1650) }}
                                    </span>
                                </div>
                                <div class="flex justify-between pt-2 border-t mt-2">
                                    <span class="font-bold text-lg">Total :</span>
                                    <span class="font-bold text-lg text-red-600">
                                      {{ PreciseCalculator.formatCurrency(1893) }}
                                    </span>
                                </div>
                            </div>
                            <div class="clear-both"></div>



                        </template>
                    </DataTable>

                </form>
            </template>
        </Card>
    </AppLayout>

</template>
