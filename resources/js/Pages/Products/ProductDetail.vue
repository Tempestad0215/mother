<script setup lang="ts">
import {Fieldset, FloatLabel, Select, InputNumber, InputText, SelectChangeEvent} from "primevue";
import {inject} from "vue";
import {formProductKey, taxCurrentValueKey} from "@/Injections/InjectionKeys";
import {BranchInterfaceI} from "@/Interfaces/BranchInterface";
import {UnitInterfaceI} from "@/Interfaces/UnitInterface";
import {usePage} from "@inertiajs/vue3";
import {AppPageProps} from "@/global";
import {TaxInterfaceI} from "@/Interfaces/TaxInterface";
import {PreciseCalculator} from "@/utils/Decimal";

const page = usePage<AppPageProps>();

const propsW = defineProps<{
    units: UnitInterfaceI[],
    branches: BranchInterfaceI[]
}>()
const form = inject(formProductKey)!!
const taxes = page.props.taxes;
const taxCurrentValue = inject(taxCurrentValueKey)!!;

const selectTax = (event: SelectChangeEvent) => {
    const infoTax:TaxInterfaceI = event.value
    taxCurrentValue.value = Number(PreciseCalculator.divide(infoTax.rate, "100"))
}


</script>

<template>
    <Fieldset legend="Caracteristicas" >
        <div class="grid grid-cols-2 gap-4">
            <FloatLabel  variant="on" >
                <Select @change="selectTax" fluid id="tax" optionValue="id" :optionLabel="(item:TaxInterfaceI) => `${item.name } | ${item.rate}`" :options="taxes"  v-model="form.tax_id" />
                <label for="tax">Impuesto</label>
            </FloatLabel>
            <FloatLabel   variant="on" >
                <Select fluid id="unit" optionValue="id" optionLabel="name"  :options="propsW.units"  v-model="form.unit_id" />
                <label for="tax">Unidad</label>
            </FloatLabel>
            <FloatLabel   variant="on" >
                <InputNumber fluid id="weight" v-model="form.weight" />
                <label for="weight">Peso</label>
            </FloatLabel>
            <FloatLabel   variant="on" >
                <Select optionLabel="name" optionValue="id" fluid :options="propsW.branches"  id="branch" v-model="form.branch_id" />
                <label for="branch">Ramas</label>
            </FloatLabel>
            <FloatLabel   variant="on" >
                <InputText fluid  id="dimension" v-model="form.dimensions" />
                <label for="dimension">Dimensiones</label>
            </FloatLabel>
        </div>
    </Fieldset>
</template>
