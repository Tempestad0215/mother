<script setup lang="ts">
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { FloatLabel, InputNumber, RadioButton } from 'primevue';
import { computed, inject, onMounted, ref, watch } from 'vue';
import { saleKey } from '@/utils/keys';
import { faArrowAltCircleDown, faArrowAltCircleUp } from '@fortawesome/free-solid-svg-icons';
import { editFormI, infoSaleI } from '@/Interfaces/SaleInterface';

// Datos para el fomrularios
const form = inject(saleKey)!;

//
const maxIndex = defineModel<number>('lastIndex', {
  default: 0,
});
const minIndex = defineModel<number>('minIndex', {
  default: 0,
});
const lastIndex = ref(0);

const typePrice = ref<'price' | 'min_price' | 'promotional_price'>('price');

const emit = defineEmits<{
  (e: 'calculateTotals'): void;
  (e: 'calculateItemRow', item: infoSaleI): void;
}>();

// Formulario para editar un item de la venta
const editItemForm = defineModel<editFormI>('editItemForm', {
  required: true,
});

onMounted(() => {
  maxIndex.value = form.info_sale.length - 1;
  typePrice.value = 'price';
});

// Almacenes para el select
watch(
  () => editItemForm,
  (newVal) => {
    if (!checkIndex()) return;
    const item = form.info_sale[maxIndex.value];
    item.price = newVal.value.price;
    item.stock = newVal.value.stock;
    item.discount = newVal.value.discount;
  },
  { deep: true }
);

// Datos del formulario
watch(
  () => editItemForm,
  (newFormState) => {
    // Si no hay un precio o stock válido, evitamos cálculos vacíos
    if (!newFormState.value.stock || !newFormState.value.price) return;

    // Sacar la información del producto que se está editando
    const info = form.info_sale[maxIndex.value];

    // Si por alguna razón el índice no es válido, salimos
    if (!info) return;

    emit('calculateItemRow', info);
  },
  { deep: true } // 🔥 Obligatorio para que escuche cambios dentro de las propiedades del objeto
);

const isRefund = computed((): boolean => {
  return form.type === 'Devolucion';
});

// Para mostrar el formulario de edición
type MoveDirection = 'up' | 'down';

// Para mover el formulario de edición hacia arriba o hacia abajo
const moveEdit = (direction: MoveDirection) => {
  const current = maxIndex.value;

  if (direction === 'up') {
    // no bajar de 0
    if (current <= minIndex.value) return;
    maxIndex.value = current - 1;
  }

  if (direction === 'down') {
    // no subir del último índice
    if (current >= maxIndex.value) return;
    maxIndex.value = current + 1;
  }
  Object.assign(editItemForm, form.info_sale[maxIndex.value]);
};

// Formulario para la venta
const changePrice = () => {
  if (form.info_sale.length <= 0) return;
  const idx = maxIndex.value;
  // proteger índice inválido
  if (idx < 0 || idx >= form.info_sale.length) return;

  const item = form.info_sale[idx];

  switch (typePrice.value) {
    case 'price':
      // precio normal (ya está en item.price)
      // si quisieras usar un campo original:
      // item.price = item.base_price ?? item.price;
      item.temp_price = item.price ?? 0;
      item.price_type = 'price';
      break;

    case 'promotional_price':
      // precio mínimo
      item.temp_price = item.promotional_price ?? 0;
      item.price_type = 'promotional_price';
      break;

    case 'min_price':
      // precio especial
      item.temp_price = item.min_price ?? 0;
      item.price_type = 'min_price';
      break;

    default:
      // opcional: si no hay tipo válido, no hacer nada
      return;
  }

  // totalAmount(idx);
};

// Para obtener el precio mínimo del producto que se está editando
const minPrice = computed((): number => {
  if (!checkIndex()) return 0;
  return Number(form.info_sale[lastIndex.value].min_price);
});

// Para mostrar el nombre del producto que se está editando
const productEditingName = computed((): string => {
  if (!checkIndex()) return '';
  return form.info_sale[lastIndex.value].product_name;
});

// Para mostrar el formulario de edición
// const deletedItem = (index: number) => {
//   if (form.info_sale[index].stock === 0) {
//     form.info_sale.splice(index, 1);
//     calculateTotals();
//
//     if (form.info_sale.length === 0) {
//       showEdit.value = false;
//     }
//     return;
//   }
// };

// Para saber si el producto es un servicio o no
const productIsService = computed(() => {
  const item = form.info_sale[lastIndex.value];
  return !!item?.is_service;
});

// Para verificar si el índice es válido
const checkIndex = (): boolean => {
  return !(maxIndex.value < minIndex.value || maxIndex.value > maxIndex.value);
};
</script>

<template>
  <div class="flex flex-col gap-5 items-center">
    <div v-if="form.info_sale.length > 0" class="text-2xl font-bold">
      Editando el Item : {{ productEditingName }}, es un:
      {{ productIsService ? 'Servicios' : 'Producto' }}
    </div>
    <div class="flex gap-5">
      <div class="flex flex-col gap-5 mt-5">
        <div v-if="!productIsService" class="flex gap-5">
          <div class="flex items-center gap-2">
            <RadioButton
              :disabled="isRefund"
              @change="changePrice"
              v-model="typePrice"
              inputId="normal_price"
              name="normal_price"
              value="price"
            />
            <label for="normal_price"> Precio Normal </label>
          </div>
          <div class="flex items-center gap-2">
            <RadioButton
              :disabled="isRefund"
              @change="changePrice"
              v-model="typePrice"
              inputId="promotional_price"
              name="promotional_price"
              value="promotional_price"
            />
            <label for="promotional_price"> Precio Promocional </label>
          </div>
          <div class="flex items-center gap-2">
            <RadioButton
              :disabled="isRefund"
              @change="changePrice"
              v-model="typePrice"
              inputId="min_price"
              name="min_price"
              value="min_price"
            />
            <label for="min_price"> Precio Minimo </label>
          </div>
        </div>
        <div class="flex gap-5">
          <FloatLabel variant="on">
            <InputNumber v-model="editItemForm.stock" />
            <label for="stock">Cantidad</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <InputNumber
              :disabled="isRefund"
              :min="minPrice"
              :readonly="!productIsService"
              v-model="editItemForm.price"
            />
            <label for="price">Precio</label>
          </FloatLabel>
          <FloatLabel variant="on">
            <InputNumber :disabled="isRefund" v-model="editItemForm.discount" />
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
</template>

<style scoped></style>
