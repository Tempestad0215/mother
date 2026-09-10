<script setup lang="ts">
import { saleKey } from '@/utils/keys';
import { inject } from 'vue';
import { infoSaleI, WarehouseMapType } from '@/Interfaces/SaleInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import { Column, DataTable, DataTableCellEditCompleteEvent, InputNumber, Select } from 'primevue';
import { getMoney, truncateText } from '@/Global/Helpers';
import { EnumValueI } from '@/Interfaces/GeneralInterface';

// Para eliminar un item de la venta
const propsW = defineProps<{
  refund?: boolean;
  warehouses?: WarehouseMapType;
}>();

// Para eliminar un item de la venta
const form = inject(saleKey)!;

// Obtener los almacenes para el select
const getWarehouses = (): EnumValueI[] | [] => {
  if (propsW.warehouses) {
    return Object.entries(propsW.warehouses).map(([key, value]) => {
      return {
        label: key,
        value: value,
      };
    });
  } else {
    return [];
  }
};

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

// Exponer funciones al componente padre
defineExpose({
  // totalAmount,
  calculateItemRow,
  calculateTotals,
});

const onCellEditComplete = (event: DataTableCellEditCompleteEvent) => {
  const index = event.index;
  const info = event.newData;
  calculateItemRow(info);
  Object.assign(form.info_sale[index], info);
};

const getLabelName = (uuid: string): string => {
  const warehouseName = getWarehouses().find((el) => el.value === uuid);
  if (warehouseName) return warehouseName.label;
  return 'Almacen no encontrado';
};
</script>

<template>
  <DataTable @cellEditComplete="onCellEditComplete" editMode="cell" :value="form.info_sale">
    <Column style="width: 2%" header="#">
      <template #body="{ index }">
        {{ index + 1 }}
      </template>
    </Column>
    <Column header="Producto/Servicio">
      <template #body="{ data }: { data: infoSaleI }">
        <p>{{ data.code }}</p>
        <p>{{ truncateText(data.product_name) }}</p>
      </template>
    </Column>
    <Column
      style="width: 10%"
      class="max-w-15.9"
      header="Cantidad"
      :field="(data: infoSaleI) => `${getMoney(data.stock)}`"
    >
      <template #body="{ data }: { data: infoSaleI }">
        {{ data.stock }}
      </template>
      <template #editor="{ data }: { data: infoSaleI }">
        <InputNumber :min-fraction-digits="2" :max-fraction-digits="2" fluid v-model="data.stock" />
      </template>
    </Column>
    <Column
      style="width: 15%"
      header="Precio"
      :field="(data: infoSaleI) => `${getMoney(data.price)}`"
    >
      <template #body="{ data }: { data: infoSaleI }">
        {{ getMoney(data.price) }}
      </template>
      <template #editor="{ data }: { data: infoSaleI }">
        <InputNumber fluid :minFractionDigits="2" :maxFractionDigits="2" v-model="data.price" />
      </template>
    </Column>
    <Column header="Itbis" :field="(data: infoSaleI) => `${getMoney(data.tax_amount)}`" />
    <Column
      style="width: 10%"
      header="Descuento"
      :field="(data: infoSaleI) => `${getMoney(data.discount_amount)}`"
    >
      <template #body="{ data }: { data: infoSaleI }">
        {{ data.discount }}
      </template>
      <template #editor="{ data }: { data: infoSaleI }">
        <InputNumber
          :max-fraction-digits="2"
          :min-fraction-digits="2"
          fluid
          v-model="data.discount"
        />
      </template>
    </Column>
    <Column style="width: 15%" class="" header="Almacen">
      <template #body="{ data }: { data: infoSaleI }">
        {{ getLabelName(data.warehouse_uuid) }}
      </template>
      <template #editor="{ data, index }: { data: infoSaleI; index: number }">
        <Select
          :disabled="form.type === 'Devolucion'"
          v-model="form.info_sale[index].warehouse_uuid"
          :options="getWarehouses()"
          optionLabel="label"
          optionValue="value"
        />
      </template>
    </Column>
    <Column
      style="width: 12%"
      header="Importe"
      :field="(data: infoSaleI) => `${getMoney(data.amount)}`"
    />
    <!--    <template #footer>-->
    <!--      <div class="text-center">-->
    <!--        <button-->
    <!--          type="button"-->
    <!--          v-if="form.info_sale.length > 0"-->
    <!--          @click="showEditInfo"-->
    <!--          v-tooltip.bottom="'Editar Item'"-->
    <!--          class="bg-green-300 p-1 rounded-md"-->
    <!--        >-->
    <!--          <FilePenLine :size="30" />-->
    <!--        </button>-->
    <!--      </div>-->
    <!--    </template>-->
  </DataTable>
  <!--  <Dialog v-model:visible="showEdit" modal>-->
  <!--    <SaleEditItem-->
  <!--      @calculate-totals="calculateTotals"-->
  <!--      @calculate-item-row="calculateItemRow"-->
  <!--      v-model:editItemForm="formEditInfo"-->
  <!--      v-model:lastIndex="lastIndex"-->
  <!--      v-model:minIndex="minIndex"-->
  <!--    />-->
  <!--  </Dialog>-->
</template>
