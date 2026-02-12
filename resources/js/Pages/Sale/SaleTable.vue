<script setup lang="ts">
import {saleKey} from "@/utils/keys";
import {computed, inject, ref} from "vue";
import {infoSaleI} from "@/Interfaces/SaleInterface";
import {PreciseCalculator} from "@/utils/Decimal";
import {useRoute} from "ziggy-js";
import {DataTable, Column} from "primevue";
import {InputNumber, Button, Dialog, FloatLabel, Checkbox, RadioButton} from "primevue";
import {getMoney} from "@/Global/Helpers";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faArrowAltCircleDown, faArrowAltCircleUp} from "@fortawesome/free-solid-svg-icons";



const route = useRoute();
const propsW = defineProps<{
	refund?: boolean;
}>()

const form = inject(saleKey)!;
const lastIndex = ref<number>(0);
const showEdit = ref(false);
const typePrice  = ref(1)
const minIndex = computed(() => 0)
const maxIndex = computed(() =>
    form.info_sale.length > 0 ? form.info_sale.length - 1 : 0
)

/**
 * Calcular el itbis y otros datos de la ventana
 * @param index
 */

const deletedItem = (index: number) =>{
    if (form.info_sale[index].stock === 0)
    {

        form.info_sale.splice(index, 1);

        if (form.info_sale.length === 0)
        {
            showEdit.value = false;
        }
        return;
    }
}

function totalAmount (index: number) {

    if(index < 0 || index >= form.info_sale.length ) return;

    setTimeout(()=>{
        deletedItem(index);
    }, 150);


	// Sacar los datos del produtos
	let info: infoSaleI = form.info_sale[index];
    if(!info) return;

	let discountRate = PreciseCalculator.divide((info.discount || 0), 100)

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
		return Number(PreciseCalculator.add(discount, (item.discount_amount || 0)).toString())
	}, 0);
	form.amount = Number(PreciseCalculator.subtract(form.sub_total, form.discount_amount).toFixed(2))



}

defineExpose({
	totalAmount,
	totalSale

})

const getLastIndex = () => {
    if (form.info_sale.length <= 0) return;

    lastIndex.value = form.info_sale.length - 1;
    showEdit.value = true;
}

type MoveDirection = 'up' | 'down';
const moveEdit = (direction: MoveDirection) => {
    const current = lastIndex.value

    if (direction === 'up') {
        // no bajar de 0
        if (current <= minIndex.value) return
        lastIndex.value = current - 1
    }

    if (direction === 'down') {
        // no subir del último índice
        if (current >= maxIndex.value) return
        lastIndex.value = current + 1
    }
}

const changePrice = () =>{
    if (form.info_sale.length <= 0) return;
    const idx = lastIndex.value;
    // proteger índice inválido
    if (idx < 0 || idx >= form.info_sale.length) return;

    const item = form.info_sale[idx];

    switch (typePrice.value) {
        case 1:
            // precio normal (ya está en item.price)
            // si quisieras usar un campo original:
            // item.price = item.base_price ?? item.price;
            item.price_temp = item.price ?? 0;
            break;

        case 2:
            // precio mínimo
            item.price_temp = item.min_price ?? 0;
            break;

        case 3:
            // precio especial
            item.price_temp = item.special_price ?? 0;
            break;

        default:
            // opcional: si no hay tipo válido, no hacer nada
            return;
    }

    totalAmount(idx);
}

</script>

<template>
    <DataTable :value="form.info_sale">
        <Column header="#">
            <template #body="{index}">
                {{index+1}}
            </template>
        </Column>
        <Column header="Producto/Servicio" field="product_name" />
        <Column
            class="max-w-20"
            header="Cantidad"
            :field="(data:infoSaleI) => `${getMoney(data.stock)}`" />
        <Column
            class="max-w-20"
            header="Precio"
            :field="(data:infoSaleI) => `${getMoney(data.price)}`"/>
        <Column
            header="Itbis"
            :field="(data:infoSaleI) => `${getMoney(data.tax_rate)}`" />
        <Column
            class="max-w-20"
            header="Descuento"
            :field="(data:infoSaleI) => `${getMoney(data.tax_rate)}`" />
        <Column
            header="Importe"
            :field="(data:infoSaleI) => `${getMoney(data.amount)}`" />
        <template #footer>
            <div class="text-center">
                <Button v-if="form.info_sale.length > 0" title="Editar" @click="getLastIndex" icon="pi pi-pencil"  />
            </div>
        </template>
    </DataTable>
    <Dialog
        v-model:visible="showEdit"
        modal>
        <div class="flex flex-col gap-5 items-center">
            <div v-if="form.info_sale.length > 0" class="text-2xl font-bold">
                Editanto el Item : {{form.info_sale[lastIndex].product_name}}, es un: {{form.info_sale[lastIndex].is_service ? 'Servicios' : 'Producto'}}
            </div>
            <div class="flex gap-5">
                <div class="flex flex-col gap-5 mt-5">
                    <div class="flex gap-5">
                        <div class="flex items-center gap-2">
                            <RadioButton @change="changePrice"  v-model="typePrice" inputId="normal_price" name="normal_price" :value="1" />
                            <label for="normal_price"> Precio Normal </label>
                        </div>
                        <div class="flex items-center gap-2">
                            <RadioButton @change="changePrice" v-model="typePrice" inputId="special_price" name="special_price" :value="2" />
                            <label for="special_price"> Precio Especial </label>
                        </div>
                        <div class="flex items-center gap-2">
                            <RadioButton @change="changePrice" v-model="typePrice" inputId="min_price" name="min_price" :value="3" />
                            <label for="min_price"> Precio Minimo </label>
                        </div>
                    </div>
                    <div class="flex gap-5">
                        <FloatLabel variant="on">
                            <InputNumber @blur="totalAmount(lastIndex)" v-model="form.info_sale[lastIndex].stock" />
                            <label for="stock">Cantidad</label>
                        </FloatLabel>
                        <FloatLabel variant="on">
                            <InputNumber :readonly="form.info_sale[lastIndex].is_service" v-model="form.info_sale[lastIndex].price_temp" />
                            <label for="price">Precio</label>
                        </FloatLabel>
                        <FloatLabel variant="on">
                            <InputNumber v-model="form.info_sale[lastIndex].discount" />
                            <label for="discount">Descuento</label>
                        </FloatLabel>
                    </div>

                </div>
                <div class="mt-5 text-3xl space-x-3">
                    <FontAwesomeIcon v-if="lastIndex > minIndex" @click="moveEdit('up')" :icon="faArrowAltCircleUp" />
                    <FontAwesomeIcon v-if="lastIndex < maxIndex"  @click="moveEdit('down')" :icon="faArrowAltCircleDown" />
                </div>
            </div>

        </div>
    </Dialog>
</template>
