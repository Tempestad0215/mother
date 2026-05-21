<script setup lang="ts">
import { inject, ref } from 'vue';
import { categoryBaseI } from '@/Interfaces/CategoriesInterface';
import { SupplierI } from '@/Interfaces/SupplierInterface';
import { useRoute } from 'ziggy-js';
import { formProductKey, productDataKey } from '@/Injections/InjectionKeys';
import { AutoComplete, Button, Dialog, FloatLabel, InputText, Select, useToast } from 'primevue';
import { router } from '@inertiajs/vue3';
import FRegisterCategory from '@/Pages/Categories/FRegister.vue';
import FRegisterSupplier from '@/Pages/Suppliers/FRegister.vue';
import { PaymentTypeEnumI } from '@/Interfaces/GlobalInterface';
import { CirclePlus, Printer } from '@lucide/vue';
import axios from 'axios';

/**
 *
 */
const route = useRoute();
const toast = useToast();

/**
 *
 */
const propsW = defineProps<{
  categories: categoryBaseI[];
  suppliers: SupplierI[];
  paymentTypes: PaymentTypeEnumI;
  code?: string;
}>();

/**
 *
 */
const form = inject(formProductKey)!!;
const productDataOption = inject(productDataKey);
const createCategory = ref(false);
const createSupplier = ref(false);

/**
 *
 */
const searchProduct = () => {
  router.get(
    route('product.index', { search: form.name }),
    {},
    {
      preserveState: true,
      preserveScroll: true,
      replace: true,
    }
  );
};

/**
 *
 */
const printLabel = async () => {
  // Verificar si existe el codigo
  if (propsW.code) {
    try {
      //
      const response = await axios.get(route('product.get-label', { code: propsW.code }), {
        responseType: 'blob',
      });

      const blob = new Blob([response.data], { type: 'application/pdf' });

      const url = window.URL.createObjectURL(blob);

      const iframe = document.createElement('iframe');
      iframe.style.display = 'none';
      iframe.src = url;

      document.body.appendChild(iframe);

      // 4. Esperamos a que el iframe cargue el PDF y disparamos la ventana de impresión
      iframe.onload = () => {
        iframe.contentWindow?.focus();
        iframe.contentWindow?.print();

        // 5. Limpieza: Removemos el iframe y liberamos la memoria del Blob un momento después
        setTimeout(() => {
          window.URL.revokeObjectURL(url);
          document.body.removeChild(iframe);
        }, 3000);
      };
    } catch (_) {
      toast.add({
        summary: 'Error en la peticion',
        detail: 'No se ha podido imprimir el label del producto',
        severity: 'error',
        life: 500,
      });
    }
  }
};

// const printLabel = async (code:string | null) => {
//     if (code !== null) {
//         try {
//             loadingUrlLabel.value = true;
//             const {data} = await axios.get(route(`product.get-label`, {code: code}))
//
//
//             // 2) Crear iframe oculto
//             const iframe = document.createElement('iframe')
//             iframe.style.position = 'fixed'
//             iframe.style.right = '0'
//             iframe.style.bottom = '0'
//             iframe.style.width = '0'
//             iframe.style.height = '0'
//             iframe.style.border = '0'
//             iframe.style.zIndex = '999999'
//             iframe.src = data.url
//
//
// // 3) Cuando cargue, imprimir y luego eliminar
//             iframe.onload = () => {
//                 try {
//                     iframe.contentWindow?.focus()
//                     iframe.contentWindow?.print()
//                 } finally {
//                     // A veces conviene esperar un poco para que el print se dispare
//                     // setTimeout(() => iframe.remove(), 500)
//                 }
//             }
//
//             document.body.appendChild(iframe)
//
//             // urlLabel.value = data.url;
//             // openPrintDialog.value = true;
//         }catch(error) {
//             toast.add({
//                 severity: 'warn',
//                 summary: 'Error',
//                 detail: "Erro al Intentar Obtener El Label",
//                 life: 5000
//             });
//         }finally {
//             loadingUrlLabel.value = false;
//         }
//
//     }
// }
</script>

<template>
  <div class="">
    <div class="">
      <!--                <div class="flex items-center gap-3">-->
      <!--                    <p>ID Siguiente : {{productStore.nextCode}}</p>-->
      <!--                    <Button title="Imprimir" :disabled="loadingUrlLabel" @click="printLabel(productStore.nextCode)"  >-->
      <!--                        <template #icon>-->
      <!--                            <Printer />-->
      <!--                        </template>-->
      <!--                    </Button>-->
      <!--                </div>-->
      <div class="grid grid-cols-2">
        <div>
          <button type="button" @click="printLabel()" class="bg-green-300 p-2 rounded-md">
            <Printer />
          </button>
        </div>

        <div class="text-right space-x-3">
          <Button @click="createCategory = true" label="Crear Categoria">
            <template #icon>
              <CirclePlus />
            </template>
          </Button>
          <Button @click="createSupplier = true" label="Crear Suplidor">
            <template #icon>
              <CirclePlus />
            </template>
          </Button>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
      <FloatLabel variant="on">
        <AutoComplete
          fluid
          :suggestions="productDataOption"
          option-label="name"
          @valueChange="searchProduct"
          id="name"
          v-model="form.name"
        />
        <label for="name">Nombre</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <InputText fluid id="description" v-model="form.description" />
        <label for="description">Descripcion</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <Select
          fluid
          v-model="form.category_uuid"
          option-value="uuid"
          id="category"
          option-label="name"
          :options="categories"
        />
        <label for="category">Categoria</label>
      </FloatLabel>
      <FloatLabel variant="on">
        <Select
          fluid
          v-model="form.supplier_uuid"
          option-value="uuid"
          id="supplier"
          option-label="company_name"
          :options="suppliers"
        />
        <label for="supplier">Suplidor</label>
      </FloatLabel>
    </div>
  </div>

  <Dialog v-model:visible="createCategory" modal header="Crear Categoria">
    <FRegisterCategory :category-edit="null" />
  </Dialog>
  <Dialog v-model:visible="createSupplier" modal header="Crear Suplidor">
    <FRegisterSupplier :paymentTypes="paymentTypes" :update="false" :supplierEdit="null" /> />
  </Dialog>
</template>
