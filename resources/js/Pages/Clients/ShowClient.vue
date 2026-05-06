<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import FShowClient from '@/Pages/Clients/FShowClient.vue';
import TabLink from '@components/TabLink.vue';
import { PaginationI } from '@/Interfaces/GlobalInterface';
import { clientBaseI } from '@/Interfaces/ClientInterface';
import { useRoute } from 'ziggy-js';

const route = useRoute();
// Datos del backend
const props = defineProps<{
  clients: PaginationI<clientBaseI>;
}>();
</script>

<template>
  <!-- Titulo -->
  <Head title="Cliente" />

  <!-- Contenido -->
  <AppLayout>
    <!-- Cabecera de la ventana -->
    <template #header>
      <TabLink :href="route('client.create')"> Registrar </TabLink>
      <TabLink :active="true" :href="route('client.show')"> Mostrar </TabLink>
    </template>

    <!-- Contenido principal -->
    <div>
      <FShowClient :clients="props.clients" />
    </div>
  </AppLayout>
</template>
