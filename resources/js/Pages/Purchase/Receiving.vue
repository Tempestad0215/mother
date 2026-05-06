<script setup lang="ts">
import {
  InputText,
  Breadcrumb,
  Button,
  Card,
  Column,
  DataTable,
  DatePicker,
  Dialog,
  FloatLabel,
  InputGroup,
  InputGroupAddon,
  InputNumber,
  Select,
  Textarea,
  useConfirm,
  useToast,
} from 'primevue';
import AppLayout from '@layout/AppLayout.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
  faArrowAltCircleDown,
  faArrowAltCircleUp,
  faEdit,
  faTruckField,
} from '@fortawesome/free-solid-svg-icons';
import { MoveDirectionEdit, PaginationI } from '@/Interfaces/GlobalInterface';
import {
  PurchaseBaseI,
  PurchaseFormI,
  PurchaseItemI,
  PurchaseSupplierI,
} from '@/Interfaces/PurchaseInterface';
import { SupplierI } from '@/Interfaces/SupplierInterface';
import { computed, onMounted, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { PurchaseStatusEnum } from '@/Enums/PurchaseEnum';
import { getMoney } from '@/Global/Helpers';
import { PreciseCalculator } from '@/utils/Decimal';
import { purchaseBreadCrumb } from '@/Helpers/PurchaseHelper';

const toast = useToast();
const confirm = useConfirm();

interface PropsI {
  purchases: PaginationI<PurchaseSupplierI>;
  suppliers: SupplierI[];
  purchaseAvailable: PurchaseSupplierI[] | null;
  purchaseStatus: {
    name: string;
    value: string;
  }[];
}

const propsW = withDefaults(defineProps<PropsI>(), {
  purchaseAvailable: null,
});

const searchSupplier = ref('');
const showPurchaseAvailable = ref(false);
const purchaseAvailable = ref<PurchaseBaseI | null>(null);
const docDate = ref<Date | null>(new Date());
const editItem = ref(false);
const itemToEdit = ref<PurchaseItemI | null>(null);
const lastIndex = ref(0);

const form = useForm<PurchaseFormI>({
  id: 0,
  code: '',
  supplier_id: 0,
  user_id: 0,
  supplier_name: '',
  items: [],
  doc_date: '',
  amount: 0,
  tax: 0,
  discount: 0,
  sub_total: 0,
  comment: '',
  status: PurchaseStatusEnum.Parcial,
});

onMounted(() => {
  getDate(new Date());

  if (propsW.purchaseAvailable && propsW.purchaseAvailable?.length > 1) {
    console.log('Existe mas de uno, por favor elije');
  } else {
    //Verifica que exista el available
    if (!propsW.purchaseAvailable) return;
    // Tomar en una variable
    const info = propsW.purchaseAvailable;

    // Verificar si existe o no
    if (!info) return;

    const data = info[0];
    // Tomar el primer registro
    Object.assign(form, data);
    form.status = PurchaseStatusEnum.Completada;
  }
});

const getSupplierName = computed(() => {
  return form.supplier?.company_name ?? '';
});

const maxIndex = computed(() => form.items.length - 1);
const minIndex = computed(() => 0);

const deleteItem = () => {
  const data = form.items[lastIndex.value];
  if (!data) return;

  if (data.quantity === 0) {
    confirm.require({
      message: `¿Estás seguro de que deseas eliminar este artículo : ${data.product_name}?`,
      header: 'Confirmar eliminación',
      icon: 'pi pi-exclamation-triangle',
      accept: () => {
        editItem.value = false;
        form.items.splice(lastIndex.value, 1);
        calculateAmount(maxIndex.value);

        toast.add({
          severity: 'success',
          summary: 'Éxito',
          detail: 'Artículo eliminado correctamente',
          life: 3000,
        });
      },
      acceptProps: {
        label: 'Eliminar',
        severity: 'danger',
      },
      rejectProps: {
        label: 'Cancelar',
        outlined: true,
      },
    });
  } else {
    calculateAmount(lastIndex.value);
  }
};

const sumSubTotalByLine = () => {
  const discountTotal = form.items.reduce(
    (acc: number, curr: PurchaseItemI) => Number(PreciseCalculator.add(acc, curr.discount)),
    0
  );
  const subTotal = form.items.reduce(
    (acc: number, curr: PurchaseItemI) => Number(PreciseCalculator.add(acc, curr.amount)),
    0
  );
  const taxTotal = form.items.reduce(
    (acc: number, curr: PurchaseItemI) => Number(PreciseCalculator.add(acc, curr.tax_amount)),
    0
  );

  form.tax = taxTotal;
  form.discount = discountTotal;

  form.sub_total = Number(PreciseCalculator.subtract(subTotal, taxTotal));

  form.amount = Number(PreciseCalculator.add(taxTotal, form.sub_total));
};

const calculateAmount = (index: number) => {
  const info = form.items[index];
  const taxPercent = PreciseCalculator.divide(info.tax_rate, 100);
  const cost = info.cost;
  const quantity = info.quantity;
  const discountRate = Number(PreciseCalculator.divide(info.discount, 100));

  const taxPerProduct = PreciseCalculator.multiply(cost, taxPercent.toString());
  form.items[index].tax_amount = Number(
    PreciseCalculator.multiply(taxPerProduct.toString(), quantity)
  );
  const base = PreciseCalculator.multiply(quantity, cost);
  const discountAmount = Number(PreciseCalculator.multiply(base.toString(), discountRate));

  form.items[index].discount = discountAmount;
  form.items[index].amount = Number(PreciseCalculator.subtract(base.toString(), discountAmount));

  sumSubTotalByLine();
};

const getDate = (date: Date) => {
  form.doc_date = date.toISOString();
};

const submit = () => {
  form.post(route('purchase.receiving.store'), {
    onSuccess: () => {
      toast.add({
        severity: 'success',
        summary: 'Exito',
        detail: 'Recepcion de Mercancia Registrada Exitosamente',
        life: 5000,
      });
      form.reset();
      searchSupplier.value = '';
    },
    onError: (err) => {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: `Error en Esta Peticion, Detalle : ${Object.values(err)[0]}`,
        life: 5000,
      });
    },
  });
};

