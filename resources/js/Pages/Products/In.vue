<script setup lang="ts">
import HeaderBox from '@components/HeaderBox.vue';
import NavLink from '@components/NavLink.vue';
import AppLayout from '@layout/AppLayout.vue';



</script>


<template>
    <AppLayout>
        <template #header >
            <HeaderBox>
                <h2>
                    Entrada de producto
                </h2>
                <template #link >
                    <NavLink
                        :href="route('product.create')" >
                        Registrar
                    </NavLink>
                    <NavLink
                        :active="true"
                        :href="route('product.show')" >
                        Mostrar
                    </NavLink>
                    <NavLink
                        :href="route('product.create')" >
                        Entrada
                    </NavLink>
                </template>
            </HeaderBox>
        </template>

    </AppLayout>

</template>
