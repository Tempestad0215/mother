<script setup lang="ts">
import {clientEditI} from "@/Interfaces/ClientInterface";
import AppLayout from "@layout/AppLayout.vue";
import {useRoute} from "ziggy-js";
import {Tabs, TabList, Tab, TabPanel, InputText} from "primevue";
import {ref} from "vue";

const route = useRoute();
/**
 * propsW de la vantana
 */
const test = ref("")
const propsW = defineProps<{
    clientEdit?: clientEditI,
    update?: boolean,
    typeRNC: string[]
}>();
</script>

<template>
    <AppLayout>
        <Tabs value="0">
            <TabList>
                <Tab value="0">Mostrar</Tab>
                <Tab value="1">Registrar</Tab>
            </TabList>
            <TabPanel value="0" >
                <InputText v-model="test" />
            </TabPanel>
            <TabPanel value="1">
                Funcionad esde el numero 1
            </TabPanel>

        </Tabs>

    </AppLayout>
</template>

<style scoped>

</style>
