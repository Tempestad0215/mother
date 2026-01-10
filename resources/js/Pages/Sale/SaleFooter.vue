<script setup lang="ts">
import InputError from "@components/InputError.vue";
import {getMoney} from "@/Global/Helpers";
import {saleKey} from "@/utils/keys";
import {inject} from "vue";

const form = inject(saleKey)!;
</script>

<template>
	<!--                        Dato de la ventas-->
	<div>
		<!--                            Mensaje generales-->
		<div>
			<InputError :message="form.errors.general"/>
		</div>
		
		<div>
			<InputError :message="form.errors.info_sale"/>
		</div>
		
		
		<!--                            Comentario de la venta-->
		<div class="grid grid-cols-4 items-center gap-4">
			<textarea
				placeholder="Comentario"
				v-model.trim="form.comment"
				cols="60"
				class="area col-span-2">
            </textarea>
			<div class=" col-end-7 col-span-2 text-white">
				<table>
					<tbody>
					<tr>
						<th class="text-left">Itbis :</th>
						<td>{{ getMoney(form.tax) }}</td>
					</tr>
					<tr>
						<th class="text-left">Sub Total :</th>
						<td>{{ getMoney(form.sub_total) }}</td>
					</tr>
					<tr>
						<th class="text-left">Decuento :</th>
						<td>{{ getMoney(form.discount_amount) }}</td>
					</tr>
					<tr>
						<th class="text-left">Total :</th>
						<td class="w-[15rem]">{{ getMoney(form.amount) }}</td>
					</tr>
					</tbody>
				</table>
			</div>
		
		</div>
	</div>
</template>

<style scoped>

</style>