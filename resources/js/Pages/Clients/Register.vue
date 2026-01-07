<script setup lang="ts">
import {clientBaseI, clientDocumentI, clientEditI, clientPriceI, clientTypeI} from "@/Interfaces/ClientInterface";
import AppLayout from "@layout/AppLayout.vue";
import {useRoute} from "ziggy-js";
import {DataTable, Column, Button, Dialog} from "primevue";
import {ref} from "vue";
import {paginationI} from "@/Interfaces/GlobalInterface";
import FRegister from "@/Pages/Clients/FRegister.vue";
import { usePage} from "@inertiajs/vue3";

const route = useRoute();
const page = usePage();
/**
 * propsW de la vantana
 */
const test = ref("")
const createClient = ref<boolean>(false)
const propsW = defineProps<{
    clientEdit?: clientEditI,
    update?: boolean,
    typeRNC: string[],
    clientData: paginationI<clientBaseI>,
    clientType: clientTypeI,
    clientPrice: clientPriceI,
    clientDocument: clientDocumentI,
}>();
</script>

<template>
    <AppLayout>
        <DataTable :value="propsW.clientData.data" >
            <template #header>
                <Button
                    @click="createClient = true">
                    Crear Cliente
                </Button>
            </template>
            <Column field="name" label="Nombre"  />
        </DataTable>
        <Dialog
            modal
            v-model:visible="createClient" >
            <FRegister
                :clientDocument="clientDocument"
                :clientPrice="clientPrice"
                :clientType="clientType"
                :typeRNC="typeRNC"
                :client-edit="clientEdit"/>
        </Dialog>
    </AppLayout>
</template>

<style scoped>

</style>
