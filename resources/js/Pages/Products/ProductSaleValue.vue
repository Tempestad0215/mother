<script setup lang="ts">
import {inject, watch} from "vue";
import {PreciseCalculator} from "@/utils/Decimal";
import {Fieldset, InputNumber, FloatLabel, Select, DataTable, Column} from "primevue";
import {formProductKey} from "@/Injections/InjectionKeys";
import {useProductStore} from "@/stores/ProductStore";
import {storeToRefs} from "pinia";
import {WarehouseBaseI} from "@/Interfaces/WarehouseInterface";
import {PriceListWTI} from "@/Interfaces/PriceListInterface";


const propsW = defineProps<{
    warehouses: Array<WarehouseBaseI>
    priceLists: Array<PriceListWTI>
}>()

const form = inject(formProductKey)!!
const productStore = storeToRefs(useProductStore())

/*
Propiedades computada
 */
/**
 * Precio sin impuesto
 */

watch(
	() => [form.tax_uuid, form.cost, form.price, form.tax_uuid],
	()=>{

        const tax = PreciseCalculator.multiply(
            form.price,
            productStore.taxRate.value
        )

        if(Number(tax) === 0)
        {
            form.product_no_tax = form.price
        }else {
            form.product_no_tax = Number(PreciseCalculator.subtract(
                form.price, tax.toString()
            ))
        }

        form.benefits = Number(PreciseCalculator.subtract(
            form.price, form.cost
        ))

        const benefitsRate = PreciseCalculator.divide(
            Number(form.benefits), form.price || 1
        )

        form.benefits_rate = Number(
            Number(PreciseCalculator.multiply(
            String(benefitsRate), 100
            )).toFixed(2)
        )



	}
)



</script>

<template>

    <Fieldset legend="Detalle de Ventas">
        <div class=" mb-5 max-w-60">
            <FloatLabel variant="on"  >
                <Select
                    :options="propsW.warehouses"
                    option-label="name"
                    option-value="uuid"
                    fluid />
                <label for="warehouse">Almacen</label>
            </FloatLabel>
        </div>
        <div class="flex flex-col md:flex-row  gap-3">
            <FloatLabel variant="on" >
                <InputNumber currency="DOP" locale="en-US" :max-fraction-digits="2" fluid id="sale_cost"  v-model="form.cost" />
                <label for="sale_cost">Costo</label>
            </FloatLabel>
            <FloatLabel variant="on" >
                <InputNumber currency="DOP" locale="en-US" :max-fraction-digits="2" fluid id="sale_price" v-model="form.price" />
                <label for="sale_price">Precio</label>
            </FloatLabel>
            <FloatLabel variant="on" >
                <InputNumber :min="form.cost" currency="DOP" locale="en-US" :max-fraction-digits="2" fluid id="sale_min_price" v-model="form.min_price" />
                <label for="sale_min_price">Precio Minimo</label>
            </FloatLabel>
            <FloatLabel variant="on" >
                <InputNumber :min="form.min_price" currency="DOP" locale="en-US" :max-fraction-digits="2" fluid id="sale_special_price" v-model="form.special_price" />
                <label for="sale_special_price">Precio Especial</label>
            </FloatLabel>
        </div>
        <div class="flex flex-col md:flex-row justify-between mt-5">
            <p>
                <strong>Precio - Itbis</strong>
                <span class="inline-block px-3 rounded-md ml-3">{{ PreciseCalculator.formatCurrency(form.product_no_tax)  }}</span>
            </p>
            <p>
                <strong>Beneficio</strong>
                <span class="inline-block px-3 rounded-md ml-3">{{ PreciseCalculator.formatCurrency(form.benefits) }}</span>
            </p>
            <p>
                <strong>Beneficios Margen </strong>
                <span class="inline-block px-3 rounded-md ml-3">{{ form.benefits_rate }} %</span>
            </p>
        </div>
    </Fieldset>
</template>
