<script setup lang="ts" >
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from "@layout/AppLayout.vue";


</script>

<template>
    <AppLayout
        title="Bienvenida">
        <template #header >
            funcionando desde la cabecera de la mejro
        </template>

        <div>
            <div class="h-screen">
                esta es el continedo
            </div>

        </div>
    </AppLayout>
</template>
