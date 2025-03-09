<script setup lang="ts">
import {clientEditI} from "@/Interfaces/Client";
import AppLayout from "@layout/AppLayout.vue";
import FRegister from "@/Pages/Clients/FRegister.vue";
import TabLink from "@components/TabLink.vue";

/**
 * propsW de la vantana
 */
const propsW = defineProps<{
    clientEdit?: clientEditI,
    update?: boolean,
}>();
</script>

<template>
    <AppLayout>
        <template #header>
            <TabLink :href="route('client.create')">
                Registrar
            </TabLink>
            <TabLink :href="route('client.show')">
                Mostrar
            </TabLink>
        </template>

        <FRegister
            :clientEdit="clientEdit"/>



    </AppLayout>
</template>

<style scoped>

</style>
