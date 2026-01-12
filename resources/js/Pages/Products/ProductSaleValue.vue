<script setup lang="ts">
import {inject, watch} from "vue";
import {PreciseCalculator} from "@/utils/Decimal";
import {Fieldset, InputNumber, FloatLabel} from "primevue";
import {formProductKey} from "@/Injections/InjectionKeys";
import {useProductStore} from "@/stores/ProductStore";
import {storeToRefs} from "pinia";


const form = inject(formProductKey)!!
const productStore = storeToRefs(useProductStore())

/*
Propiedades computada
 */
/**
 * Precio sin impuesto
 */

watch(
	() => [form.tax_id, form.cost, form.price, form.tax_id],
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
    <Fieldset legend="Datos de Ventas">
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
    </Fieldset>
</template>
