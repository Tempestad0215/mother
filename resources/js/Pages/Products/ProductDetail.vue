<script setup lang="ts">
import {Fieldset, FloatLabel, Select, InputNumber, InputText, SelectChangeEvent} from "primevue";
import {inject} from "vue";
import {formProductKey} from "@/Injections/InjectionKeys";
import {BranchInterfaceI} from "@/Interfaces/BranchInterface";
import {UnitInterfaceI} from "@/Interfaces/UnitInterface";
import {usePage} from "@inertiajs/vue3";
import {AppPageProps} from "@/global";
import {TaxInterfaceI} from "@/Interfaces/TaxInterface";
import {useProductStore} from "@/stores/ProductStore";
import {WarehouseBaseI} from "@/Interfaces/WarehouseInterface";



const page = usePage<AppPageProps>();



const propsW = defineProps<{
    units: UnitInterfaceI[],
    branches: BranchInterfaceI[]
    warehouses: WarehouseBaseI[]
}>()



const form = inject(formProductKey)!!
const taxes = page.props.taxes;
const productStore = useProductStore()



const selectTax = (data:SelectChangeEvent) => {
    const taxInfo:TaxInterfaceI | undefined = taxes.find((el)=> el.uuid === data.value)

    productStore.setTaxRateFromPercent(Number(taxInfo?.rate))

}


</script>

<template>
    <Fieldset legend="Caracteristicas" >
        <div class="grid grid-cols-2 gap-4">
            <FloatLabel  variant="on" >
                <Select
                    fluid
                    id="tax"
                    option-value="uuid"
                    @change="selectTax"
                    :optionLabel="(item:TaxInterfaceI) => `${item.name } | ${item.rate}`"
                    :options="taxes"
                    v-model="form.tax_uuid">
                </Select>
                <label for="tax">Impuesto</label>
            </FloatLabel>
            <FloatLabel   variant="on" >
                <Select fluid id="unit" optionValue="uuid" optionLabel="name"  :options="propsW.units"  v-model="form.unit_uuid" />
                <label for="tax">Unidad</label>
            </FloatLabel>
            <FloatLabel   variant="on" >
                <InputNumber fluid id="weight" v-model="form.weight" />
                <label for="weight">Peso</label>
            </FloatLabel>
            <FloatLabel   variant="on" >
                <Select optionLabel="name" optionValue="uuid" fluid :options="propsW.branches"  id="branch" v-model="form.brand_uuid" />
                <label for="branch">Ramas</label>
            </FloatLabel>
            <FloatLabel   variant="on" >
                <InputText fluid  id="dimension" v-model="form.dimensions" />
                <label for="dimension">Dimensiones</label>
            </FloatLabel>
            <FloatLabel   variant="on" >
                <Select
                    fluid
                    :options="propsW.warehouses"
                    v-model="form.warehouse_uuid"
                    optionValue="uuid"
                    optionLabel="name"/>
                <label id="warehouse" >Almacen</label>
            </FloatLabel>
        </div>
    </Fieldset>
</template>
