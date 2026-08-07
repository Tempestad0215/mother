<script setup lang="ts">
import {
  AutoComplete,
  AutoCompleteOptionSelectEvent,
  Breadcrumb,
  Button,
  Card,
  Column,
  DataTable,
  DatePicker,
  FloatLabel,
  InputNumber,
  Select,
  SelectChangeEvent,
  useConfirm,
  useToast,
  Divider,
} from 'primevue';
import { router, useForm } from '@inertiajs/vue3';
import { purchaseInfoI } from '@/Interfaces/PurchaseInterface';
import { SupplierI } from '@/Interfaces/SupplierInterface';
import AppLayout from '@layout/AppLayout.vue';
import { PreciseCalculator } from '@/utils/Decimal';
import { ProductBaseI } from '@/Interfaces/ProductInterface';
import { TaxBaseI } from '@/Interfaces/TaxInterface';
import { useProductStore } from '@/stores/ProductStore';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { purchaseBreadCrumb } from '@/Helpers/PurchaseHelper';
import { Plus, Trash2, Send } from '@lucide/vue';

const toast = useToast();
const confirm = useConfirm();

const propsW = defineProps<{
  suppliers: SupplierI[];
  products: ProductBaseI[];
  taxes: TaxBaseI[];
  warehouses: WarehouseBaseI[];
}>();

const productStore = useProductStore();

/*
   Formulario
 */
const form = useForm({
  uuid: 0,
  supplier_uuid: 0,
  doc_date: new Date(),
  info: [
    {
      uuid: '',
      code: '',
      name: '',
      quantity: 0,
      cost: 0,
      warehouse_uuid: '',
      tax_uuid: '',
      tax: 0,
      discount_rate: 0,
      discount_amount: 0,
      amount: 0,
    },
  ] as purchaseInfoI[],
  tax: 0,
  discount: 0,
  sub_total: 0,
  amount: 0,
  comment: '',
});

const searchProduct = (index: number) => {
  const productSearch = form.info[index].name;
  router.get(
    route('purchase.index', { productSearch }),
    {},
    {
      preserveScroll: true,
      preserveState: true,
    }
  );
};

const getInfoName = (event: AutoCompleteOptionSelectEvent, index: number) => {
  const info = event.value as purchaseInfoI;
  const existsIndex = form.info.findIndex((el) => el.code === info.code);

  if (existsIndex === -1) {
    form.info[index].uuid = info.uuid;
    form.info[index].code = info.code;
    form.info[index].name = info.name;
    return;
  } else {
    form.info[existsIndex].quantity += 1;
  }
};

const submit = () => {
  form.post(route('purchase.store'), {
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'Registro Completado',
        detail: 'Orden de compra registrada correctamente.',
        life: 3000,
      });
      form.reset();
      form.clearErrors();
    },
    onError: (err) => {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: `Error en la petición: ${Object.values(err)[0]}`,
        life: 5000,
      });
    },
  });
};

const getTaxInfo = (event: SelectChangeEvent, index: number) => {
  const taxInfo: TaxBaseI | undefined = propsW.taxes.find((el) => el.uuid === event.value);
  form.info[index].tax_uuid = taxInfo?.uuid ?? '';
  productStore.setTaxRateFromPercent(Number(taxInfo?.rate) ?? 0);
};

const sumSubTotalByLine = () => {
  const discountTotal = form.info.reduce(
    (acc: number, curr: purchaseInfoI) => acc + curr.discount_amount,
    0
  );
  const subTotal = form.info.reduce((acc: number, curr: purchaseInfoI) => acc + curr.amount, 0);
  const taxTotal = form.info.reduce((acc: number, curr: purchaseInfoI) => acc + curr.tax, 0);

  form.tax = taxTotal;
  form.discount = discountTotal;
  form.sub_total = Number(PreciseCalculator.subtract(subTotal, taxTotal));
  form.amount = Number(PreciseCalculator.add(taxTotal, form.sub_total));
};

const calculateAmount = (index: number) => {
  const taxPercent = productStore.taxRate;
  const info = form.info[index];
  const cost = info.cost;
  const quantity = info.quantity;
  const discountRate = Number(PreciseCalculator.divide(info.discount_rate, 100));
  const taxPerProduct = PreciseCalculator.multiply(cost, taxPercent);

  form.info[index].tax = Number(PreciseCalculator.multiply(taxPerProduct.toString(), quantity));
  const base = PreciseCalculator.multiply(quantity, cost);
  const discountAmount = Number(PreciseCalculator.multiply(base.toString(), discountRate));

  form.info[index].discount_amount = discountAmount;
  form.info[index].amount = Number(PreciseCalculator.subtract(base.toString(), discountAmount));

  sumSubTotalByLine();
};

