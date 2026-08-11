<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import { ref } from 'vue';
import {
  Breadcrumb,
  Button,
  Card,
  Column,
  DataTable,
  Dialog,
  Divider,
  Fieldset,
  Tag,
  useConfirm,
  useToast,
} from 'primevue';
import { PurchaseItemI, PurchaseSupplierI } from '@/Interfaces/PurchaseInterface';
import { purchaseBreadCrumb, PurchaseStatusSeverity } from '@/Helpers/PurchaseHelper';
import { getMoney } from '@/Global/Helpers';
import { PurchaseStatusEnum } from '@/Enums/PurchaseEnum';
import { router } from '@inertiajs/vue3';
import { Eye, ShoppingBag, CheckCircle, XCircle } from '@lucide/vue';

const confirm = useConfirm();
const toast = useToast();

const propsW = defineProps<{
  purchases: PurchaseSupplierI[];
}>();

const showPurchase = ref<boolean>(false);
const purchaseSelected = ref<PurchaseSupplierI | null>(null);

const getSeverityTag = (status: PurchaseStatusEnum) => {
  return PurchaseStatusSeverity[status] ?? 'secondary';
};

const selectPurchase = (purchase: PurchaseSupplierI | null) => {
  showPurchase.value = true;
  purchaseSelected.value = purchase;
};

const clearAll = () => {
  purchaseSelected.value = null;
  showPurchase.value = false;
};

