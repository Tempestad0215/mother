<script setup lang="ts">
import { saleKey } from '@/utils/keys';
import { computed, inject, reactive, ref, watch } from 'vue';
import { infoSaleI, WarehouseMapType } from '@/Interfaces/SaleInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import {
  Button,
  Column,
  DataTable,
  Dialog,
  FloatLabel,
  InputNumber,
  RadioButton,
  Select,
} from 'primevue';
import { getMoney } from '@/Global/Helpers';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faArrowAltCircleDown, faArrowAltCircleUp } from '@fortawesome/free-solid-svg-icons';
import { FilePenLine } from '@lucide/vue';

/**
 *
 */
const propsW = defineProps<{
  refund?: boolean;
  warehouses?: WarehouseMapType;
}>();

/**
 *
 */
const form = inject(saleKey)!;
const lastIndex = ref<number>(0);
const showEdit = ref(false);
const typePrice = ref(1);
const minIndex = computed(() => 0);
const maxIndex = computed(() => (form.info_sale.length > 0 ? form.info_sale.length - 1 : 0));

/**
 *
 */
interface editFormI {
  stock: number;
  price: number;
  discount: number;
}

/**
 *
 */
const editItemForm = reactive<editFormI>({
  stock: 0,
  price: 0,
  discount: 0,
});

/**
 *
 */
const minPrice = computed((): number => {
  if (!checkIndex()) return 0;
  return Number(form.info_sale[lastIndex.value].min_price);
});
/**
 *
 */
const productEditingName = computed((): string => {
  if (!checkIndex()) return '';
  return form.info_sale[lastIndex.value].product_name;
});
/**
 *
 */
const productIsService = computed(() => {
  const item = form.info_sale[lastIndex.value];
  return !!item?.is_service;
});

const getWarehouses = computed(() => {
  if (propsW.warehouses) {
    return Object.entries(propsW.warehouses).map(([key, value]) => {
      console.log(value);
      return {
        name: key,
        value: value,
      };
    });
  } else {
    return [];
  }
});

watch(
  () => editItemForm,
  (newVal) => {
    if (!checkIndex()) return;
    const item = form.info_sale[lastIndex.value];
    item.price = newVal.price;
    item.stock = newVal.stock;
    item.discount = newVal.discount;
  },
  { deep: true }
);

const checkIndex = (): boolean => {
  return !(lastIndex.value < minIndex.value || lastIndex.value > maxIndex.value);
};

const deletedItem = (index: number) => {
  if (form.info_sale[index].stock === 0) {
    form.info_sale.splice(index, 1);
    calculateTotals();

    if (form.info_sale.length === 0) {
      showEdit.value = false;
    }
    return;
  }
};

const getLastIndex = () => {
  if (form.info_sale.length <= 0) return;

  lastIndex.value = form.info_sale.length - 1;
  showEdit.value = true;

  // asingDataToEditItemForm()
  Object.assign(editItemForm, form.info_sale[lastIndex.value]);
};

type MoveDirection = 'up' | 'down';

/**
 *
 * @param direction
 */
const moveEdit = (direction: MoveDirection) => {
  const current = lastIndex.value;

  if (direction === 'up') {
    // no bajar de 0
    if (current <= minIndex.value) return;
    lastIndex.value = current - 1;
  }

  if (direction === 'down') {
    // no subir del último índice
    if (current >= maxIndex.value) return;
    lastIndex.value = current + 1;
  }
  Object.assign(editItemForm, form.info_sale[lastIndex.value]);
};

/**
 *
 */
const calculateTotals = () => {
  // 1) Totales base
  const subTotal = form.info_sale.reduce(
    (acc: number, currentValue: infoSaleI): number =>
      Number(PreciseCalculator.add(acc, currentValue.amount)),
    0
  );

  const taxTotal = form.info_sale.reduce(
    (acc: number, currentValue: infoSaleI): number =>
      Number(PreciseCalculator.add(acc, currentValue.tax_rate)),
    0
  );

  const discountTotal = form.info_sale.reduce(
    (acc: number, currentValue: infoSaleI): number =>
      Number(PreciseCalculator.add(acc, currentValue.discount_amount || 0)),
    0
  );

  // 2) Subtotal sin impuestos (si ese es tu concepto)
  form.sub_total = Number(PreciseCalculator.subtract(subTotal, taxTotal));

  // 3) Guardar tax y descuento
  form.tax = taxTotal;
  form.discount_amount = discountTotal;

  // 4) Total final
  const subTotalNoTax = Number(PreciseCalculator.add(form.sub_total, taxTotal));

  form.amount = Number(PreciseCalculator.subtract(subTotalNoTax, discountTotal));
};

/**
 *
 * @param index
 */
