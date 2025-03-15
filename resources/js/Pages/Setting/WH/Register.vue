<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import TabLink from "@components/TabLink.vue";
import FRegister from "@/Pages/Setting/WH/FRegister.vue";
import FShow from "@/Pages/Setting/WH/FShow.vue";
import {warehouseBaseI} from "@/Interfaces/Warehouse";


/*
Propiedades
 */
const propsW = defineProps<{
    warehouse: warehouseBaseI[]
}>();



</script>

<template>
    <Head title="Almacenes" />
    <AppLayout>
        <template #header>
            <TabLink
                :href="route('setting.index')">
                Ajustes
            </TabLink>
            <TabLink
                :href="route('aco.index')">
                Cuentas
            </TabLink>
            <TabLink
                :active="true"
                :href="route('wh.index')">
                Almacen
            </TabLink>
        </template>

        <!--        Contenido de la vantana-->
        <div class="">
            <FRegister
                class="w-full"
                />

            <FShow
                :warehouse="propsW.warehouse"/>



        </div>
    </AppLayout>
</template>
