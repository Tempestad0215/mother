<script setup lang="ts">

import {
    Card, AutoComplete, DataTable, Column, Button, InputGroup, InputGroupAddon, FloatLabel, DatePicker, Select,
    AutoCompleteCompleteEvent, AutoCompleteOptionSelectEvent
} from "primevue";
import AppLayout from "@layout/AppLayout.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faTruckField} from "@fortawesome/free-solid-svg-icons";
import {PaginationI} from "@/Interfaces/GlobalInterface";
import {PurchaseSupplierI} from "@/Interfaces/PurchaseInterface";
import {SupplierI} from "@/Interfaces/SupplierInterface";
import {computed, ref} from "vue";

interface PropsI {
    purchases: PaginationI<PurchaseSupplierI>
    suppliers: PaginationI<SupplierI>
}

const propsW = withDefaults(defineProps<PropsI>(),{

})

const searchSupplier = ref("")
const filteredSuppliers = ref<SupplierI[]>([])




const getSuppliers = (event:AutoCompleteCompleteEvent) => {
    setTimeout(()=>{
        filteredSuppliers.value = propsW.suppliers.data?.filter((supplier) => {
            return supplier.company_name.toLowerCase().startsWith(event.query.toLowerCase())
        })
    },250)
}

const selectSupplier = (event: AutoCompleteOptionSelectEvent) => {
    console.log(event)
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

                    <DataTable>
                        <Column header="#" />
                        <Column header="Producto/Servicio" />
                        <Column header="Cantidad" />
                        <Column header="Costo" />
                        <Column header="Itbis" />
                        <Column header="Almacen" />
                        <Column header="Decuento" />
                        <Column header="Importe" />
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
    </AppLayout>

</template>