const addLine = () => {
  const info = form.info[form.info.length - 1];
  if (info.name === '' || info.amount === 0) {
    toast.add({
      severity: 'warn',
      summary: 'Información incompleta',
      detail: 'Por favor, complete la línea actual antes de agregar otra.',
      life: 5000,
    });
    return false;
  }
  form.info.push({
    uuid: '',
    code: '',
    name: '',
    quantity: 0,
    cost: 0,
    warehouse_uuid: '',
    tax_uuid: '',
    tax: 0,
    discount_rate: 0,
    amount: 0,
    discount_amount: 0,
  });
};

const destroy = (event: Event, index: number) => {
  if (form.info.length === 1) {
    toast.add({
      severity: 'info',
      summary: 'No se puede eliminar',
      detail: 'Debe haber al menos un elemento en la orden.',
      life: 3000,
    });
    return false;
  } else {
    confirm.require({
      target: event.currentTarget as HTMLElement,
      message: '¿Desea eliminar esta línea?',
      header: 'Confirmar Eliminación',
      icon: 'pi pi-exclamation-triangle',
      rejectProps: {
        label: 'Cancelar',
        severity: 'secondary',
        outlined: true,
      },
      acceptProps: {
        label: 'Eliminar',
        severity: 'danger',
      },
      accept: () => {
        form.info.splice(index, 1);
        sumSubTotalByLine();
        toast.add({
          severity: 'success',
          summary: 'Fila Eliminada',
          life: 3000,
        });
      },
    });
  }
};
</script>

