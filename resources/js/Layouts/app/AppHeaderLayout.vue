<script setup lang="ts">
import AppContent from '@/Components/AppContent.vue';
import AppHeader from '@/Components/AppHeader.vue';
import AppShell from '@/Components/AppShell.vue';
import { BreadcrumbItem } from '@/types/navigation';
import { Toaster } from '@/Components/ui/sonner';

type Props = {
  breadcrumbs?: BreadcrumbItem[];
};

withDefaults(defineProps<Props>(), {
  breadcrumbs: () => [],
});
</script>

<template>
  <AppShell variant="header">
    <AppHeader :breadcrumbs="breadcrumbs" />
    <AppContent variant="header">
      <slot />
    </AppContent>
    <Toaster />
  </AppShell>
</template>
