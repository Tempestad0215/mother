<script setup lang="ts">
import { saleKey } from '@/utils/keys';
import { computed, inject, ref } from 'vue';
import { editFormI, infoSaleI, WarehouseMapType } from '@/Interfaces/SaleInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import { Column, DataTable, Dialog, Select } from 'primevue';
import { getMoney } from '@/Global/Helpers';
import { FilePenLine } from '@lucide/vue';
import SaleEditItem from '@/Pages/Sale/SaleEditItem.vue';

// Para eliminar un item de la venta
const propsW = defineProps<{
  refund?: boolean;
  warehouses?: WarehouseMapType;
}>();

// Para eliminar un item de la venta
const form = inject(saleKey)!;
const lastIndex = ref<number>(0);
const minIndex = ref<number>(0);
const showEdit = ref(false);
const formEditInfo = ref<editFormI>({
  price: 0,
  stock: 1,
  discount: 0,
});

// Obtener los almacenes para el select
const getWarehouses = computed(() => {
  if (propsW.warehouses) {
    return Object.entries(propsW.warehouses).map(([key, value]) => {
      return {
        name: key,
        value: value,
      };
    });
  } else {
    return [];
  }
});

// Para calcular los totales de la venta
const calculateTotals = () => {
  // 1) Totales base
  const subTotal = form.info_sale.reduce(
    (acc: number, currentValue: infoSaleI): number =>
      Number(PreciseCalculator.add(acc, currentValue.amount)),
    0
  );

  // 1.1) Totales de impuestos y descuentos
  const taxTotal = form.info_sale.reduce(
    (acc: number, currentValue: infoSaleI): number =>
      Number(PreciseCalculator.add(acc, currentValue.tax_amount)),
    0
  );

  // 1.2) Totales de descuentos
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

// Para eliminar un item de la venta
const calculateItemRow = (item: infoSaleI) => {
  if (!item.stock || !item.price) return;

  // Calcular el porcentaje de descuento
  const discountRate = PreciseCalculator.divide(item.discount || 0, 100);

  // Calcular Importe Bruto (Precio * Stock)
  item.amount = parseFloat(
    PreciseCalculator.multiply(item.price.toString(), item.stock.toString()).toFixed(2)
  );

  // Calcular monto deducido por el descuento
  item.discount_amount = parseFloat(
    PreciseCalculator.multiply(item.amount.toString(), discountRate.toString()).toFixed(2)
  );

  // Calcular ITBIS basado en el importe bruto
  item.tax_amount = parseFloat(
    PreciseCalculator.multiply(item.amount.toString(), (item.tax_rate || 0).toString()).toFixed(2)
  );

  // Recalcular los totales de la factura global
  calculateTotals();
};

// Tomar la info
const showEditInfo = () => {
  // Verificar si hay al menos un item en la venta
  if (form.info_sale.length <= 0) return;
  showEdit.value = true;
  // Tomar el ultimo indice
  const maxIndex = form.info_sale.length - 1;

  // Tomar los datos por el index
  const info = form.info_sale[maxIndex];
  // Para los datos para editar
  formEditInfo.value.price = info.price;
  formEditInfo.value.stock = info.stock;
  formEditInfo.value.discount = info.discount ?? 0;
};

// Exponer funciones al componente padre
defineExpose({
  // totalAmount,
  calculateItemRow,
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
    <Column header="Itbis" :field="(data: infoSaleI) => `${getMoney(data.tax_amount)}`" />
    <Column
      class="max-w-20"
      header="Descuento"
      :field="(data: infoSaleI) => `${getMoney(data.discount_amount)}`"
    />
    <Column class="max-w-20" header="Almacen">
      <template #body="{ index }">
        <Select
          :disabled="form.type === 'Devolucion'"
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
          v-if="form.info_sale.length > 0"
          @click="showEditInfo"
          v-tooltip.bottom="'Editar Item'"
          class="bg-green-300 p-1 rounded-md"
        >
          <FilePenLine :size="30" />
        </button>
      </div>
    </template>
  </DataTable>
  <Dialog v-model:visible="showEdit" modal>
    <SaleEditItem
      @calculate-totals="calculateTotals"
      @calculate-item-row="calculateItemRow"
      v-model:editItemForm="formEditInfo"
      v-model:lastIndex="lastIndex"
      v-model:minIndex="minIndex"
    />
  </Dialog>
</template>