<template>
  <AppLayout>
    <div class="w-full px-2 sm:px-4 py-4 max-w-7xl mx-auto">
      <Card class="shadow-sm rounded-lg border border-slate-200">
        <template #title>
          <div class="space-y-2">
            <Breadcrumb :model="purchaseBreadCrumb" class="text-xs sm:text-sm p-0 bg-transparent" />
            <h3 class="text-xl sm:text-2xl font-bold text-center text-slate-800">
              Orden de Compra
            </h3>
          </div>
          <Divider class="my-3" />
        </template>

        <template #content>
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Selección de Proveedor y Fecha -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <FloatLabel variant="on" class="w-full">
                <Select
                  id="supplier_id"
                  class="w-full"
                  :options="propsW.suppliers"
                  v-model="form.supplier_uuid"
                  optionValue="uuid"
                  optionLabel="company_name"
                  placeholder="Seleccionar Suplidor"
                />
                <label for="supplier_id">Suplidor / Proveedor</label>
              </FloatLabel>

              <FloatLabel variant="on" class="w-full">
                <DatePicker
                  dateFormat="yy-mm-dd"
                  v-model="form.doc_date"
                  id="doc_date"
                  class="w-full"
                />
                <label for="doc_date">Fecha Documento</label>
              </FloatLabel>
            </div>

            <!-- Tabla de Ítems (Con Scroll Horizontal Suave en Móviles) -->
            <div class="overflow-x-auto rounded-lg border border-slate-200 shadow-sm">
              <DataTable
                size="small"
                striped-rows
                show-gridlines
                :value="form.info"
                class="min-w-[850px] w-full"
              >
                <Column header="#" class="w-12 text-center">
                  <template #body="{ index }">
                    <span class="font-medium text-slate-600">{{ index + 1 }}</span>
                  </template>
                </Column>

                <Column header="Código" class="w-24">
                  <template #body="{ index }">
                    <span class="text-xs font-semibold text-slate-700">
                      {{ form.info[index].code || '-' }}
                    </span>
                  </template>
                </Column>

                <Column header="Producto/Servicio" class="min-w-[220px]">
                  <template #body="{ index }">
                    <AutoComplete
                      @option-select="getInfoName($event, index)"
                      @complete="searchProduct(index)"
                      option-label="name"
                      :suggestions="products"
                      v-model="form.info[index].name"
                      fluid
                      placeholder="Buscar producto..."
                    />
                  </template>
                </Column>

                <Column header="Cant." class="w-24">
                  <template #body="{ index }">
                    <InputNumber
                      locale="en-US"
                      :max-fraction-digits="2"
                      :min-fraction-digits="0"
                      @blur="calculateAmount(index)"
                      v-model="form.info[index].quantity"
                      fluid
                    />
                  </template>
                </Column>

                <Column header="Costo" class="w-28">
                  <template #body="{ index }">
                    <InputNumber
                      locale="en-US"
                      :max-fraction-digits="2"
                      :min-fraction-digits="2"
                      @blur="calculateAmount(index)"
                      v-model="form.info[index].cost"
                      fluid
                    />
                  </template>
                </Column>

                <Column header="Desc." class="w-20">
                  <template #body="{ index }">
                    <InputNumber
                      suffix="%"
                      :min="0"
                      :max="100"
                      @blur="calculateAmount(index)"
                      v-model="form.info[index].discount_rate"
                      fluid
                    />
                  </template>
                </Column>

                <Column header="Impuesto" class="w-28">
                  <template #body="{ index }">
                    <Select
                      @blur="calculateAmount(index)"
                      placeholder="Itbis"
                      :options="taxes"
                      @change="getTaxInfo($event, index)"
                      option-value="uuid"
                      option-label="name"
                      fluid
                    />
                  </template>
                </Column>

                <Column header="Almacén" class="w-28">
                  <template #body="{ index }">
                    <Select
                      placeholder="Alm."
                      :options="warehouses"
                      option-value="uuid"
                      option-label="name"
                      v-model="form.info[index].warehouse_uuid"
                      fluid
                    />
                  </template>
                </Column>

                <Column header="Importe" class="w-28">
                  <template #body="{ index }">
                    <InputNumber
                      locale="en-US"
                      :max-fraction-digits="2"
                      :min-fraction-digits="2"
                      v-model="form.info[index].amount"
                      readonly
                      fluid
                    />
                  </template>
                </Column>

                <Column header="Acción" class="w-16 text-center">
                  <template #body="{ index }">
                    <Button
                      @click="destroy($event, index)"
                      severity="danger"
                      outlined
                      class="h-8 w-8 p-0 flex items-center justify-center mx-auto"
                      title="Eliminar fila"
                    >
                      <Trash2 class="w-4 h-4 text-red-600" />
                    </Button>
                  </template>
                </Column>

                <template #footer>
                  <div class="text-center py-1">
                    <Button
                      @click="addLine"
                      label="Agregar Línea"
                      class="h-9 px-4 bg-emerald-600 hover:bg-emerald-700 border-none text-sm"
                    >
                      <template #icon>
                        <Plus class="w-4 h-4 mr-1" />
                      </template>
                    </Button>
                  </div>
                </template>
              </DataTable>
            </div>

            <!-- Resumen de Totales y Registrar -->
            <div
              class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pt-2"
            >
              <div
                class="w-full sm:w-80 bg-slate-50 text-slate-800 rounded-xl p-4 shadow-sm border border-slate-200 ml-auto"
              >
                <div class="flex justify-between mb-2 text-sm">
                  <span class="font-medium text-slate-600">Descuento:</span>
                  <span class="text-emerald-600 font-semibold">
                    {{ PreciseCalculator.formatCurrency(form.discount) }}
                  </span>
                </div>
                <div class="flex justify-between mb-2 text-sm">
                  <span class="font-medium text-slate-600">Impuestos:</span>
                  <span class="text-blue-600 font-semibold">
                    {{ PreciseCalculator.formatCurrency(form.tax) }}
                  </span>
                </div>
                <div class="flex justify-between mb-2 text-sm">
                  <span class="font-medium text-slate-600">Sub Total:</span>
                  <span class="text-slate-700 font-semibold">
                    {{ PreciseCalculator.formatCurrency(form.sub_total) }}
                  </span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-200 mt-2">
                  <span class="font-bold text-base text-slate-900">Total:</span>
                  <span class="font-bold text-lg text-emerald-700">
                    {{ PreciseCalculator.formatCurrency(form.amount) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Botón Final de Envío -->
            <div class="pt-4 flex justify-end">
              <Button
                :disabled="form.processing"
                type="submit"
                label="Registrar Orden"
                class="w-full sm:w-auto h-11 px-6 text-base font-medium"
              >
                <template #icon>
                  <Send class="w-4 h-4 mr-2" />
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
  padding: 0.35rem 0.5rem;
}
</style>
