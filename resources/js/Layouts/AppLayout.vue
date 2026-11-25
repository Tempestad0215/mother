<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import { BreadcrumbItem } from '@/types/navigation';
import { Toaster } from '@components/ui/sonner';

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