const showEditItem = () => {
  //
  if (lastIndex.value < 0 || lastIndex.value >= form.items.length) return;
  // Abrir la ventana
  lastIndex.value = maxIndex.value;
  editItem.value = true;
  itemToEdit.value = form.items[lastIndex.value];
};

const moveEditItem = (direction: MoveDirectionEdit) => {
  const currentIndex = lastIndex.value;

  if (direction === 'DOWN') {
    if (currentIndex >= maxIndex.value) return;
    lastIndex.value += 1;
  }

  if (direction === 'UP') {
    if (currentIndex <= minIndex.value) return;
    lastIndex.value -= 1;
  }
};
</script>

<template>
  <AppLayout>
    <Card>
      <template #title>
        <div>
          <Breadcrumb :model="purchaseBreadCrumb" />
        </div>
        <h3 class="text-center text-2xl font-bold">Recepcion de Marcancia</h3>
      </template>
      <template #content>
        <form @submit.prevent="submit">
          <div class="flex items-center justify-between">
            <div>
              <InputGroup>
                <InputText v-model="getSupplierName" />
                <InputGroupAddon>
                  <FontAwesomeIcon
                    title="Mostrar Suplidores"
                    class="text-3xl"
                    :icon="faTruckField"
                  />
                </InputGroupAddon>
              </InputGroup>
            </div>

            <div class="flex gap-3">
              <div class="w-50">
                <FloatLabel variant="on">
                  <Select
                    v-model="form.status"
                    fluid
                    optionLabel="name"
                    optionValue="value"
                    :options="propsW.purchaseStatus"
                  />
                  <label for="">Estado Recepcion</label>
                </FloatLabel>
              </div>

              <div>
                <FloatLabel variant="on">
                  <DatePicker @dateSelect="getDate" v-model="docDate" />
                  <label for="">Fecha</label>
                </FloatLabel>
              </div>
            </div>
          </div>

          <DataTable :value="form.items">
            <Column header="#">
              <template #body="{ index }">
                {{ index + 1 }}
              </template>
            </Column>
            <Column field="product_name" header="Producto/Servicio" />
            <Column header="Cantidad">
              <template #body="{ index, data }: { index: number; data: PurchaseItemI }">
                <InputNumber
                  @blur="calculateAmount(index)"
                  :readonly="data.isReadOnly"
                  locale="en-US"
                  :minFractionDigits="2"
                  :maxFractionDigits="2"
                  v-model="form.items[index].quantity"
                />
              </template>
            </Column>
            <Column header="Costo">
              <template #body="{ data }: { data: PurchaseItemI }">
                {{ getMoney(data.cost) }}
              </template>
            </Column>
            <Column header="Itbis">
              <template #body="{ data }: { data: PurchaseItemI }">
                {{ getMoney(data.tax_amount) }}
              </template>
            </Column>
            <Column field="warehouse_name" header="Almacen" />
            <Column header="Descuento">
              <template #body="{ data }: { data: PurchaseItemI }">
                {{ getMoney(data.discount) }}
              </template>
            </Column>
            <Column header="Importe">
              <template #body="{ data }: { data: PurchaseItemI }">
                {{ getMoney(data.amount) }}
              </template>
            </Column>
            <template #footer>
              <div class="text-center">
                <FontAwesomeIcon
                  @click="showEditItem"
                  class="text-3xl text-orange-500 hover:scale-110 duration-300"
                  title="Editar"
                  :icon="faEdit"
                />
              </div>
            </template>
          </DataTable>
          <div class="flex items-center justify-between">
            <div>
              <FloatLabel variant="on">
                <Textarea
                  :cols="30"
                  :rows="2"
                  class="max-w-60 min-w-20 min-h-15 max-h-30"
                  v-model="form.comment"
                />
                <label for="">Comentario</label>
              </FloatLabel>
            </div>
            <div class=" ">
              <p>Descuento : {{ getMoney(form.discount) }}</p>
              <p>Itbis : {{ getMoney(form.tax) }}</p>
              <p>Sub Total : {{ getMoney(form.sub_total) }}</p>
              <p class="text-white bg-blue-800 rounded-md px-6 py-1">
                Total : {{ getMoney(form.amount) }}
              </p>
            </div>
          </div>

          <div class="mt-3 space-x-3 text-right">
            <Button severity="warn" outlined icon="pi pi-exclamation-triangle" label="Cancelar" />
            <Button
              @click="submit"
              :disabled="form.processing"
              icon="pi pi-send"
              label="Registrar"
            />
          </div>
        </form>
      </template>
    </Card>

    <Dialog
      :header="`Compra Disponible Para Recibir de: ${form.supplier_name}`"
      v-model:visible="showPurchaseAvailable"
      modal
    >
      <DataTable :value="purchaseAvailable">
        <Column field="code" header="Codigo" />
        <Column field="tax" header="Itbis" />
        <Column field="discount" header="Descuento" />
        <Column field="sub_total" header="Sub Total" />
        <Column header="Act">
          <template #body>
            <Button icon="pi pi-check" />
          </template>
        </Column>
      </DataTable>
    </Dialog>

    <!--        Para poder editar los datos y borrar en 0-->
    <Dialog
      class=""
      :header="`Editando El Item :${itemToEdit?.product_name}`"
      v-model:visible="editItem"
      modal
    >
      <div class="mt-5">
        <div class="flex gap-5 items-center">
          <FloatLabel variant="on">
            <InputNumber @blur="deleteItem" v-model="form.items[lastIndex].quantity" />
            <label for="quantity">Cantidad</label>
          </FloatLabel>
          <div class="text-2xl space-x-3">
            <FontAwesomeIcon
              @click="moveEditItem('DOWN')"
              title="Bajar"
              :icon="faArrowAltCircleDown"
            />
            <FontAwesomeIcon @click="moveEditItem('UP')" title="Subir" :icon="faArrowAltCircleUp" />
          </div>
        </div>
      </div>
    </Dialog>
  </AppLayout>
</template>
