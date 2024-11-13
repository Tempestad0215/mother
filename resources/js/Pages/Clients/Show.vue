<script setup lang="ts">
import type { clientI } from '@/Interfaces/ClientInterface';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import type { PropType } from 'vue';
import FloatShowCli from "@/Pages/Clients/FloatShowCli.vue";


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

    <!-- Contenido -->
    <AppLayout>
        <!-- Cabecera de la ventana -->
        <template #header>

        </template>

        <!-- Contenido principal -->
        <div>
           <FloatShowCli
               :clients="<clientI>props.clients"
                />

        </div>
    </AppLayout>


</template>
