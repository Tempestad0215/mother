<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import {provide, ref} from 'vue';
import {supplierI} from "@/Interfaces/SupplierInterface";
import {ProductBaseI, ProductTypeEnumI} from "@/Interfaces/ProductInterface";
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {WarehouseBaseI} from "@/Interfaces/WarehouseInterface";
import {useRoute} from "ziggy-js";
import {Button, Column, DataTable, Dialog, InputGroup, InputGroupAddon, InputText} from "primevue";
import Pagination from "@components/Pagination.vue";
import {PaginationI, PaymentTypeEnumI} from "@/Interfaces/GlobalInterface";
import FRegister from "@/Pages/Products/FRegister.vue";
import {productDataKey, taxCurrentValueKey} from "@/Injections/InjectionKeys";
import {BranchInterfaceI} from "@/Interfaces/BranchInterface";
import {UnitInterfaceI} from "@/Interfaces/UnitInterface";
import {TaxInterfaceI} from "@/Interfaces/TaxInterface";


const route = useRoute()

//Propiedades de la ventana
const propsW = defineProps<{
    products: PaginationI<ProductBaseI>
    productEdit? : ProductBaseI,
    update? : boolean,
    categories: categoryBaseI[],
    suppliers: supplierI[],
    warehouse: WarehouseBaseI[],
    nextProduct?: number,
    paymentTypes: PaymentTypeEnumI,
    productType: ProductTypeEnumI,
    branches: BranchInterfaceI[]
    units: UnitInterfaceI[]
    taxes: TaxInterfaceI[]
}>();

const taxCurrentValue = ref(0)
//Mostrar la ventana de suplidores
const selectedProduct = ref<ProductBaseI | null>(null);
const searchValue = ref("")
const createProduct = ref(false)
const isUpdate = ref(false)

provide(productDataKey, propsW.products.data ?? [])
provide(taxCurrentValueKey, taxCurrentValue)






const searchData = () => {

}


const editData = (data:ProductBaseI) => {
    selectedProduct.value = data;
    isUpdate.value = true
}

const deleteData = (data:ProductBaseI, event:Event) => {

}

</script>



<template>
    <!-- Contenido de la ventana -->
    <AppLayout>
        <DataTable
            paginator
            :rows="propsW.products.per_page ?? 0"
            :loading="!propsW.products.data"
            :value="propsW.products.data" >
            <template #header>
                <div class="flex justify-between items-center">
                    <form @submit.prevent="searchData">
                        <InputGroup class="max-w-60">
                            <InputText v-model="searchValue" placeholder="Buscar" type="search" />
                            <InputGroupAddon
                                @click="searchData">
                                <i  class="pi pi-search" ></i>
                            </InputGroupAddon>
                        </InputGroup>
                    </form>
                    <Button
                        label="Agregar Producto"
                        class="h-8"
                        @click="createProduct = true" />
                </div>

            </template>
            <Column field="code" header="Codigo"  />
            <Column field="name" header="Nombre"  />
            <Column field="rnc" header="RNC"  />
            <Column field="phone" header="Telefono"  />
            <Column field="email" header="Correo"  />
            <Column header="Act">
                <template #body="{data}:{data:ProductBaseI}">
                    <div class="space-x-2">
                        <Button @click="editData(data)" class="pt-1 h-8"  title="Editar" icon="pi pi-file-edit" />
                        <Button @click="deleteData(data, $event)" class="pt-1 h-8"  title="Eliminar" severity="danger" icon="pi pi-trash" />

                    </div>
                </template>
            </Column>
            <template #paginatorcontainer>
                <Pagination
                    :search="searchValue"
                    :pag="propsW.products"
                />
            </template>
        </DataTable>
        <Dialog
            modal
            @hide="selectedProduct = null"
            v-model:visible="createProduct"
            header="Registro de Producto">
            <FRegister
                :units="propsW.units"
                :branches="propsW.branches"
                :productType="propsW.productType"
                :paymentTypes="paymentTypes"
                :categories="propsW.categories"
                :suppliers="propsW.suppliers"
                :warehouse="propsW.warehouse"
                :productEdit="selectedProduct"
                :update="isUpdate"
                />
        </Dialog>
    </AppLayout>
</template>
