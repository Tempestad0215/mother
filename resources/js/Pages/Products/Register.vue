<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import {onMounted, provide, ref} from 'vue';
import {SupplierI} from "@/Interfaces/SupplierInterface";
import {ProductBaseI, ProductTypeEnumI} from "@/Interfaces/ProductInterface";
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {WarehouseBaseI} from "@/Interfaces/WarehouseInterface";
import {
    Dialog,
} from "primevue";
import {PaginationI, PaymentTypeEnumI} from "@/Interfaces/GlobalInterface";
import FRegister from "@/Pages/Products/FRegister.vue";
import {productDataKey, taxCurrentValueKey} from "@/Injections/InjectionKeys";
import {BranchInterfaceI} from "@/Interfaces/BranchInterface";
import {UnitInterfaceI} from "@/Interfaces/UnitInterface";
import {TaxInterfaceI} from "@/Interfaces/TaxInterface";
import {useProductStore} from "@/stores/ProductStore";
import FShowProduct from "@/Pages/Products/FShowProduct.vue";
import {PriceListWTI} from "@/Interfaces/PriceListInterface";


//Propiedades de la ventana
const propsW = defineProps<{
    products: PaginationI<ProductBaseI>
    productEdit? : ProductBaseI,
    update? : boolean,
    categories: categoryBaseI[],
    suppliers: SupplierI[],
    warehouses: WarehouseBaseI[],
    nextProduct: string | null,
    paymentTypes: PaymentTypeEnumI,
    productType: ProductTypeEnumI
    branches: BranchInterfaceI[]
    units: UnitInterfaceI[]
    taxes: TaxInterfaceI[]
    priceLists: Array<PriceListWTI>
}>();


const taxCurrentValue = ref(0)
//Mostrar la ventana de suplidores
const selectedProduct = ref<ProductBaseI | null>(null);
const createProduct = ref(false)
const isUpdate = ref(false)

provide(productDataKey, propsW.products.data ?? [])
provide(taxCurrentValueKey, taxCurrentValue)

//store
const productStore = useProductStore()

onMounted(()=>{
    if(propsW.nextProduct)
    {
        productStore.nextCode = propsW.nextProduct
    }
})

const clearCreate = ()=>{
    selectedProduct.value = null
    isUpdate.value = false
    productStore.nextCode = propsW.nextProduct || null
}

</script>



<template>
    <!-- Contenido de la ventana -->
    <AppLayout>
        <FShowProduct

            v-model:createProduct="createProduct"
            v-model:selectedProduct="selectedProduct"
            :products="propsW.products"/>

        <Dialog
            modal
            @hide="clearCreate"
            v-model:visible="createProduct"
            header="Registro de Producto">
            <FRegister
                :priceLists="propsW.priceLists"
                :warehouses="propsW.warehouses"
                :units="propsW.units"
                :branches="propsW.branches"
                :productType="propsW.productType"
                :paymentTypes="paymentTypes"
                :categories="propsW.categories"
                :suppliers="propsW.suppliers"
                :productEdit="selectedProduct"
                :update="isUpdate"
                />
        </Dialog>
    </AppLayout>
</template>
