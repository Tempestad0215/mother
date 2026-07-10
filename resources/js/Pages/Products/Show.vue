<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ProductTableI } from '@/Interfaces/ProductInterface';
import FShowProduct from '@/Pages/Products/FShowProduct.vue';
import { useRoute } from 'ziggy-js';
import { PaginationI } from '@/Interfaces/GlobalInterface';

const route = useRoute();
const props = defineProps<{
  products: PaginationI<ProductTableI>;
}>();
</script>

<template>
  <!--    Titulo de la ventana-->
  <Head title="Mostrar" />
  <AppLayout>
    <!--        Contenido de la ventana-->
    <div class="fondo rounded-md p-5">
      <FShowProduct class="" :products="props.products" />
    </div>
  </AppLayout>
</template>
