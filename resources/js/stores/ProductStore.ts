import {defineStore} from "pinia";
import {ref} from "vue";
import {PreciseCalculator} from "@/utils/Decimal";


export const useProductStore = defineStore('products', ()=>{
    const taxRate = ref(0)
    const nextCode = ref<string | null>("")

    function setTaxRateFromPercent(percent: number){

        taxRate.value = Number(PreciseCalculator.divide(
            percent, 100
        ))
    }

    return {taxRate, setTaxRateFromPercent, nextCode}
})
