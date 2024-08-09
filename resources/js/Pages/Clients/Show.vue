<script setup lang="ts">
import type { clientI } from '@/Interfaces/ClientInterface';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import type { PropType } from 'vue';
import LinkHeader from "@components/LinkHeader.vue";
import FloatShowCli from "@/Pages/Clients/FloatShow.vue";


// Datos del backend
const props = defineProps({
    clients:{
        type: Object as PropType<clientI>,
        required: true
    }
});


</script>


<template>
    <!-- Titulo -->
    <Head title="Cliente"/>

    <!-- Contenido de todo -->
    <AppLayout>
        <!-- Cabecera de la ventana -->
        <template #header>
            <LinkHeader
                :href="route('client.create')">
                Registrar
            </LinkHeader>
            <LinkHeader
                :active="true"
                :href="route('client.create')">
                Mostrar
            </LinkHeader>
        </template>

        <!-- Contenido principal -->
        <div>
           <FloatShowCli
               :clients="props.clients"
                />

        </div>
    </AppLayout>


</template>
