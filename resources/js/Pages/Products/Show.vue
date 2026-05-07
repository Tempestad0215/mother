<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { productI } from '@/Interfaces/ProductInterface';
import { PropType } from 'vue';
import FShowProduct from '@/Pages/Products/FShowProduct.vue';
import TabLink from '@components/TabLink.vue';
import { useRoute } from 'ziggy-js';

const route = useRoute();
const props = defineProps({
  products: {
    type: Object as PropType<productI>,
    required: true,
  },
});
</script>

<template>
  <!--    Titulo de la ventana-->
  <Head title="Mostrar" />
  <AppLayout>
    <!--        Titulo de la ventana-->
    <template #header>
      <TabLink :href="route('product.index')"> Registrar </TabLink>
      <TabLink :href="route('entry.index')"> Entrada </TabLink>
      <TabLink :active="true" :href="route('product.show')"> Mostrar </TabLink>
    </template>

    <!--        Contenido de la ventana-->
    <div class="fondo rounded-md p-5">
      <FShowProduct class="" :products="props.products" />
    </div>
  </AppLayout>
</template>
