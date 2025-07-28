<script setup lang="ts">
import {getMoney, moneyConfig} from "@/Global/Helpers";
import {Money} from "v-money3";
import {saleKey} from "@/utils/keys";
import {inject} from "vue";
import {infoSaleI} from "@/Interfaces/SaleInterface";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";
import {PreciseCalculator} from "@/utils/Decimal";

const propsW = defineProps<{
	refund?: boolean;
}>()

const form = inject(saleKey)!;


/**
 * Calcular el itbis y otros datos de la ventana
 * @param index
 */
function totalAmount (index: number) {
	
	// Sacar los datos del produtos
	let info: infoSaleI = form.info_sale[index];
	let discountRate = PreciseCalculator.divide(info.discount, 100)
	
	//Para calcular los datos
	info.amount = parseFloat((info.price * info.stock).toFixed(2));
	//Descuento datos
	info.discount_amount = parseFloat((PreciseCalculator.multiply(info.amount, discountRate.toString())).toFixed(2));
	//Pasar los datos al formulario
	info.tax = parseFloat((PreciseCalculator.multiply(info.amount, info.tax_rate)).toFixed(2));
	
	//Calcular los totales
	totalSale();
	
}

/**
 * Eliminar datos de la venta
 * @param name
 * @param index
 */
async function deleteItem  (name: string, index: number){
	
	//Tomar el resultado si vas a eliminar
	const result = await Swal.fire({
		title: `Desea eliminar registro : ${name}?`,
		text: "Los cambios realizados son irreversible!",
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Si, Eliminar!",
		cancelButtonText: "Cancelar"
	});
	
	//Verificar si se ha confirmado
	if (result.isConfirmed) {
		//Tomar datos la venta
		let info: infoSaleI = form.info_sale[index];
		
		
		//Eliminar el producto seleccionado
		form.info_sale.splice(index, 1);
		
		//Verificar si es diferente a devuelta
		if (!propsW.refund) {
			
			if (form.id !== 0) {
				//Enviar los datos para actualizar
				form.transform((data) => ({
					...data,
					info: info,
					info_new: data.info_sale,
				})).patch(route('sale.destroy.item', {product: info.product_id, sale: form.id}, {
					preserveScroll: true,
					preserveState: true,
					onFinish: () => {
					},
					onSuccess: () => {
						successHttp(`Item : ${info.product_name} Eliminado Correctamente`);
					}
				}));
			}
		}
		//REalizar el cálculo de nuevo
		// totalSale();
	}
}




/**
 * Calculo el total de venta
 */
// Calculo de los datos finales
function totalSale() {
	
	//Calcular el total
	form.tax = form.info_sale.reduce((tax: number, item: infoSaleI) => {
		return Number(PreciseCalculator.add(tax, item.tax).toFixed(2))
	},0);
	form.sub_total = form.info_sale.reduce((subTotal: number, item: infoSaleI) => {
		return Number(PreciseCalculator.add(subTotal, item.amount).toFixed(2))
	}, 0);
	form.discount_amount = form.info_sale.reduce(( discount, item: infoSaleI) => {
		return Number(PreciseCalculator.add(discount, item.discount_amount).toString())
	}, 0);
	form.amount = Number(PreciseCalculator.subtract(form.sub_total, form.discount_amount).toFixed(2))
	
	
	
}

defineExpose({
	totalAmount,
	totalSale
	
})

</script>

<template>
	<!--                        Listado de los productos-->
	<div
		class="max-h-[400px] border-t-2 mt-3 border-black overflow-y-auto shadow-lg p-3 rounded-md">
		<table class="styleTable w-full">
			<thead>
			<tr>
				<th>#</th>
				<th>Producto/Servicio</th>
				<th>Cantidad</th>
				<th>Itbis</th>
				<th>Precio</th>
				<th>Desc.</th>
				<th>Importe</th>
				<th>Act</th>
			</tr>
			</thead>
			<tbody>
			<tr
				v-for="(item, index) in form.info_sale" :key="index">
				<td>
					{{ index + 1 }}
				</td>
				<td>
					{{ item.product_name }}
				</td>
				<td class="max-w-[5rem]">
					<Money
						class=" bg-transparent h-[2rem] max-w-[6rem] rounded-md border-none"
						@blur="totalAmount(index)"
						v-bind="moneyConfig"
						v-model.number="item.stock"/>
				</td>
				<td>
					{{ getMoney(item.tax) }}
				</td>
				
				<!--                                        Precio solo modificar si es servicio-->
				<td class="max-w-[5rem]">
					<span v-if="item.type === 'producto' || item.type === 'ventas'">{{ getMoney(item.price) }}
					</span>
					<Money
						class=" bg-transparent h-[2rem] max-w-[6rem] rounded-md border-none"
						v-if="item.type === 'servicio'"
						@blur="totalAmount(index)"
						v-bind="moneyConfig"
						v-model.number="item.price"/>
				</td>
				<td class="max-w-[4rem]">
					<Money
						class=" bg-transparent h-[2rem] max-w-[5rem] rounded-md border-none"
						@blur="totalAmount(index)"
						v-bind="moneyConfig"
						:min="0"
						:max="100"
						v-model.number="item.discount"/>
				</td>
				<td>
					{{ getMoney(item.amount) }}
				</td>
				
				<td>
					<i
						@click="deleteItem(item.product_name, index)"
						class=" icon-efect text-red-500 fa-solid fa-circle-xmark"></i>
				</td>
			</tr>
			</tbody>
		</table>
	
	</div>
</template>

<style scoped>

</style>