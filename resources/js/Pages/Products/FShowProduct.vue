<script setup lang="ts">
import Pagination from "@components/Pagination.vue";
import {ProductBaseI} from "@/Interfaces/ProductInterface";
import {router, usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import {useRoute} from "ziggy-js";
import {
    Breadcrumb,
    Button,
    Card,
    Column,
    DataTable,
    InputGroup,
    InputGroupAddon,
    InputText,
    useConfirm,
    useToast
} from "primevue";
import {useProductStore} from "@/stores/ProductStore";
import {productBreadCrumb} from "@/Helpers/ProductHelper";
import {PreciseCalculator} from "@/utils/Decimal";
import {PaginationI} from "@/Interfaces/GlobalInterface";


const toast = useToast()
const confirm = useConfirm()
const route = useRoute();
/**
 * Informacion de la ventana
 */
const {url, component, props} = usePage();
const {auth} = props;


interface PropsI {
    products: PaginationI<ProductBaseI>
    stock?: boolean,
    isProduct?:boolean
}
/**
 * Propiedades de la ventana
 */
const propsW = withDefaults(defineProps<PropsI>(),{
    stock: false,
    isProduct: true
})


//store
const productStore = useProductStore()


const selectedProduct = defineModel<ProductBaseI | null>('selectedProduct', {
    default: null
})
const searchValue = ref("")
const createProduct = defineModel<boolean>('createProduct',{
    default: false
})
const isUpdate = ref(false)

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


</script>

<template>
    <Card>
        <template #title>
            <div v-if="propsW.isProduct">
                <Breadcrumb :model="productBreadCrumb" />
            </div>
            <div>
                <h3 class="text-center">Productos</h3>
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
            {{component}}
            <DataTable
                paginator
                :rows="propsW.products.per_page ?? 0"
                :loading="!propsW.products.data"
                :value="propsW.products.data" >
                <Column field="code" header="Codigo"  />
                <Column field="name" header="Nombre"  />
                <Column v-if="component === 'Products/Register'" :field="(data:ProductBaseI) => `${PreciseCalculator.formatCurrency(data.cost)}`" header="Costo"  />
                <Column :field="(data:ProductBaseI) => `${PreciseCalculator.formatCurrency(data.price)}`" header="Precio"  />
                <Column :field="(data:ProductBaseI) => `${data.is_service ? 'Servicio' : 'Producto'}`" header="Tipo"  />
                <Column field="stock" header="Stock" v-if="propsW.stock" />
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
</template>

