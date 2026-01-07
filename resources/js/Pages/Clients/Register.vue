<script setup lang="ts">
import {clientBaseI, clientEditI} from "@/Interfaces/ClientInterface";
import AppLayout from "@layout/AppLayout.vue";
import {useRoute} from "ziggy-js";
import {DataTable, Column, Button, Dialog} from "primevue";
import {ref} from "vue";
import {paginationI} from "@/Interfaces/GlobalInterface";
import FRegister from "@/Pages/Clients/FRegister.vue";

const route = useRoute();
/**
 * propsW de la vantana
 */
const test = ref("")
const propsW = defineProps<{
    clientEdit?: clientEditI,
    update?: boolean,
    typeRNC: string[],
    clientData: paginationI<clientBaseI>
}>();
</script>

<template>
    <AppLayout>
        <DataTable :value="clientData" >
            <template #header>
                <Button>
                    Crear Cliente
                </Button>
            </template>
            <Column field="name" label="Nombre"  />
        </DataTable>
        <Dialog>
            <FRegister
                :typeRNC="typeRNC"
                :client-edit="clientEdit"/>
        </Dialog>
    </AppLayout>
</template>

<style scoped>

</style>
