<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import FShow from "@/Pages/Clients/FShow.vue";
import TabLink from "@components/TabLink.vue";
import {paginationI} from "@/Interfaces/Global";
import {clientBaseI} from "@/Interfaces/Client";


// Datos del backend
const props = defineProps<{
    clients: paginationI<clientBaseI>
}>();


</script>


<template>
    <!-- Titulo -->
    <Head title="Cliente"/>

    <!-- Contenido -->
    <AppLayout>
        <!-- Cabecera de la ventana -->
        <template #header>
            <TabLink
                :href="route('client.create')">
                Registrar
            </TabLink>
            <TabLink
                :active="true"
                :href="route('client.show')">
                Mostrar
            </TabLink>
        </template>

        <!-- Contenido principal -->
        <div>
           <FShow
               :clients="props.clients"
                />

        </div>
    </AppLayout>


</template>
