<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import {
  Breadcrumb,
  Button,
  Card,
  Column,
  DataTable,
  DatePicker,
  Divider,
  FloatLabel,
  InputNumber,
  InputText,
  Select,
  Textarea,
  AutoComplete,
  AutoCompleteCompleteEvent,
  AutoCompleteOptionSelectEvent,
  useToast,
} from 'primevue';
import { purchaseBreadCrumb } from '@/Helpers/PurchaseHelper';
import { Eraser, Plus, Send, Trash2 } from 'lucide-vue-next';
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import axios from 'axios';
import { FormReceiveItemI } from '@/Interfaces/ReceiveInterface';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { ProductBaseI } from '@/Interfaces/ProductInterface';
import { TaxBaseI } from '@/Interfaces/TaxInterface';
import { SupplierBaseI, SupplierI } from '@/Interfaces/SupplierInterface';

// Interfaces
const toast = useToast();

// Props que vienen desde el Controller de Laravel
const props = defineProps<{
  warehouses: WarehouseBaseI[];
  taxes: TaxBaseI[];
}>();

const productSuggestions = ref<ProductBaseI[]>([]);
const supplierSuggestions = ref<SupplierBaseI[]>([]);

// Formulario de Inertia
const form = useForm({
  receive_date: new Date() as Date | null,
  supplier_uuid: '',
  comment: '',
  items: [
    {
      code: '',
      product_name: '',
      cost: 0,
      quantity: 1,
      warehouse_uuid: props.warehouses?.[0]?.uuid || '',
      discount: 0,
      tax: 0,
      amount: 0,
    },
  ] as FormReceiveItemI[],
});

// Función para calcular el importe neto de una fila individual
const calculateRowAmount = (index: number) => {
  const item = form.items[index];
  const subtotal = item.cost * item.quantity;
  const discountAmount = (subtotal * item.discount) / 100;
  const subtotalWithDiscount = subtotal - discountAmount;
  const taxAmount = (subtotalWithDiscount * item.tax) / 100;

  const finalAmount = subtotalWithDiscount + taxAmount;
  form.items[index].amount = Number(finalAmount.toFixed(2));
};

// Agregar nueva fila limpia a la tabla
const addRow = () => {
  // 1. Obtener la última fila de la lista
  const lastItem = form.items[form.items.length - 1];

  // 2. Validar si existe y si tiene campos vacíos (código o nombre)
  if (lastItem) {
    const isCodeEmpty = !lastItem.code || lastItem.code.trim() === '';
    const isNameEmpty = !lastItem.product_name || lastItem.product_name.trim() === '';

    if (isCodeEmpty && isNameEmpty) {
      toast.add({
        severity: 'warn',
        summary: 'Fila Vacía',
        detail: 'Por favor, completa el producto actual antes de agregar uno nuevo.',
        life: 3000,
      });
      return; // ⛔ Detiene la ejecución, no agrega la fila
    }
  }

  // 3. Si la última fila está llena (o es el primer elemento), agrega una nueva
  form.items.push({
    code: '',
    product_name: '',
    cost: 0,
    quantity: 1,
    warehouse_uuid: props.warehouses?.[0]?.uuid || '',
    discount: 0,
    tax: 0,
    amount: 0,
  });
};

// Eliminar una fila específica
const removeRow = (index: number) => {
  if (form.items.length > 1) {
    form.items.splice(index, 1);
  }
};

// Total General acumulado de la entrada de mercancía
const totalReceive = computed(() => {
  return form.items.reduce((acc, item) => acc + (item.amount || 0), 0);
});

// Helper de formato de moneda
const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('es-DO', {
    style: 'currency',
    currency: 'DOP',
  }).format(value || 0);
};

