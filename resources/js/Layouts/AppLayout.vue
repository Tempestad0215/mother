<script setup lang="ts">
import { onMounted, onUnmounted, reactive, ref, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Divider from '@components/Divider.vue';
import ImageMenu from '@components/ImageMenu.vue';
import { useRoute } from 'ziggy-js';
import { ConfirmDialog, PanelMenu, ScrollPanel, Toast } from 'primevue';
import { MenuItemI } from '@/Interfaces/GlobalInterface';
import {
  GitBranch,
  LayoutDashboardIcon,
  Users,
  TruckIcon,
  ShoppingCart,
  ReceiptIcon,
  Menu,
  X,
  FileText, // Icono sugerido para Cotizaciones
} from '@lucide/vue';

const route = useRoute();
const { props } = usePage();

defineProps({
  title: String,
});

const menuImageRef = ref<HTMLElement | null>(null);
const showExchange = ref<boolean>(false);
const showOption = ref<boolean>(false);

// Estado de visibilidad:
// En desktop representa si está colapsado (compacto).
// En móvil representa si el menú lateral está abierto.
const isHiddenMenu = ref<boolean>(false);
const isMobileOpen = ref<boolean>(false);

// Menú optimizado para POS y Servicios
const menuItems = reactive<MenuItemI[]>([
  {
    label: 'Dashboard',
    url: route('dashboard'),
    activePath: '/dashboard',
    iconComponent: LayoutDashboardIcon,
  },
  {
    label: 'Clientes',
    url: route('client.index'),
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
    activePath: '/purchase',
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
  /* Opción para Cotizaciones / Servicios futuro
  {
    label: 'Cotizaciones',
    url: '#',
    activePath: '/quotation',
    iconComponent: FileText,
  },
  */
]);

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

const closeMobileMenu = () => {
  isMobileOpen.value = false;
};

// Cerrar el menú móvil al cambiar de ruta
watch(
  () => usePage().url,
  () => {
    closeMobileMenu();
  }
);

onMounted(() => {
  document.addEventListener('click', handleClick);
  showExchangeWindow();
});

onUnmounted(() => {
  document.removeEventListener('click', handleClick);
});
</script>

<template>
  <div class="flex h-screen bg-gray-100 overflow-hidden relative">
    <!-- 1. Header Móvil (solo visible en pantallas pequeñas) -->
    <header
      class="md:hidden bg-slate-900 text-white h-14 w-full flex items-center justify-between px-4 fixed top-0 left-0 z-30 border-b border-slate-800"
    >
      <div class="flex items-center gap-3">
        <button
          @click="isMobileOpen = !isMobileOpen"
          class="p-2 rounded-lg bg-slate-800 text-slate-200 hover:text-white hover:bg-slate-700 transition"
          aria-label="Abrir Menú"
        >
          <Menu v-if="!isMobileOpen" class="w-6 h-6" />
          <X v-else class="w-6 h-6" />
        </button>

        <span class="font-bold text-sm tracking-wide text-gray-200">
          {{ title || 'POS System' }}
        </span>
      </div>

      <div class="w-8 h-8 rounded-full overflow-hidden border border-slate-700">
        <ImageMenu :url="props.auth.user.profile_photo_url" />
      </div>
    </header>

    <!-- 2. Backdrop para móvil (oscurece la pantalla al abrir el sidebar) -->
    <div
      v-if="isMobileOpen"
      @click="closeMobileMenu"
      class="fixed inset-0 bg-black/60 z-40 md:hidden backdrop-blur-sm transition-opacity"
    ></div>

    <!-- 3. Sidebar Responsive (Móvil Drawer / Desktop Collapsible) -->
    <aside
      class="bg-slate-900 text-slate-100 flex flex-col z-50 shadow-2xl transition-all duration-300 fixed md:static top-0 bottom-0 left-0 h-full"
      :class="[
        // Comportamiento en Móvil
        isMobileOpen ? 'translate-x-0 w-64' : '-translate-x-full md:translate-x-0',
        // Comportamiento en Desktop (Ancho variable según colapso)
        isHiddenMenu ? 'md:w-20' : 'md:w-60',
      ]"
    >
      <!-- Encabezado del Sidebar (Logo & Botón Colapsar Desktop) -->
      <div class="p-4 flex items-center justify-between border-b border-slate-800">
        <!-- Perfil / Logo -->
        <div
          v-show="!isHiddenMenu || isMobileOpen"
          class="flex items-center gap-3 overflow-hidden transition-all duration-300"
        >
          <ImageMenu :url="props.auth.user.profile_photo_url" />
        </div>

        <!-- Botón Toggle para Desktop -->
        <button
          @click="isHiddenMenu = !isHiddenMenu"
          class="hidden md:flex p-2 rounded-lg bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 transition ml-auto"
          title="Colapsar menú"
        >
          <Menu class="w-5 h-5" />
        </button>

        <!-- Botón Cerrar para Móvil -->
        <button
          @click="closeMobileMenu"
          class="md:hidden p-2 rounded-lg bg-slate-800 text-slate-400 hover:text-white"
        >
          <X class="w-5 h-5" />
        </button>
      </div>

      <Divider class="my-0 border-slate-800" />

      <!-- Menú de Navegación -->
      <div class="flex-1 overflow-y-auto py-3">
        <PanelMenu orientation="vertical" :model="menuItems" class="w-full">
          <template #item="{ item }: { item: MenuItemI }">
            <Link
              class="flex items-center py-3 px-4 mx-2 rounded-lg text-sm font-medium transition-all duration-200 group text-slate-300 hover:bg-slate-800 hover:text-emerald-400"
              :class="{ 'justify-center px-0': isHiddenMenu && !isMobileOpen }"
              :href="item.url"
              @click="closeMobileMenu"
            >
              <div class="flex items-center">
                <component
                  v-if="item.iconComponent !== undefined"
                  :is="item.iconComponent"
                  class="w-5 h-5 transition-transform duration-200 group-hover:scale-110 shrink-0"
                  :class="{ 'mr-3': !isHiddenMenu || isMobileOpen }"
                />
                <i
                  v-else
                  class="text-base transition-transform duration-200 group-hover:scale-110 shrink-0"
                  :class="[item.icon, { 'mr-3': !isHiddenMenu || isMobileOpen }]"
                ></i>

                <!-- Etiqueta de texto (se oculta cuando el menú está colapsado en desktop) -->
                <span
                  class="whitespace-nowrap transition-opacity duration-200"
                  :class="{ hidden: isHiddenMenu && !isMobileOpen }"
                >
                  {{ item.label }}
                </span>
              </div>
            </Link>
          </template>
        </PanelMenu>
      </div>
    </aside>

    <!-- 4. Contenido Principal -->
    <main class="flex-1 flex flex-col min-w-0 h-full pt-14 md:pt-0 overflow-hidden">
      <ScrollPanel class="flex-1 p-4 md:p-6 bg-slate-50 h-full">
        <!-- Contenedor adaptativo para tablas y componentes del POS -->
        <div class="max-w-7xl mx-auto">
          <slot />
        </div>
      </ScrollPanel>
    </main>
  </div>

  <Toast />
  <ConfirmDialog />
</template>

<style scoped>
/* Opcional: Ajuste para ScrollPanel de PrimeVue */
:deep(.p-scrollpanel-content) {
  padding: 0 !important;
}
</style>
