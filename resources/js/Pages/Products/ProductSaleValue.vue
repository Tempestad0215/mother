<script setup lang="ts">

import {moneyConfig} from "@/Global/Helpers";
import {Money} from "v-money3";
import InputLabel from "@components/InputLabel.vue";
import {computed} from "vue";
import {PreciseCalculator} from "@/utils/Decimal";

interface propsW {
    taxRate: number
}


const propsW = withDefaults(defineProps<propsW>(), {
    taxRate: 0
})

const emit = defineEmits<{
    (e: 'calculate',
     productNoTax: string,
     benefits: string,
     benefitsMargin: string): void
}>()


const cost = defineModel<number>('cost', {
    default: 0
})
const price = defineModel<number>('price', {
    default: 0
})
const min_price = defineModel<number>('min_price', {
    default: 0
})
const special_price = defineModel<number>('special_price', {
    default: 0
})


/*
Propiedades computada
 */
/**
 * Precio sin impuesto
 */

const total = computed(() => {
    const productNoTax = PreciseCalculator.subtract(
        cost.value, propsW.taxRate
    )

    const benefits = PreciseCalculator.subtract(
        price.value, cost.value
    )

    const benefitsMargin = PreciseCalculator.divide(
        Number(benefits), cost.value
    )

    emit('calculate', productNoTax.toString(), benefits.toString(), benefitsMargin.toString())

    return {
        productNoTax,
        benefits,
        benefitsMargin,
    }
})


</script>

<template>
    <fieldset class="field grid grid-cols-4 gap-3">
        <legend>Datos de Ventas</legend>
        <div>
            <InputLabel
                class="inline ml-2"
                for="sale_cost"
                value="Costo"/>
            <Money
                class="inputGeneral w-full"
                v-bind="moneyConfig"
                v-model.number="cost"/>
        </div>
        <!--                        Informacion de venta-->
        <div>
            <InputLabel
                class="inline ml-2"
                for="sale_price"
                value="Precio"/>
            <Money
                class="inputGeneral w-full"
                v-bind="moneyConfig"
                v-model.number="price"/>
        </div>
        <div>
            <InputLabel
                class="inline ml-2"
                for="sale_cost"
                value="Pre. Minimo"/>
            <Money
                class="inputGeneral w-full"
                v-bind="moneyConfig"
                v-model.number="min_price"/>
        </div>
        <!--                        Informacion de venta-->
        <div>
            <InputLabel
                class="inline ml-2"
                for="sale_price"
                value="Pre. Especial"/>
            <Money
                class="inputGeneral w-full"
                v-bind="moneyConfig"
                v-model.number="special_price"/>
        </div>
    </fieldset>
</template>

<style scoped>

</style>
