<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {ref} from "vue";
import {DataTable, Column, Dialog} from "primevue";


/*
Propiedades
 */
const propsW = defineProps<{
    purchases: any
}>();

/*
Datos de la ventana
 */
const showProduct = ref<boolean>(false)




</script>

<template>
    <AppLayout>
        <DataTable>
            <Column header="ID" />
            <Column header="Name" />
        </DataTable>
        <Dialog
            modal
            header="Creacion de Compra">

        </Dialog>
    </AppLayout>
</template>
