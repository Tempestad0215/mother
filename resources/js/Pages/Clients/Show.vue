<script setup lang="ts">
import type {clientDataI } from '@/Interfaces/Client';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import type { PropType } from 'vue';
import FShow from "@/Pages/Clients/FShow.vue";
import TabLink from "@components/TabLink.vue";


// Datos del backend
const props = defineProps({
    clients:{
        type: Object as PropType<clientDataI>,
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
