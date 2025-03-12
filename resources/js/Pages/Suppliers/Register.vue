<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import FRegister from "@/Pages/Suppliers/FRegister.vue";
import {supplierI} from "@/Interfaces/Supplier";
import TabLink from "@components/TabLink.vue";


// Propiedades
const propsW = defineProps<{
    supplierEdit?: supplierI,
    update?: boolean
}>();

</script>

<template>
    <AppLayout>
        <template #header>
            <TabLink
                :active="true"
                :href="route('supplier.create')">
                Registrar
            </TabLink>
            <TabLink
                :href="route('supplier.show')">
                Mostrar
            </TabLink>
        </template>
        <div>
            <FRegister
                :supplierEdit="propsW.supplierEdit"
                :update="propsW.update"/>
        </div>
    </AppLayout>
</template>

<style scoped>

</style>
