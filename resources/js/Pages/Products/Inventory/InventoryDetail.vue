<script setup lang="ts">
import { Column, DataTable, InputNumber, Checkbox, FloatLabel } from 'primevue';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { computed, inject, onMounted, reactive, watch } from 'vue';
import { formProductKey } from '@/Injections/InjectionKeys';
import { WarehouseProductI } from '@/Interfaces/ProductInterface';

const propsW = defineProps<{
  warehouses: Array<WarehouseBaseI>;
}>();

// Para los datos del fomulario
const form = inject(formProductKey)!!;

// Datos para poder cambiar los datos
const handleStockValue = reactive({
  min_stock: 0,
  max_stock: 0,
  reorder_level: 0,
});

// Al momento de cargar
onMounted(() => {
  if (propsW.warehouses && propsW.warehouses.length > 0) {
    propsW.warehouses.forEach((warehouse: WarehouseBaseI) => {
      form.warehouse_product = [];
      form.warehouse_product?.push({
        warehouse_uuid: warehouse.uuid,
        prefix: warehouse.prefix,
        name: warehouse.name,
        available: 0,
        committed: 0,
        min_stock: 0,
        max_stock: 0,
        reorder_level: 0,
      });
    });
  }
});

// Verificar si los datos cambian para introducir en los datos
watch(
  () => handleStockValue,
  (newVal) => {
    form.warehouse_product?.forEach((wh: WarehouseProductI) => {
      wh.min_stock = newVal.min_stock;
      wh.max_stock = newVal.max_stock;
      wh.reorder_level = newVal.reorder_level;
    });
  },
  {
    deep: true,
  }
);

//Verificar si el reorder esta en el umbral
const checkReorderLevel = computed(() => {
  return (minStock: number, reorderLevel: number) => {
    return reorderLevel < minStock;
  };
});
</script>

<template>
  <div>
    <div>
      <div class="space-x-2">
        <Checkbox binary v-model="form.handle_warehouse" />
        <label for="handleWarehouse">Manejar Por Almacen</label>
      </div>
      <div class="grid grid-cols-3 gap-3 mt-3" v-if="!form.handle_warehouse">
        <FloatLabel variant="on">
          <InputNumber :min="0" v-model="handleStockValue.min_stock" fluid />
          <label for="min_stock">Stock Minimo</label>
        </FloatLabel>
        <FloatLabel variant="on">
          <InputNumber :min="0" v-model="handleStockValue.max_stock" fluid />
          <label for="min_stock">Stock Minimo</label>
        </FloatLabel>
        <FloatLabel variant="on">
          <InputNumber :min="0" v-model="handleStockValue.reorder_level" fluid />
          <label for="min_stock">Reorder Level</label>
        </FloatLabel>
      </div>
    </div>
    <DataTable :value="form.warehouse_product">
      <Column header="PREFIX" field="prefix"> </Column>
      <Column header="NOMBRE" field="name"></Column>
      <Column header="STOCK" class="w-30">
        <template #body="{ data }: { data: WarehouseProductI }">
          <InputNumber fluid v-model="data.available" />
        </template>
      </Column>
      <Column header="COMPREMITIDO" class="w-20">
        <template #body="{ data }: { data: WarehouseProductI }">
          <InputNumber readonly fluid v-model="data.committed" />
        </template>
      </Column>
      <Column header="PEDIDO" class="w-25">
        <template #body="{ data }: { data: WarehouseProductI }">
          <InputNumber fluid v-model="data.available" />
        </template>
      </Column>
      <Column header="STOCK MINIMO" class="w-30">
        <template #body="{ data }: { data: WarehouseProductI }">
          <InputNumber :readonly="!form.handle_warehouse" fluid v-model="data.min_stock" />
        </template>
      </Column>
      <Column header="STOCK MAXIMO" class="w-30">
        <template #body="{ data }: { data: WarehouseProductI }">
          <InputNumber :readonly="!form.handle_warehouse" fluid v-model="data.max_stock" />
        </template>
      </Column>
      <Column header="NIVEL NESARIO" class="w-30">
        <template #body="{ data }: { data: WarehouseProductI }">
          <InputNumber
            :readonly="!form.handle_warehouse"
            :invalid="checkReorderLevel(data.min_stock || 0, data.reorder_level || 0)"
            fluid
            v-model="data.reorder_level"
          />
        </template>
      </Column>
    </DataTable>
  </div>
</template>

<style scoped></style>
