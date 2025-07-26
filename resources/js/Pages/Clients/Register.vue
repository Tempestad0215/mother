<script setup lang="ts">
import {clientEditI} from "@/Interfaces/ClientInterface";
import AppLayout from "@layout/AppLayout.vue";
import FRegister from "@/Pages/Clients/FRegister.vue";
import TabLink from "@components/TabLink.vue";

/**
 * propsW de la vantana
 */
const propsW = defineProps<{
    clientEdit?: clientEditI,
    update?: boolean,
    typeRNC: string[]
}>();
</script>

<template>
    <AppLayout>
        <template #header>
            <TabLink
                :active="true"
                :href="route('client.create')">
                Registrar
            </TabLink>
            <TabLink :href="route('client.show')">
                Mostrar
            </TabLink>
        </template>

        <FRegister
            :update="propsW.update"
            :clientEdit="propsW.clientEdit"
            :typeRNC="typeRNC"/>

    </AppLayout>
</template>

<style scoped>

</style>
