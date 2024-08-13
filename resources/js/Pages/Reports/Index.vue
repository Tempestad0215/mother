<script setup lang="ts">

import AppLayout from "@layout/AppLayout.vue";
import {Head} from "@inertiajs/vue3";
import SecondaryButton from "@components/SecondaryButton.vue";
</script>

<template>
    <Head title="Reportes"/>
    <AppLayout>
        <template #header >

        </template>

        <div class="bg-gray-200 rounded-md p-5">

            <fieldset class=" border-2 border-gray-800 rounded-md p-3 space-x-3">
                <legend>
                    Reportes de Ventas
                </legend>

                <SecondaryButton >
                    Ventas Diaria
                </SecondaryButton>

                <SecondaryButton >
                    Ventas Semanal
                </SecondaryButton>

                <SecondaryButton >
                    Ventas Mensual
                </SecondaryButton>

                <SecondaryButton >
                    Ventas Anual
                </SecondaryButton>
            </fieldset>


            <fieldset class=" border-2 border-gray-800 rounded-md p-3 space-x-3 mt-4">
                <legend>
                    Reporte de Inventario
                </legend>

                <SecondaryButton >
                    Productos Alm. -10
                </SecondaryButton>


            </fieldset>

        </div>
    </AppLayout>

</template>

<style scoped>

</style>
