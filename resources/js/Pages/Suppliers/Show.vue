<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import TabLink from "@components/TabLink.vue";
import FloatBox from "@components/FloatBox.vue";
import {ref} from "vue";
import FShow from "@/Pages/Suppliers/FShow.vue";
import {paginationI} from "@/Interfaces/Global";
import {supplierI} from "@/Interfaces/Supplier";
import FSee from "@/Pages/Suppliers/FSee.vue";



// Propiedades
const propsW = defineProps<{
    suppliers: paginationI<supplierI>
}>();


const seeSupplier = ref<boolean>(false);
const supplierData = ref<supplierI | null>(null);

// Funciones
const getDataSupplier = (item: supplierI) => {
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
