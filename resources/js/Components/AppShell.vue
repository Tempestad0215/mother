<script setup lang="ts">
import { AppVariant } from '@/types/ui';
import SidebarProvider from '@/Components/ui/sidebar/SidebarProvider.vue';
import { usePage } from '@inertiajs/vue3';

type Props = {
  variant?: AppVariant;
};

withDefaults(defineProps<Props>(), {
  variant: 'sidebar',
});

const isOpen = usePage().props.sidebarOpen;
</script>

<template>
  <div v-if="variant === 'header'" class="flex min-h-screen w-full flex-col">
    <slot />
  </div>
  <SidebarProvider v-else :default-open="isOpen">
    <slot />
  </SidebarProvider>
</template>
