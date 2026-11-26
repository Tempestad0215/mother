<script setup lang="ts">
import { Toaster } from '@/Components/ui/sonner';
import AppLayout from '@/Layouts/app/AppSidebarLayout.vue';
import { BreadcrumbItem } from '@/types/navigation';

const { breadcrumbs = [] } = defineProps<{
    breadcrumbs?: BreadcrumbItem[];
}>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
    </AppLayout>
    <Toaster />
</template>
