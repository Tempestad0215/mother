<script setup lang="ts">
import {Fieldset} from "primevue";
import {inject} from "vue";
import {formProductKey} from "@/Injections/InjectionKeys";
import {PreciseCalculator} from "@/utils/Decimal";

const form = inject(formProductKey)!!

</script>

<template>
    <Fieldset legend="Informacion de Ventas" >
        <div class="flex flex-col md:flex-row justify-between">
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
