<script setup lang="ts">
import { inject, onMounted, watch } from 'vue';
import { PreciseCalculator } from '@/utils/Decimal';
import { InputNumber, FloatLabel, Select, useToast } from 'primevue';
import { formProductKey } from '@/Injections/InjectionKeys';
import { useProductStore } from '@/stores/ProductStore';
import { storeToRefs } from 'pinia';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { PriceListProducts, PriceListWTI } from '@/Interfaces/PriceListInterface';
import { UnitInterfaceI } from '@/Interfaces/UnitInterface';
import axios from 'axios';

const toast = useToast();

const propsW = defineProps<{
  warehouses: Array<WarehouseBaseI>;
  priceLists: Array<PriceListWTI>;
  units: Array<UnitInterfaceI>;
  isUpdate: boolean;
}>();

const form = inject(formProductKey)!!;
const productStore = storeToRefs(useProductStore());

onMounted(() => {
  if (propsW.isUpdate) {
    setTimeout(async () => {
      await getInfoFromPriceList();
    }, 300);
  }
});

watch(
  () => [form.tax_uuid, form.cost, form.price, form.tax_uuid],
  () => {
    const tax = PreciseCalculator.multiply(form.price, productStore.taxRate.value);

    if (Number(tax) === 0) {
      form.product_no_tax = form.price;
    } else {
      form.product_no_tax = Number(PreciseCalculator.subtract(form.price, tax.toString()));
    }

    form.benefits = Number(PreciseCalculator.subtract(form.price, form.cost));
    const benefitsRate = PreciseCalculator.divide(Number(form.benefits), form.price || 1);
    form.benefits_rate = Number(
      Number(PreciseCalculator.multiply(String(benefitsRate), 100)).toFixed(2)
    );
  }
);

const getInfoFromPriceList = async () => {
  try {
    const res = await axios.get(route('price-list.product.show', form.uuid));
    const data = res.data as Array<PriceListProducts>;
    const infoPriceList = data.find((el) => el.uuid === form.price_list_uuid);

    if (infoPriceList) {
      form.price = infoPriceList.price;
      form.min_price = infoPriceList.min_price;
      form.promotional_price = infoPriceList.promotional_price;
    } else {
      form.price = 0;
      form.min_price = 0;
      form.promotional_price = 0;
    }
  } catch (error) {
    toast.add({
      severity: 'error',
      detail: 'Error al obtener la lista de precios',
      life: 3000,
    });
  }
};
</script>

<template>
  <div class="space-y-5">
    <!-- Selección de Almacén, Lista de Precio y Unidad -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <FloatLabel variant="on" class="w-full">
        <Select
          :options="propsW.warehouses"
          v-model="form.warehouse_uuid"
          option-label="name"
          option-value="uuid"
          fluid
        />
        <label for="warehouse">Almacén Predeterminado</label>
      </FloatLabel>

      <FloatLabel variant="on" class="w-full">
        <Select
          @change="getInfoFromPriceList"
          fluid
          id="price_list"
          option-value="uuid"
          :optionLabel="(item: PriceListWTI) => `${item.name} | ${item.currency}`"
          :options="propsW.priceLists"
          v-model="form.price_list_uuid"
        />
        <label for="price_list">Lista de Precios</label>
      </FloatLabel>

      <FloatLabel variant="on" class="w-full">
        <Select
          fluid
          id="unit"
          optionValue="uuid"
          optionLabel="name"
          :options="propsW.units"
          v-model="form.unit_uuid"
        />
        <label for="unit">Unidad de Medida</label>
      </FloatLabel>
    </div>

    <!-- Valores de Costo y Precios -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <FloatLabel variant="on" class="w-full">
        <InputNumber
          currency="DOP"
          locale="en-US"
          :max-fraction-digits="2"
          fluid
          id="sale_cost"
          v-model="form.cost"
        />
        <label for="sale_cost">Costo</label>
      </FloatLabel>

      <FloatLabel variant="on" class="w-full">
        <InputNumber
          currency="DOP"
          locale="en-US"
          :max-fraction-digits="2"
          fluid
          id="sale_price"
          v-model="form.price"
        />
        <label for="sale_price">Precio Venta</label>
      </FloatLabel>

      <FloatLabel variant="on" class="w-full">
        <InputNumber
          :min="form.cost"
          currency="DOP"
          locale="en-US"
          :max-fraction-digits="2"
          fluid
          id="sale_min_price"
          v-model="form.min_price"
        />
        <label for="sale_min_price">Precio Mínimo</label>
      </FloatLabel>

      <FloatLabel variant="on" class="w-full">
        <InputNumber
          :min="form.min_price"
          currency="DOP"
          locale="en-US"
          :max-fraction-digits="2"
          fluid
          id="sale_promotional_price"
          v-model="form.promotional_price"
        />
        <label for="sale_promotional_price">Precio Promocional</label>
      </FloatLabel>
    </div>

    <!-- Resumen Calculado -->
    <div
      class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 bg-slate-50 rounded-lg border border-slate-200 text-sm"
    >
      <div class="flex justify-between sm:justify-start items-center gap-2">
        <strong class="text-slate-700">Precio sin ITBIS:</strong>
        <span
          class="font-semibold text-slate-900 bg-white px-2 py-0.5 rounded border border-slate-200"
        >
          {{ PreciseCalculator.formatCurrency(form.product_no_tax) }}
        </span>
      </div>

      <div class="flex justify-between sm:justify-start items-center gap-2">
        <strong class="text-slate-700">Beneficio Neto:</strong>
        <span
          class="font-semibold text-emerald-600 bg-white px-2 py-0.5 rounded border border-slate-200"
        >
          {{ PreciseCalculator.formatCurrency(form.benefits) }}
        </span>
      </div>

      <div class="flex justify-between sm:justify-start items-center gap-2">
        <strong class="text-slate-700">Margen Beneficio:</strong>
        <span
          class="font-semibold text-blue-600 bg-white px-2 py-0.5 rounded border border-slate-200"
        >
          {{ form.benefits_rate }} %
        </span>
      </div>
    </div>
  </div>
</template>
