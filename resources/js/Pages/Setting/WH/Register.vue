<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import TabLink from "@components/TabLink.vue";
import FRegister from "@/Pages/Setting/WH/FRegister.vue";
import FShow from "@/Pages/Setting/WH/FShow.vue";
import {warehouseBaseI} from "@/Interfaces/WarehouseInterface";
import {reactive} from "vue";


/*
Propiedades
 */
const propsW = defineProps<{
    warehouse: warehouseBaseI[]
}>();


const state = reactive({
    editWareHouse: null as warehouseBaseI | null,
})


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
                :href="route('sequence.create')">
                Correlativos
            </TabLink>
            <TabLink
                :href="route('aco.index')">
                Cuentas
            </TabLink>
            <TabLink
                active
                :href="route('wh.index')">
                Almacen
            </TabLink>
        </template>

        <!--        Contenido de la vantana-->
        <div class="">
            <FRegister
                :edit-ware-house="state.editWareHouse ?? undefined"
                class="w-full"
                />

            <FShow
                @editWareHouse="(item:warehouseBaseI) => state.editWareHouse = item "
                :warehouse="propsW.warehouse"/>



        </div>
    </AppLayout>
</template>