const approve = () => {
  router.patch(
    route('purchase.approve', { purchase: purchaseSelected.value?.uuid }),
    {},
    {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Aprobado',
          detail: 'Orden aprobada correctamente.',
          life: 3000,
        });
        clearAll();
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error en la solicitud: ${Object.values(err)[0]}`,
          life: 5000,
        });
      },
    }
  );
};

const approveOrder = (event: Event) => {
  confirm.require({
    target: event.currentTarget as HTMLElement,
    message: '¿Desea aprobar esta orden de compra?',
    header: 'Aprobar Orden',
    icon: 'pi pi-info-circle',
    rejectProps: {
      label: 'Cancelar',
      severity: 'secondary',
      outlined: true,
    },
    acceptProps: {
      label: 'Aprobar',
    },
    accept: () => {
      approve();
    },
  });
};

const cancel = () => {
  router.patch(
    route('purchase.cancel', { purchase: purchaseSelected.value?.uuid }),
    {},
    {
      onSuccess: () => {
        toast.add({
          severity: 'success',
          summary: 'Cancelado',
          detail: 'Orden cancelada correctamente.',
          life: 3000,
        });
        clearAll();
      },
      onError: (err) => {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: `Error en la solicitud: ${Object.values(err)[0]}`,
          life: 5000,
        });
      },
    }
  );
};

const cancelOrder = (event: Event) => {
  confirm.require({
    target: event.currentTarget as HTMLElement,
    message: '¿Desea cancelar esta orden? Los cambios son irreversibles.',
    header: 'Cancelar Orden',
    icon: 'pi pi-exclamation-triangle',
    rejectProps: {
      label: 'Regresar',
      severity: 'secondary',
      outlined: true,
    },
    acceptProps: {
      severity: 'danger',
      label: 'Sí, Cancelar',
    },
    accept: () => {
      cancel();
    },
  });
};

const createReception = (data: PurchaseSupplierI) => {
  router.get(route('purchase.receiving.index', { supplier: data.supplier_uuid }));
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
              Órdenes de Compra
            </h3>
          </div>
          <Divider class="my-3" />
        </template>

        <template #content>
          <DataTable
            :value="propsW.purchases"
            responsiveLayout="stack"
            breakpoint="768px"
            paginator
            :rows="15"
            class="shadow-sm rounded-lg overflow-hidden border border-slate-200"
          >
            <Column header="ID" field="id" class="font-semibold text-slate-700" />

            <Column header="Proveedor" field="supplier.company_name" />

            <Column header="Ítems">
              <template #body="{ data }">
                <span>{{ data.items.length }} ítem(s)</span>
              </template>
            </Column>

            <Column header="Descuento">
              <template #body="{ data }">
                <span class="text-emerald-600 font-medium">{{ getMoney(data.discount) }}</span>
              </template>
            </Column>

            <Column header="ITBIS">
              <template #body="{ data }">
                <span class="text-blue-600 font-medium">{{ getMoney(data.tax) }}</span>
              </template>
            </Column>

            <Column header="Sub Total">
              <template #body="{ data }">
                <span>{{ getMoney(data.sub_total) }}</span>
              </template>
            </Column>

            <Column header="Total">
              <template #body="{ data }">
                <span class="font-bold text-slate-900">{{ getMoney(data.amount) }}</span>
              </template>
            </Column>

            <Column header="Estado">
              <template #body="{ data }: { data: PurchaseSupplierI }">
                <Tag :severity="getSeverityTag(data.status)" :value="data.status" />
              </template>
            </Column>

            <Column header="Acciones">
              <template #body="{ data }: { data: PurchaseSupplierI }">
                <div class="flex items-center gap-2 pt-1 sm:pt-0">
                  <Button
                    v-if="
                      data.status !== PurchaseStatusEnum.Borrador &&
                      data.status !== PurchaseStatusEnum.Completada
                    "
                    title="Entrada de Mercancía"
                    severity="info"
                    outlined
                    class="h-9 w-9 p-0 flex items-center justify-center"
                    @click="createReception(data)"
                  >
                    <template #icon>
                      <ShoppingBag class="w-4 h-4 text-blue-600" />
                    </template>
                  </Button>

                  <Button
                    title="Ver Detalle"
                    class="p-button-outlined p-button-sm h-9 w-9 p-0 flex items-center justify-center"
                    @click="selectPurchase(data)"
                  >
                    <template #icon>
                      <Eye class="w-4 h-4 text-slate-700" />
                    </template>
                  </Button>
                </div>
              </template>
            </Column>
          </DataTable>
        </template>
      </Card>
    </div>

    <!-- Modal Detalle de la Orden de Compra -->
    <Dialog
      v-model:visible="showPurchase"
      modal
      dismissableMask
      :header="`Detalle de Orden: ${purchaseSelected?.code ?? ''}`"
      :breakpoints="{ '960px': '85vw', '641px': '95vw' }"
      :style="{ width: '60vw' }"
      class="p-dialog-responsive mx-2 sm:mx-0"
      @hide="clearAll"
    >
      <div class="space-y-4 py-1">
        <!-- Estado de la Orden -->
        <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-lg border border-slate-200">
          <span class="font-semibold text-slate-700 text-sm">Estado de la Orden:</span>
          <Tag
            :severity="getSeverityTag(purchaseSelected?.status!!)"
            :value="purchaseSelected?.status"
          />
        </div>

        <!-- Datos del Proveedor -->
        <Fieldset legend="Información del Proveedor" class="text-sm">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-slate-700">
            <p><strong>Empresa:</strong> {{ purchaseSelected?.supplier.company_name }}</p>
            <p><strong>Teléfono:</strong> {{ purchaseSelected?.supplier.phone || 'N/A' }}</p>
            <p><strong>Correo:</strong> {{ purchaseSelected?.supplier.email || 'N/A' }}</p>
            <p><strong>Fecha Documento:</strong> {{ purchaseSelected?.doc_date }}</p>
          </div>
        </Fieldset>

        <!-- Tabla de Ítems (Con Scroll Horizontal para Móvil) -->
        <div class="overflow-x-auto rounded-lg border border-slate-200">
          <DataTable
            :value="purchaseSelected?.items"
            size="small"
            striped-rows
            class="min-w-175 w-full text-xs sm:text-sm"
          >
            <Column header="#" class="w-10 text-center">
              <template #body="{ index }">
                <span class="font-medium text-slate-500">{{ index + 1 }}</span>
              </template>
            </Column>

            <Column header="Producto" field="product_name" class="font-medium" />

            <Column header="Costo">
              <template #body="{ data }: { data: PurchaseItemI }">
                {{ getMoney(data.cost) }}
              </template>
            </Column>

            <Column header="Cant.">
              <template #body="{ data }: { data: PurchaseItemI }">
                {{ data.quantity }}
              </template>
            </Column>

            <Column header="ITBIS">
              <template #body="{ data }: { data: PurchaseItemI }"> {{ data.tax_rate }}% </template>
            </Column>

            <Column header="Descuento">
              <template #body="{ data }: { data: PurchaseItemI }">
                {{ getMoney(data.discount) }}
              </template>
            </Column>

            <Column header="Almacén" field="warehouse_name" />

            <Column header="Importe" class="font-bold">
              <template #body="{ data }: { data: PurchaseItemI }">
                {{ getMoney(data.amount) }}
              </template>
            </Column>
          </DataTable>
        </div>

        <!-- Resumen de Totales y Botones de Acción -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 pt-2">
          <div
            class="w-full sm:w-72 bg-slate-50 text-slate-800 rounded-xl p-3 border border-slate-200 ml-auto text-sm space-y-1.5"
          >
            <div class="flex justify-between">
              <span class="text-slate-600">Descuento:</span>
              <span class="text-emerald-600 font-medium">{{
                getMoney(purchaseSelected?.discount)
              }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-600">ITBIS:</span>
              <span class="text-blue-600 font-medium">{{ getMoney(purchaseSelected?.tax) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-600">Sub Total:</span>
              <span>{{ getMoney(purchaseSelected?.sub_total) }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-slate-200 font-bold text-base">
              <span>Total:</span>
              <span class="text-emerald-700">{{ getMoney(purchaseSelected?.amount) }}</span>
            </div>
          </div>
        </div>

        <!-- Botones de Acción del Modal -->
        <div class="pt-3 flex flex-col-reverse sm:flex-row justify-end gap-3">
          <Button
            @click="cancelOrder($event)"
            severity="warn"
            outlined
            label="Cancelar Orden"
            class="w-full sm:w-auto h-10"
          >
            <template #icon>
              <XCircle class="w-4 h-4 mr-1" />
            </template>
          </Button>

          <Button
            v-if="purchaseSelected?.status === PurchaseStatusEnum.Borrador"
            @click="approveOrder($event)"
            label="Aprobar Orden"
            class="w-full sm:w-auto h-10 bg-emerald-600 hover:bg-emerald-700 border-none"
          >
            <template #icon>
              <CheckCircle class="w-4 h-4 mr-1" />
            </template>
          </Button>
        </div>
      </div>
    </Dialog>
  </AppLayout>
</template>

<style scoped>
:deep(.p-datatable-tbody > tr > td) {
  padding: 0.75rem 1rem;
}

@media (max-width: 768px) {
  :deep(.p-datatable-stacked .p-datatable-tbody > tr > td) {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e2e8f0;
  }
}

:deep(.p-dialog-content) {
  padding: 1rem;
  max-height: 80vh;
  overflow-y: auto;
}

@media (max-width: 640px) {
  :deep(.p-dialog-content) {
    padding: 0.75rem;
    max-height: 85vh;
  }
}
</style>