// Enviar formulario al backend
const submit = () => {
  form.post(route('receives.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
};

const searchProductName = async (event: AutoCompleteCompleteEvent) => {
  const value = event.query as string;

  try {
    const res = await axios.get(route('product.get.json'), {
      params: {
        search: value,
      },
    });

    if (res.data && Array.isArray(res.data)) {
      productSuggestions.value = res.data as ProductBaseI[];
    } else {
      productSuggestions.value = [];
    }
  } catch (er) {
    console.log(er);
  }
};

const selectProduct = (data: AutoCompleteOptionSelectEvent, index: number) => {
  const infoData = data.value as ProductBaseI;
  const info = form.items[index];

  info.code = infoData.code;
  info.product_name = infoData.name;
};

const searchSupplier = async (event: AutoCompleteCompleteEvent) => {
  const value = event.query as string;

  try {
    const res = await axios.get(route('supplier.json'));

    if (res.data && Array.isArray(res.data)) {
      supplierSuggestions.value = res.data as SupplierBaseI[];
    } else {
      supplierSuggestions.value = [];
    }
  } catch (error) {}
};

const selectSupplier = (event: AutoCompleteOptionSelectEvent) => {
  const dataInfo = event.value as SupplierBaseI;
  form.supplier_uuid = dataInfo.uuid;
};
</script>

<template>
  <AppLayout title="Entrada de Mercancía">
    <div class="w-full px-2 sm:px-4 py-6 max-w-7xl mx-auto">
      <Card class="shadow-sm rounded-xl border border-slate-200">
        <template #title>
          <div class="space-y-2">
            <Breadcrumb :model="purchaseBreadCrumb" class="text-xs sm:text-sm p-0 bg-transparent" />
            <h3 class="text-xl sm:text-2xl font-bold text-center text-slate-800">
              Recepción / Entrada Manual de Mercancía
            </h3>
          </div>
          <Divider class="my-3" />
        </template>

        <template #content>
          <form class="space-y-6" @submit.prevent="submit">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <FloatLabel variant="on">
                <AutoComplete
                  :suggestions="supplierSuggestions"
                  :optionLabel="(data: SupplierBaseI) => `${data.company_name} | ${data.contact}`"
                  @option-select="selectSupplier"
                  @complete="searchSupplier"
                  fluid
                />
                <label for="supplier">Proveedor</label>
              </FloatLabel>

              <FloatLabel variant="on" class="w-full">
                <DatePicker
                  id="doc_date"
                  v-model="form.receive_date"
                  dateFormat="yy-mm-dd"
                  class="w-full"
                  showIcon
                  fluid
                />
                <label for="doc_date">Fecha de Recepción</label>
              </FloatLabel>
            </div>

            <div class="overflow-x-auto rounded-lg border border-slate-200 shadow-sm">
              <DataTable
                :value="form.items"
                size="small"
                striped-rows
                show-gridlines
                class="min-w-237.5 w-full text-xs sm:text-sm"
              >
                <Column header="#" class="w-12 text-center">
                  <template #body="{ index }">
                    <span class="font-bold text-slate-500">{{ index + 1 }}</span>
                  </template>
                </Column>

                <Column header="Código" class="w-32">
                  <template #body="{ index }">
                    <InputText
                      v-model="form.items[index].code"
                      placeholder="SKU / Código"
                      class="w-full p-inputtext-sm font-mono"
                    />
                  </template>
                </Column>

                <Column header="Nombre / Descripción" class="min-w-45">
                  <template #body="{ index }">
                    <AutoComplete
                      :suggestions="productSuggestions"
                      @option-select="selectProduct($event, index)"
                      :option-label="(data: ProductBaseI) => `${data.code} | ${data.name}`"
                      @complete="searchProductName"
                    />
                  </template>
                </Column>

                <Column header="Almacén" class="w-40">
                  <template #body="{ index }">
                    <div class="w-40">
                      <Select
                        fluid
                        v-model="form.items[index].warehouse_uuid"
                        :options="warehouses"
                        optionLabel="name"
                        optionValue="uuid"
                        placeholder="Seleccionar"
                        class="w-full p-inputtext-sm text-xs"
                      />
                    </div>
                  </template>
                </Column>

                <Column header="Costo U." class="w-32">
                  <template #body="{ index }">
                    <div class="w-30">
                      <InputNumber
                        fluid
                        v-model="form.items[index].cost"
                        mode="currency"
                        currency="DOP"
                        locale="es-DO"
                        class="w-45 p-inputtext-sm"
                        @input="calculateRowAmount(index)"
                      />
                    </div>
                  </template>
                </Column>

                <Column header="Cant." class="w-24">
                  <template #body="{ index }">
                    <div class="w-20">
                      <InputNumber
                        fluid
                        v-model="form.items[index].quantity"
                        :min="1"
                        class="w-full p-inputtext-sm"
                        @input="calculateRowAmount(index)"
                      />
                    </div>
                  </template>
                </Column>
                <Column header="ITBIS %" class="w-24">
                  <template #body="{ index }">
                    <div class="w-30 max-w-40">
                      <Select
                        fluid
                        v-model="form.items[index].warehouse_uuid"
                        :options="props.taxes"
                        :optionLabel="(data: TaxBaseI) => `${data.name} | ${data.rate}`"
                        option-value="uuid"
                      />
                    </div>
                  </template>
                </Column>

                <Column header="Importe" class="w-32 text-right">
                  <template #body="{ data }">
                    <span class="font-bold text-slate-800">
                      {{ formatCurrency(data.amount) }}
                    </span>
                  </template>
                </Column>

                <Column class="w-12 text-center">
                  <template #body="{ index }">
                    <Button
                      type="button"
                      severity="danger"
                      text
                      rounded
                      :disabled="form.items.length === 1"
                      @click="removeRow(index)"
                    >
                      <Trash2 class="w-4 h-4" />
                    </Button>
                  </template>
                </Column>
              </DataTable>
            </div>

            <div class="flex justify-between items-center">
              <Button
                type="button"
                label="Añadir Ítem"
                severity="secondary"
                outlined
                size="small"
                @click="addRow"
              >
                <template #icon>
                  <Plus class="w-4 h-4 mr-1" />
                </template>
              </Button>

              <div class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-2 text-right">
                <span class="text-xs text-slate-500 uppercase font-bold block">Total Entrada</span>
                <span class="text-xl font-black text-emerald-600">{{
                  formatCurrency(totalReceive)
                }}</span>
              </div>
            </div>

            <FloatLabel variant="on" class="w-full">
              <Textarea
                id="comment"
                v-model="form.comment"
                class="w-full min-h-20"
                rows="3"
                autoResize
              />
              <label for="comment">Comentario u Observaciones de la Entrada</label>
            </FloatLabel>

            <div class="pt-2 flex flex-col-reverse sm:flex-row justify-end gap-3">
              <Button
                type="button"
                severity="secondary"
                label="Limpiar Formulario"
                class="w-full sm:w-auto h-10"
                outlined
                @click="form.reset()"
              >
                <template #icon>
                  <Eraser class="w-4 h-4 mr-1" />
                </template>
              </Button>

              <Button
                type="submit"
                label="Procesar Entrada"
                :loading="form.processing"
                class="w-full sm:w-auto h-10 bg-emerald-600 hover:bg-emerald-700 border-none text-white font-semibold"
              >
                <template #icon>
                  <Send class="w-4 h-4 mr-1" />
                </template>
              </Button>
            </div>
          </form>
        </template>
      </Card>
    </div>
  </AppLayout>
</template>

<style scoped>
:deep(.p-datatable-sm .p-datatable-tbody > tr > td) {
  padding: 0.4rem 0.5rem;
}
</style>
