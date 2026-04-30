<script setup lang="ts">
import {clientBaseI, clientDocumentI, clientPriceI, clientTypeI} from "@/Interfaces/ClientInterface";
import AppLayout from "@layout/AppLayout.vue";
import {useConfirm, useToast, Dialog} from "primevue";
import {onMounted, ref} from "vue";
import {PaginationI} from "@/Interfaces/GlobalInterface";
import FRegister from "@/Pages/Clients/FRegister.vue";
import {useRoute} from "ziggy-js";
import FShowClient from "@/Pages/Clients/FShowClient.vue";


const createClient = ref<boolean>(false);
const selectedClient = ref<clientBaseI | null>(null);
const isUpdate = ref(false);


const propsW = defineProps<{
    typeRNC: string[]
    clientData: PaginationI<clientBaseI>
    clientType: clientTypeI
    clientPrice: clientPriceI
    clientDocument: clientDocumentI
}>();


onMounted(()=>{
    window.addEventListener("keydown", handleKeyDown);
})

const handleKeyDown = (event: KeyboardEvent) => {
    if(event.ctrlKey && event.key.toLowerCase() === "k"){
        event.preventDefault();
        createClient.value = true;
    }
}

const closeModal = () => {
    selectedClient.value = null;
    isUpdate.value = false;
}
</script>

<template>
    <AppLayout>
        <FShowClient
            v-model:show-client="createClient"
            v-model:client-selected="selectedClient"
            v-model:update-client="isUpdate"
            :client-data="propsW.clientData"/>
        <Dialog
            modal
            @hide="closeModal"
            v-model:visible="createClient" >
            <FRegister
                :clientDocument="clientDocument"
                :clientPrice="clientPrice"
                :clientType="clientType"
                :typeRNC="typeRNC"
                :update="isUpdate"
                :client-edit="selectedClient"/>
        </Dialog>
    </AppLayout>
</template>
