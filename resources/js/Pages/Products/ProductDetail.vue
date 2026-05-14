<script setup lang="ts">
import { Fieldset, FloatLabel, InputNumber, InputText, Select, SelectChangeEvent } from 'primevue';
import { inject } from 'vue';
import { formProductKey } from '@/Injections/InjectionKeys';
import { BranchInterfaceI } from '@/Interfaces/BranchInterface';
import { UnitInterfaceI } from '@/Interfaces/UnitInterface';
import { usePage } from '@inertiajs/vue3';
import { AppPageProps } from '@/global';
import { TaxBaseI } from '@/Interfaces/TaxInterface';
import { useProductStore } from '@/stores/ProductStore';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { PriceListWTI } from '@/Interfaces/PriceListInterface';

const page = usePage<AppPageProps>();

const propsW = defineProps<{
  units: UnitInterfaceI[];
  branches: BranchInterfaceI[];
  warehouses: WarehouseBaseI[];
  priceLists: Array<PriceListWTI>;
}>();

const form = inject(formProductKey)!!;
const taxes = page.props.taxes;
const productStore = useProductStore();

const selectTax = (data: SelectChangeEvent) => {
  const taxInfo: TaxBaseI | undefined = taxes.find((el) => el.uuid === data.value);

  productStore.setTaxRateFromPercent(Number(taxInfo?.rate));
};
</script>

<template>
  <Fieldset legend="Caracteristicas">
    <div class="grid grid-cols-2 gap-4">
      <!--      Impuesto del producto-->
      <FloatLabel v-if="form.has_tax" variant="on">
        <Select
          fluid
          id="tax"
          option-value="uuid"
          @change="selectTax"
          :optionLabel="(item: TaxBaseI) => `${item.name} | ${item.rate}`"
          :options="taxes"
          v-model="form.tax_uuid"
        >
        </Select>
        <label for="tax">Impuesto</label>
      </FloatLabel>
      <!--Peso del producto-->
      <FloatLabel variant="on">
        <InputNumber fluid id="weight" v-model="form.weight" />
        <label for="weight">Peso</label>
      </FloatLabel>
      <!--      Dimensiones-->
      <FloatLabel variant="on">
        <InputText fluid id="dimension" v-model="form.dimensions" />
        <label for="dimension">Dimensiones</label>
      </FloatLabel>
    </div>
  </Fieldset>
</template>
