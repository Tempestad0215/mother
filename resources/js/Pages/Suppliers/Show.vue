<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import TabLink from "@components/TabLink.vue";
import FloatBox from "@components/FloatBox.vue";
import {ref} from "vue";
import FShow from "@/Pages/Suppliers/FShow.vue";
import {PaginationI} from "@/Interfaces/GlobalInterface";
import {SupplierI} from "@/Interfaces/SupplierInterface";
import FSee from "@/Pages/Suppliers/FSee.vue";
import {useRoute} from "ziggy-js";


const route = useRoute();
// Propiedades
const propsW = defineProps<{
    suppliers: PaginationI<SupplierI>
}>();


const seeSupplier = ref<boolean>(false);
const supplierData = ref<SupplierI | null>(null);

// Funciones
const getDataSupplier = (item: SupplierI) => {
    seeSupplier.value = true;
    supplierData.value = item;
}

</script>

<template>
    <AppLayout>
        <template #header>
            <TabLink
                :href="route('supplier.create')">
                Registrar
            </TabLink>
            <TabLink
                :active="true"
                :href="route('supplier.show')">
                Mostrar
            </TabLink>
        </template>

        <div>
            <FShow
                @seeSupplier="getDataSupplier"
                :suppliers="propsW.suppliers"/>
        </div>

    </AppLayout>


<!--    Mostrar la ventana flotante-->
    <FloatBox
        :header="`Ver Suplidor : ${supplierData?.company_name}`"
        @close="seeSupplier = false"
        v-if="seeSupplier">
        <FSee
            :supplier="supplierData"/>
    </FloatBox>

</template>
