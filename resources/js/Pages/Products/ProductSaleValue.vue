<script setup lang="ts">
import { inject, watch } from 'vue';
import { PreciseCalculator } from '@/utils/Decimal';
import { InputNumber, FloatLabel, Select } from 'primevue';
import { formProductKey } from '@/Injections/InjectionKeys';
import { useProductStore } from '@/stores/ProductStore';
import { storeToRefs } from 'pinia';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { PriceListWTI } from '@/Interfaces/PriceListInterface';
import { UnitInterfaceI } from '@/Interfaces/UnitInterface';
import axios from 'axios';

const propsW = defineProps<{
  warehouses: Array<WarehouseBaseI>;
  priceLists: Array<PriceListWTI>;
  units: Array<UnitInterfaceI>;
}>();

const form = inject(formProductKey)!!;
const productStore = storeToRefs(useProductStore());

/*
Propiedades computada
 */
/**
 * Precio sin impuesto
 */

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
    const res = await axios.get(
      route('price-list.product.show', { priceList: form.price_list_uuid, product: form.uuid })
    );
    console.log(res.data);
  } catch (error) {
    console.log(error);
  }
};
</script>

<template>
  <div>
    <div class="grid grid-cols-3 gap-3">
      <div>
        <FloatLabel variant="on">
          <Select
            :options="propsW.warehouses"
            v-model="form.warehouse_uuid"
            option-label="name"
            option-value="uuid"
            fluid
          />
          <label for="warehouse">Almacen</label>
        </FloatLabel>
      </div>
      <!--      Lista de precio-->
      <FloatLabel variant="on">
        <Select
          @change="getInfoFromPriceList"
          fluid
          id="price_list"
          option-value="uuid"
          :optionLabel="(item: PriceListWTI) => `${item.name} | ${item.currency}`"
          :options="propsW.priceLists"
          v-model="form.price_list_uuid"
        >
        </Select>
        <label for="tax">Lista de Precio</label>
      </FloatLabel>
      <div>
        <FloatLabel variant="on">
          <Select
            fluid
            id="unit"
            optionValue="uuid"
            optionLabel="name"
            :options="propsW.units"
            v-model="form.unit_uuid"
          />
          <label for="tax">Unidad</label>
        </FloatLabel>
      </div>
    </div>

    <div class="grid grid-cols-4 gap-3 mt-5">
      <FloatLabel variant="on">
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
      <FloatLabel variant="on">
        <InputNumber
          currency="DOP"
          locale="en-US"
          :max-fraction-digits="2"
          fluid
          id="sale_price"
          v-model="form.price"
        />
        <label for="sale_price">Precio</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <InputNumber
          :min="form.cost"
          currency="DOP"
          locale="en-US"
          :max-fraction-digits="2"
          fluid
          id="sale_min_price"
          v-model="form.min_price"
        />
        <label for="sale_min_price">Precio Minimo</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <InputNumber
          :min="form.min_price"
          currency="DOP"
          locale="en-US"
          :max-fraction-digits="2"
          fluid
          id="sale_special_price"
          v-model="form.special_price"
        />
        <label for="sale_special_price">Precio Especial</label>
      </FloatLabel>
    </div>
    <div
      class="flex flex-col md:flex-row justify-between mt-5 border-2 rounded-md p-2 border-gray-200"
    >
      <p>
        <strong>Precio - Itbis</strong>
        <span class="inline-block px-3 rounded-md ml-3">{{
          PreciseCalculator.formatCurrency(form.product_no_tax)
        }}</span>
      </p>
      <p>
        <strong>Beneficio</strong>
        <span class="inline-block px-3 rounded-md ml-3">{{
          PreciseCalculator.formatCurrency(form.benefits)
        }}</span>
      </p>
      <p>
        <strong>Beneficios Margen </strong>
        <span class="inline-block px-3 rounded-md ml-3">{{ form.benefits_rate }} %</span>
      </p>
    </div>
  </div>
</template>
