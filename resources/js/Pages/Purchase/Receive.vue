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
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import axios from 'axios';
import { FormReceiveItemI } from '@/Interfaces/ReceiveInterface';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { ProductBaseI } from '@/Interfaces/ProductInterface';
import { TaxBaseI } from '@/Interfaces/TaxInterface';
import { SupplierBaseI } from '@/Interfaces/SupplierInterface';
import { getMoney } from '@/Global/Helpers';

// Interfaces
const toast = useToast();
const page = usePage();

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
      tax_uuid: '',
      amount: 0,
      tax: 0,
    },
  ] as FormReceiveItemI[],
});

// Función para calcular el importe neto de una fila individual
const calculateRowAmount = (index: number) => {
  const item = form.items[index];

  // 1. Buscar el objeto de impuesto correspondiente al tax_uuid del producto
  let taxValue: TaxBaseI | undefined = undefined;

  if (props.taxes && props.taxes.length > 0 && item.tax_uuid) {
    taxValue = props.taxes.find((el) => el.uuid === item.tax_uuid);
  }

  const taxRate = taxValue ? Number(taxValue.rate) : 0;

  const subtotal = item.cost * item.quantity;
  const discountAmount = (subtotal * item.discount) / 100;
  const subtotalWithDiscount = subtotal - discountAmount;

  // 4. Calcular el monto del impuesto
  let taxAmount = 0;

  if (page.props.setting.add_tax) {
    taxAmount = (subtotalWithDiscount * taxRate) / 100;
  }

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
    tax_uuid: '',
    amount: 0,
    tax: 0,
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

// Enviar formulario al backend
const submit = () => {
  form.post(route('purchase.receiveStore'), {
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
    const res = await axios.get(route('supplier.json'), {
      params: {
        search: value,
      },
    });

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

const searchByCode = async (index: number) => {
  const item = form.items[index];

  // Si el código está vacío o ya tiene un nombre asignado, no hace la petición
  if (!item.code || item.code.trim() === '' || item.product_name !== '') {
    return;
  }

  try {
    const res = await axios.get(route('product.get.code'), {
      params: {
        search: item.code,
      },
    });

    if (res.data && res.data.uuid) {
      const infoData = res.data as ProductBaseI;

      item.product_name = infoData.name;
      item.tax_uuid = infoData.tax_uuid;
      item.cost = Number(infoData.cost) || 0;

      calculateRowAmount(index);
    }
  } catch (error) {
    toast.add({
      severity: 'warn',
      summary: 'Aviso',
      detail: 'No es posible encontrar este código',
      life: 300,
    });
  }
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
                      fluid
                      @blur="searchByCode(index)"
                      v-model="form.items[index].code"
                      placeholder="SKU / Código"
                      class="w-full p-inputtext-sm font-mono h-10"
                    />
                  </template>
                </Column>

                <Column header="Nombre / Descripción" class="min-w-45">
                  <template #body="{ index }">
                    <AutoComplete
                      fluid
                      :modelValue="form.items[index].product_name"
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
                        size="small"
                        optionValue="uuid"
                        placeholder="Seleccionar"
                        class="w-full p-inputtext-sm"
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
                        mode="decimal"
                        :min-fraction-digits="2"
                        :max-fraction-digits="2"
                        class="w-45 p-inputtext-sm"
                        @blur="calculateRowAmount(index)"
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
                        @blur="calculateRowAmount(index)"
                      />
                    </div>
                  </template>
                </Column>
                <Column header="ITBIS %" class="w-24">
                  <template #body="{ index }">
                    <div class="w-30 max-w-40">
                      <Select
                        fluid
                        v-model="form.items[index].tax_uuid"
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
                      {{ getMoney(data.amount) }}
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

              <!--              &lt;!&ndash; Desglose de Totales &ndash;&gt;-->
              <!--              <div-->
              <!--                class="w-full md:w-80 bg-slate-50 text-slate-800 rounded-xl p-4 shadow-sm border border-slate-200 text-sm space-y-2"-->
              <!--              >-->
              <!--                <div class="flex justify-between items-center">-->
              <!--                  <span class="text-slate-600 font-medium">Sub Total:</span>-->
              <!--                  <span class="font-semibold text-slate-700">{{ getMoney(form.sub_total) }}</span>-->
              <!--                </div>-->

              <!--                <div class="flex justify-between items-center">-->
              <!--                  <span class="text-slate-600 font-medium">ITBIS:</span>-->
              <!--                  <span class="font-semibold text-blue-600">{{ getMoney(form.tax) }}</span>-->
              <!--                </div>-->

              <!--                <div class="flex justify-between items-center">-->
              <!--                  <span class="text-slate-600 font-medium">Descuento:</span>-->
              <!--                  <span class="font-semibold text-emerald-600"-->
              <!--                    >-{{ getMoney(form.discount_amount) }}</span-->
              <!--                  >-->
              <!--                </div>-->

              <!--                <div-->
              <!--                  class="border-t border-slate-200 pt-2 mt-2 flex justify-between items-center text-base"-->
              <!--                >-->
              <!--                  <span class="font-bold text-slate-900">Total:</span>-->
              <!--                  <span class="font-bold text-lg text-emerald-700">{{-->
              <!--                    getMoney(form.amount)-->
              <!--                  }}</span>-->
              <!--                </div>-->
              <!--              </div>-->
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
