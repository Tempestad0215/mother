<script setup lang="ts">
import {getMoney} from "@/Global/Helpers";
import {saleKey} from "@/utils/keys";
import {inject} from "vue";
import {Textarea, FloatLabel} from "primevue";



const form = inject(saleKey)!;
</script>

<template>
	<!--                        Dato de la ventas-->
	<div class="flex items-center justify-between">
        <div class="flex-1">
            <FloatLabel variant="on" >
                <Textarea v-model.trim="form.comment" />
                <label for="comment">Comentario</label>
            </FloatLabel>

        </div>
		<!--                            Mensaje generales-->
        <div class="">
            <p>Descuento : {{getMoney(form.discount_amount)}}</p>
            <p>Itbis : {{getMoney(form.tax)}}</p>
            <p>Sub Total : {{getMoney(form.sub_total)}}</p>
            <p>Total : {{getMoney(form.amount)}}</p>
        </div>
        <div class="clear-both"></div>

	</div>
</template>

<style scoped>

</style>