const totalAmount = (index: number) => {
  if (index < 0 || index >= form.info_sale.length) return;

  setTimeout(() => {
    deletedItem(index);
  }, 150);

  // Sacar los datos del produtos
  if (!checkIndex()) return;
  let info: infoSaleI = form.info_sale[index];

  let discountRate = PreciseCalculator.divide(info.discount || 0, 100);

  //Para calcular los datos
  info.amount = parseFloat((info.price * info.stock).toFixed(2));
  //Descuento datos
  info.discount_amount = parseFloat(
    PreciseCalculator.multiply(info.amount, discountRate.toString()).toFixed(2)
  );
  //Pasar los datos al formulario
  info.tax_amount = parseFloat(PreciseCalculator.multiply(info.amount, info.tax_rate).toFixed(2));

  //Calcular los totales
  calculateTotals();
};

/**
 *
 */
const changePrice = () => {
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
      item.price_temp = item.special_price ?? 0;
      break;

    case 3:
      // precio especial
      item.price_temp = item.min_price ?? 0;
      break;

    default:
      // opcional: si no hay tipo válido, no hacer nada
      return;
  }

  totalAmount(idx);
};

defineExpose({
  totalAmount,
  calculateTotals,
});
</script>

<template>
  <DataTable :value="form.info_sale">
    <Column header="#">
      <template #body="{ index }">
        {{ index + 1 }}
      </template>
    </Column>
    <Column header="Producto/Servicio" field="product_name" />
    <Column
      class="max-w-20"
      header="Cantidad"
      :field="(data: infoSaleI) => `${getMoney(data.stock)}`"
    />
    <Column
      class="max-w-20"
      header="Precio"
      :field="(data: infoSaleI) => `${getMoney(data.price)}`"
    />
    <Column header="Itbis" :field="(data: infoSaleI) => `${data.tax_amount}`" />
    <Column
      class="max-w-20"
      header="Descuento"
      :field="(data: infoSaleI) => `${getMoney(data.discount_amount)}`"
    />
    <Column class="max-w-20" header="Almacen">
      <template #body="{ index }">
        <Select
          v-model="form.info_sale[index].warehouse_uuid"
          :options="getWarehouses"
          optionLabel="name"
          optionValue="value"
        />
      </template>
    </Column>
    <Column header="Importe" :field="(data: infoSaleI) => `${getMoney(data.amount)}`" />
    <template #footer>
      <div class="text-center">
        <button
          type="button"
          @click="getLastIndex"
          v-if="form.info_sale.length > 0"
          v-tooltip.bottom="'Editar Item'"
          class="bg-green-300 p-1 rounded-md"
        >
          <FilePenLine :size="30" />
        </button>
      </div>
    </template>
  </DataTable>
  <Dialog v-model:visible="showEdit" modal>
    <div class="flex flex-col gap-5 items-center">
      <div v-if="form.info_sale.length > 0" class="text-2xl font-bold">
        Editando el Item : {{ productEditingName }}, es un:
        {{ productIsService ? 'Servicios' : 'Producto' }}
      </div>
      <div class="flex gap-5">
        <div class="flex flex-col gap-5 mt-5">
          <div class="flex gap-5">
            <div class="flex items-center gap-2">
              <RadioButton
                @change="changePrice"
                v-model="typePrice"
                inputId="normal_price"
                name="normal_price"
                :value="1"
              />
              <label for="normal_price"> Precio Normal </label>
            </div>
            <div class="flex items-center gap-2">
              <RadioButton
                @change="changePrice"
                v-model="typePrice"
                inputId="special_price"
                name="special_price"
                :value="2"
              />
              <label for="special_price"> Precio Especial </label>
            </div>
            <div class="flex items-center gap-2">
              <RadioButton
                @change="changePrice"
                v-model="typePrice"
                inputId="min_price"
                name="min_price"
                :value="3"
              />
              <label for="min_price"> Precio Minimo </label>
            </div>
          </div>
          <div class="flex gap-5">
            <FloatLabel variant="on">
              <InputNumber @blur="totalAmount(lastIndex)" v-model="editItemForm.stock" />
              <label for="stock">Cantidad</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <InputNumber
                :min="minPrice"
                :readonly="productIsService"
                v-model="editItemForm.price"
              />
              <label for="price">Precio</label>
            </FloatLabel>
            <FloatLabel variant="on">
              <InputNumber v-model="editItemForm.discount" />
              <label for="discount">Descuento</label>
            </FloatLabel>
          </div>
        </div>
        <div class="mt-5 text-3xl space-x-3">
          <FontAwesomeIcon @click="moveEdit('up')" :icon="faArrowAltCircleUp" />
          <FontAwesomeIcon @click="moveEdit('down')" :icon="faArrowAltCircleDown" />
        </div>
      </div>
    </div>
  </Dialog>
</template>
