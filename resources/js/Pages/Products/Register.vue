<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import {onMounted, provide, ref} from 'vue';
import {SupplierI} from "@/Interfaces/SupplierInterface";
import {ProductBaseI, ProductTypeEnumI} from "@/Interfaces/ProductInterface";
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {WarehouseBaseI} from "@/Interfaces/WarehouseInterface";
import {
    Button,
    Column,
    DataTable,
    Dialog,
    InputGroup,
    InputGroupAddon,
    InputText,
    Breadcrumb,
    useToast,
    useConfirm,
    Card
} from "primevue";
import Pagination from "@components/Pagination.vue";
import {PaginationI, PaymentTypeEnumI} from "@/Interfaces/GlobalInterface";
import FRegister from "@/Pages/Products/FRegister.vue";
import {productDataKey, taxCurrentValueKey} from "@/Injections/InjectionKeys";
import {BranchInterfaceI} from "@/Interfaces/BranchInterface";
import {UnitInterfaceI} from "@/Interfaces/UnitInterface";
import {TaxInterfaceI} from "@/Interfaces/TaxInterface";
import {PreciseCalculator} from "@/utils/Decimal";
import {router} from "@inertiajs/vue3";
import {productBreadCrumb} from "@/Helpers/ProductHelper";
import {useProductStore} from "@/stores/ProductStore";



const toast = useToast()
const confirm = useConfirm()

//Propiedades de la ventana
const propsW = defineProps<{
    products: PaginationI<ProductBaseI>
    productEdit? : ProductBaseI,
    update? : boolean,
    categories: categoryBaseI[],
    suppliers: SupplierI[],
    warehouse: WarehouseBaseI[],
    nextProduct: string | null,
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

//store
const productStore = useProductStore()

onMounted(()=>{
    if(propsW.nextProduct)
    {
        productStore.nextCode = propsW.nextProduct
    }
})

const searchData = () => {

}


const editData = (data:ProductBaseI) => {
    selectedProduct.value = data;
    isUpdate.value = true
    createProduct.value = true
    productStore.nextCode = data.code;
}

const deleteData = (data:ProductBaseI, event:Event) => {
    confirm.require({
        target: event.currentTarget as HTMLElement,
        message: "Desea eliminar este registro, los cambios son irreversible",
        rejectProps:{
            label: "Cancelar",
            severity: "secondary",
            outlined: true

        },
        acceptProps:{
            label: "Eliminar",

        },
        accept: () => {
            router.delete(route('un.destroy', {client: data.id}),{
                onSuccess: () => {
                    toast.add({
                        severity: "success",
                        summary: "Eliminado ",
                        detail: "El Registro Fue Eliminado Correctamente.",
                        life: 3000
                    })
                }
            })
        }

    })
}

const clearCreate = ()=>{
    selectedProduct.value = null
    isUpdate.value = false
    productStore.nextCode = propsW.nextProduct || null
}

</script>



<template>
    <!-- Contenido de la ventana -->
    <AppLayout>
        <Card>
            <template #title>
                <div>
                    <Breadcrumb :model="productBreadCrumb" />
                </div>
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
            <template #content>
                <DataTable
                    paginator
                    :rows="propsW.products.per_page ?? 0"
                    :loading="!propsW.products.data"
                    :value="propsW.products.data" >
                    <Column field="code" header="Codigo"  />
                    <Column field="name" header="Nombre"  />
                    <Column :field="(data:ProductBaseI) => `${PreciseCalculator.formatCurrency(data.cost)}`" header="Costo"  />
                    <Column :field="(data:ProductBaseI) => `${PreciseCalculator.formatCurrency(data.price)}`" header="Precio"  />
                    <Column :field="(data:ProductBaseI) => `${data.is_service ? 'Servicio' : 'Producto'}`" header="Tipo"  />
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
            </template>
        </Card>

        <Dialog
            modal
            @hide="clearCreate"
            v-model:visible="createProduct"
            header="Registro de Producto">
            <FRegister
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
