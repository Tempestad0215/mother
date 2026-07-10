<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { Ref, ref } from 'vue';
import { useRoute } from 'ziggy-js';
import { Avatar, Popover, Menu } from 'primevue';
import { MenuItemI } from '@/Interfaces/GlobalInterface';

const route = useRoute();
defineProps<{
  url: string;
}>();

const op = ref();
//Par mostar la ventana
const show: Ref<boolean> = ref(false);
const items = ref<MenuItemI[]>([
  { label: 'Perfil', icon: 'pi pi-user-edit', url: route('profile.show') },
  { label: 'Ajustes', icon: 'pi pi-cog', url: route('setting.index') },
  { label: 'salir', icon: 'pi pi-sign-out', command: () => logOut() },
]);

/**
 * funcion para salir
 */
const logOut = () => {
  router.post(route('logout'));
};

const toggle = (event: Event) => {
  op.value.toggle(event);
};
</script>

<template>
  <div class="mt-3 relative text-center">
    <Avatar @click="toggle" size="large" :image="url" shape="circle" />

    <Popover ref="op" class="p-0!">
      <Menu :model="items" />
    </Popover>
  </div>
</template>
