<script setup lang="ts">
import {supplierI} from "@/Interfaces/Supplier";
import {getMoney} from "@/Global/Helpers";

/*
Propiedades
 */
const propsW = defineProps<{
    supplier: supplierI | null
}>();

</script>

<template>
    <div class="bg-blue-300 max-w-[70rem] rounded-md p-5">
        <fieldset class="field">
            <legend>Info General</legend>
            <div>
                <p>Nombre</p>
                <span class="span-white">{{propsW.supplier?.company_name}}</span>
            </div>
            <div>
                <p>Contacto</p>
                <span class="span-white">{{propsW.supplier?.contact}}</span>
            </div>
            <div>
                <p>Teléfono</p>
                <span class="span-white">{{propsW.supplier?.phone}}</span>
            </div>
            <div>
                <p>Correo</p>
                <span class="span-white">{{propsW.supplier?.email}}</span>
            </div>
        </fieldset>

        <fieldset class="field">
            <legend>Info Pago</legend>
            <div>
                <p>Tipo de Pago</p>
                <span class="span-white">{{propsW.supplier?.payment?.name}}</span>
            </div>
            <div>
                <p>Limite</p>
                <span class="span-white">{{ getMoney(propsW.supplier?.account?.amount) }}</span>
            </div>
            <div>
                <p>Plazo en Dias</p>
                <span class="span-white">{{ propsW.supplier?.account?.due_date ?? 0 }}</span>
            </div>
            <div>
                <p>Balance</p>
                <span class="span-white">{{ getMoney(propsW.supplier?.account?.balance) }}</span>
            </div>
            <div>
                <p>Consumido</p>
                <span class="span-white">{{ getMoney(propsW.supplier?.account?.consumed) }}</span>
            </div>
            <div>
                <p>Interes Por Mora</p>
                <span class="span-white">{{ propsW.supplier?.account?.late_fee ?? 0 }} %</span>
            </div>
        </fieldset>
    </div>
</template>
