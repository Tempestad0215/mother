import {defineStore} from "pinia";
import {ref} from "vue";
import {PreciseCalculator} from "@/utils/Decimal";


export const useProductStore = defineStore('products', ()=>{
    const taxRate = ref(0)


    function setTaxRateFromPercent(percent: number){

        taxRate.value = Number(PreciseCalculator.divide(
            percent, 100
        ))
    }

    return {taxRate, setTaxRateFromPercent}
})
