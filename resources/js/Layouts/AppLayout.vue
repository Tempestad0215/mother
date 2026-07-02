<script setup lang="ts">
import { onMounted, onUnmounted, reactive, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Divider from '@components/Divider.vue';
import ImageMenu from '@components/ImageMenu.vue';
import { useRoute } from 'ziggy-js';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faArrowCircleLeft } from '@fortawesome/free-solid-svg-icons';
import { ConfirmDialog, PanelMenu, ScrollPanel, Toast } from 'primevue';
import { MenuItemI } from '@/Interfaces/GlobalInterface';
import {
  GitBranch,
  LayoutDashboardIcon,
  Users,
  TruckIcon,
  ShoppingCart,
  ReceiptIcon,
  FolderClock,
} from '@lucide/vue';

// ✅ Elimina: const route = useRoute();

const route = useRoute();
const { props } = usePage();

defineProps({
  title: String,
});

const menuImageRef = ref<HTMLElement | null>(null);
const showExchange = ref<boolean>(false);
const showOption = ref<boolean>(false);
const isHiddenMenu = ref<boolean>(false);

// ✅ Menú optimizado para POS
const menuItems = reactive<MenuItemI[]>([
  {
    label: 'Dashboard',
    url: route('dashboard'), // o .create si prefieres
    activePath: '/dashboard',
    iconComponent: LayoutDashboardIcon,
  },
  {
    label: 'Clientes',
    url: route('client.index'), // o .create si prefieres
    activePath: '/client',
    iconComponent: Users,
  },
  {
    label: 'Categorías',
    url: route('category.index'),
    activePath: '/category',
    iconComponent: GitBranch,
  },
  {
    label: 'Proveedores',
    url: route('supplier.index'),
    activePath: '/supplier',
    iconComponent: TruckIcon,
  },
  {
    label: 'Compra',
    url: route('purchase.index'),
    activePath: '/product',
    iconComponent: ShoppingCart,
  },
  {
    label: 'Productos',
    url: route('product.index'),
    activePath: '/product',
    icon: 'pi pi-box',
  },
  {
    label: 'Ventas',
    url: route('sale.index'),
    activePath: '/sale',
    iconComponent: ReceiptIcon,
  },
  {
    label: 'Reportes',
    url: route('report-sale.index'),
    activePath: '/report',
    iconComponent: FolderClock,
  },
]);

// const isActive = (activePath: string): boolean => {
//     return window.location.pathname.startsWith(activePath);
// };

const handleClick = (event: MouseEvent) => {
  if (menuImageRef.value && !menuImageRef.value.contains(event.target as Node)) {
    showOption.value = false;
  }
};

const showExchangeWindow = () => {
  if (props.isExchange) {
    showExchange.value = true;
  }
};

onMounted(() => {
  document.addEventListener('click', handleClick);
  showExchangeWindow();
});

onUnmounted(() => {
  document.removeEventListener('click', handleClick);
});
</script>

<template>
  <div class="flex min-h-screen bg-gray-50">
    <FontAwesomeIcon
      @click="isHiddenMenu = !isHiddenMenu"
      class="absolute top-3 text-3xl cursor-pointer text-red-500 z-50 ease-in-out duration-200 transition-[left, transform]"
      :icon="faArrowCircleLeft"
      :class="{
        'left-40': !isHiddenMenu,
        'left-8 rotate-180': isHiddenMenu,
      }"
    />

    <!-- Sidebar Profesional -->
    <aside
      class="bg-gray-900 text-white w-64 flex flex-col shadow-xl transition-all duration-300"
      :style="{ width: isHiddenMenu ? '3rem' : '11rem' }"
    >
      <!-- Logo / User -->
      <div v-show="!isHiddenMenu">
        <ImageMenu :url="props.auth.user.profile_photo_url" />
      </div>

      <Divider />

      <PanelMenu orientation="vertical" :model="menuItems">
        <template #item="{ item }: { item: MenuItemI }">
          <Link
            class="block text-xl mx-2 group"
            :class="[{ 'text-center': isHiddenMenu }]"
            :href="item.url"
          >
            <div class="flex items-center">
              <component
                v-if="item.iconComponent !== undefined"
                :is="item.iconComponent"
                class="w-5 h-5 group-hover:text-green-500 duration-300 group-hover:scale-105"
                :class="{ 'mr-3': !isHiddenMenu }"
              />
              <i
                v-else
                class="mr-3 text-center group-hover:text-green-500 duration-300 group-hover:scale-105"
                :class="[item.icon, { 'text-center': isHiddenMenu }]"
              ></i>
              <span
                class="ease-in-out transition-all group-hover:text-green-500 duration-300 group-hover:scale-105"
                :class="{ hidden: isHiddenMenu }"
                >{{ item.label }}</span
              >
            </div>
          </Link>
        </template>
      </PanelMenu>
    </aside>

    <!-- Contenido principal -->
    <div class="flex-1 flex flex-col overflow-auto">
      <!-- Header -->
      <!--            <header class="bg-white shadow-sm h-16 flex items-center px-6 justify-between">-->
      <!--                <h1 class="text-lg font-semibold text-gray-800">-->
      <!--                    <slot name="header">{{ title }}</slot>-->
      <!--                </h1>-->
      <!--                &lt;!&ndash; Puedes agregar notificaciones, perfil, etc. &ndash;&gt;-->
      <!--            </header>-->

      <ScrollPanel class="flex-1 p-6 bg-gray-50 h-screen">
        <slot />
      </ScrollPanel>
    </div>
  </div>
  <Toast />
  <ConfirmDialog />
</template>
