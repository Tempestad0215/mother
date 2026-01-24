<script setup lang="ts">
import {getMoney, moneyConfig} from "@/Global/Helpers";
import {Money} from "v-money3";
import {saleKey} from "@/utils/keys";
import {inject} from "vue";
import {infoSaleI} from "@/Interfaces/SaleInterface";
import {PreciseCalculator} from "@/utils/Decimal";
import {useRoute} from "ziggy-js";
import {DataTable, Column} from "primevue";



const route = useRoute();
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
	// const result = await Swal.fire({
	// 	title: `Desea eliminar registro : ${name}?`,
	// 	text: "Los cambios realizados son irreversible!",
	// 	icon: "warning",
	// 	showCancelButton: true,
	// 	confirmButtonColor: "#3085d6",
	// 	cancelButtonColor: "#d33",
	// 	confirmButtonText: "Si, Eliminar!",
	// 	cancelButtonText: "Cancelar"
	// });

	// //Verificar si se ha confirmado
	// if (result.isConfirmed) {
	// 	//Tomar datos la venta
	// 	// let info: infoSaleI = form.info_sale[index];
    //
    //
	// 	//Eliminar el producto seleccionado
	// 	form.info_sale.splice(index, 1);
    //
	// 	//Verificar si es diferente a devuelta
	// 	if (!propsW.refund) {
    //
	// 		if (form.id !== 0) {
	// 			//Enviar los datos para actualizar
	// 			// form.transform((data) => ({
	// 			// 	...data,
	// 			// 	info: info,
	// 			// 	info_new: data.info_sale,
	// 			// })).patch(route('sale.destroy.item', {product: info.product_id, sale: form.id}, {
	// 			// 	preserveScroll: true,
	// 			// 	preserveState: true,
	// 			// 	onFinish: () => {
	// 			// 	},
	// 			// 	onSuccess: () => {
	// 			// 		successHttp(`Item : ${info.product_name} Eliminado Correctamente`);
	// 			// 	}
	// 			// }));
	// 		}
	// 	}
	// 	//REalizar el cálculo de nuevo
	// 	// totalSale();
	// }
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
    <DataTable :value="form.info_sale">
        <Column header="#">
            <template #body="{index}">
                {{index+1}}
            </template>
        </Column>
        <Column header="Producto/Servicio" field="product_name" />
        <Column header="Cantidad" />
        <Column header="Precio" />
        <Column header="Itbis" />
        <Column header="Descuento" />
        <Column header="Importe" />
        <Column header="Act" />
    </DataTable>

</template>
\\
