<script setup lang="ts">
import { Column, DataTable, InputNumber, Checkbox, FloatLabel } from 'primevue';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { computed, inject, onMounted, reactive, watch } from 'vue';
import { formProductKey } from '@/Injections/InjectionKeys';
import { WarehouseProductI } from '@/Interfaces/ProductInterface';

const propsW = defineProps<{
  warehouses: Array<WarehouseBaseI>;
}>();

const form = inject(formProductKey)!!;

const handleStockValue = reactive({
  min_stock: 0,
  max_stock: 0,
  reorder_level: 0,
});

onMounted(() => {
  if (propsW.warehouses && propsW.warehouses.length > 0) {
    propsW.warehouses.forEach((warehouse: WarehouseBaseI) => {
      form.warehouse_product = [];
      form.warehouse_product?.push({
        warehouse_uuid: warehouse.uuid,
        prefix: warehouse.prefix,
        name: warehouse.name,
        available: 0,
        stock_quantity: 0,
        committed: 0,
        min_stock: 0,
        max_stock: 0,
        reorder_level: 0,
      });
    });
  }
});

watch(
  () => handleStockValue,
  (newVal) => {
    form.warehouse_product?.forEach((wh: WarehouseProductI) => {
      wh.min_stock = newVal.min_stock;
      wh.max_stock = newVal.max_stock;
      wh.reorder_level = newVal.reorder_level;
    });
  },
  { deep: true }
);

const checkReorderLevel = computed(() => {
  return (minStock: number, reorderLevel: number) => {
    return reorderLevel < minStock;
  };
});
</script>

<template>
  <div class="space-y-4">
    <!-- Controles de Manejo por Almacén -->
    <div class="bg-slate-50 p-3 rounded-lg border border-slate-200 space-y-3">
      <div class="flex items-center gap-2">
        <Checkbox binary inputId="handleWarehouse" v-model="form.handle_warehouse" />
        <label for="handleWarehouse" class="text-sm font-medium text-slate-700 cursor-pointer">
          Manejar Stock Individual por Almacén
        </label>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1" v-if="!form.handle_warehouse">
        <FloatLabel variant="on" class="w-full">
          <InputNumber :min="0" v-model="handleStockValue.min_stock" fluid />
          <label for="min_stock">Stock Mínimo Global</label>
        </FloatLabel>

        <FloatLabel variant="on" class="w-full">
          <InputNumber :min="0" v-model="handleStockValue.max_stock" fluid />
          <label for="max_stock">Stock Máximo Global</label>
        </FloatLabel>

        <FloatLabel variant="on" class="w-full">
          <InputNumber :min="0" v-model="handleStockValue.reorder_level" fluid />
          <label for="reorder">Nivel de Reorden Global</label>
        </FloatLabel>
      </div>
    </div>

    <!-- Tabla de Almacenes (Con Scroll Horizontal para Celulares) -->
    <div class="overflow-x-auto rounded-lg border border-slate-200">
      <DataTable
        :value="form.warehouse_product"
        size="small"
        striped-rows
        class="min-w-[700px] w-full text-xs sm:text-sm"
      >
        <Column header="CÓDIGO" field="prefix" class="w-20 font-semibold" />
        <Column header="ALMACÉN" field="name" class="min-w-[140px]" />

        <Column header="STOCK DISP." class="w-28">
          <template #body="{ data }: { data: WarehouseProductI }">
            <InputNumber fluid v-model="data.available" size="small" />
          </template>
        </Column>

        <Column header="COMPROMETIDO" class="w-28">
          <template #body="{ data }: { data: WarehouseProductI }">
            <InputNumber readonly fluid v-model="data.committed" size="small" />
          </template>
        </Column>

        <Column header="PEDIDO" class="w-28">
          <template #body="{ data }: { data: WarehouseProductI }">
            <InputNumber fluid v-model="data.available" size="small" />
          </template>
        </Column>

        <Column header="MÍNIMO" class="w-28">
          <template #body="{ data }: { data: WarehouseProductI }">
            <InputNumber
              :readonly="!form.handle_warehouse"
              fluid
              v-model="data.min_stock"
              size="small"
            />
          </template>
        </Column>

        <Column header="MÁXIMO" class="w-28">
          <template #body="{ data }: { data: WarehouseProductI }">
            <InputNumber
              :readonly="!form.handle_warehouse"
              fluid
              v-model="data.max_stock"
              size="small"
            />
          </template>
        </Column>

        <Column header="REORDEN" class="w-28">
          <template #body="{ data }: { data: WarehouseProductI }">
            <InputNumber
              :readonly="!form.handle_warehouse"
              :invalid="checkReorderLevel(data.min_stock || 0, data.reorder_level || 0)"
              fluid
              v-model="data.reorder_level"
              size="small"
            />
          </template>
        </Column>
      </DataTable>
    </div>
  </div>
</template>
