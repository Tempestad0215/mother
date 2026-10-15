<script setup lang="ts">
import { computed, inject } from 'vue';
import { infoSaleI, WarehouseMapType } from '@/Interfaces/SaleInterface';
import { Column, DataTable, DataTableCellEditCompleteEvent, InputNumber, Select } from 'primevue';
import { getMoney, truncateText } from '@/Global/Helpers';
import { EnumValueI } from '@/Interfaces/GeneralInterface';
import { SaleTypeEnum } from '@/Helpers/SaleHelper';
import { saleKey } from '@/Injections/saleKeys';
import { useSaleCalculation } from '@/Composable/Sales/useSaleCalculation';

// Para eliminar un item de la venta
const propsW = defineProps<{
  refund?: boolean;
  warehouses?: WarehouseMapType;
}>();

// Para eliminar un item de la venta
const form = inject(saleKey)!;

const { calculateItemRow } = useSaleCalculation();

const isReturn = computed(() => {
  return form.type === SaleTypeEnum.DEVOLUCION;
});

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

const onCellEditComplete = (event: DataTableCellEditCompleteEvent) => {
  const index = event.index;
  const info = event.newData;

  calculateItemRow(info, index);
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
        <InputNumber
          :readonly="isReturn && data.is_service"
          :min-fraction-digits="2"
          :max-fraction-digits="2"
          fluid
          v-model="data.stock"
        />
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
        <InputNumber
          :readonly="isReturn"
          fluid
          :minFractionDigits="2"
          :maxFractionDigits="2"
          v-model="data.price"
        />
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
          :readonly="isReturn"
          :class="[{ 'text-red-500': isReturn }]"
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
  </DataTable>
</template>
