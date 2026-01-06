<script setup lang="ts">
import { onMounted, onUnmounted, reactive, ref } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import LinkHeader from "@components/LinkHeader.vue";
import Divider from "@components/Divider.vue";
import ImageMenu from "@components/ImageMenu.vue";
// ✅ Importa SOLO la función route de Ziggy
import { useRoute } from 'ziggy-js';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faArrowCircleLeft, faBuildingUser} from "@fortawesome/free-solid-svg-icons";

// ✅ Elimina: const route = useRoute();

const route = useRoute();
const { props } = usePage();

defineProps({
    title: String,
});

const menuImageRef = ref<HTMLElement | null>(null);
const showExchange = ref<boolean>(false);
const showOption = ref<boolean>(false);

// ✅ Menú optimizado para POS
const menuItems = reactive([
    {
        label: "Clientes",
        url: route("client.create"), // o .create si prefieres
        activePath: "/client",
        icon: "pi pi-user",
    },
    {
        label: "Categorías",
        url: route("category.create"),
        activePath: "/category",
        icon: "pi pi-sitemap",
    },
    {
        label: "Proveedores",
        url: route("supplier.create"),
        activePath: "/supplier",
        icon: "pi pi-truck",
    },
    {
        label: "Productos",
        url: route("product.create"),
        activePath: "/product",
        icon: "pi pi-box",
    },
    {
        label: "Nueva Venta",
        url: route("sale.create"),
        activePath: "/sale/create",
        icon: "pi pi-shopping-cart",
        // Resaltado especial para POS
        isPrimary: true,
    },
    {
        label: "Ventas",
        url: route("sale.create"),
        activePath: "/sale",
        icon: "pi pi-receipt",
    },
    {
        label: "Reportes",
        url: route("report-sale.index"),
        activePath: "/report",
        icon: "pi pi-chart-bar",
    },
]);

const isActive = (activePath: string): boolean => {
    return window.location.pathname.startsWith(activePath);
};

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
    document.addEventListener("click", handleClick);
    showExchangeWindow();
});

onUnmounted(() => {
    document.removeEventListener("click", handleClick);
});
</script>

<template>
    <div class="flex min-h-screen bg-gray-50">

        <FontAwesomeIcon
            @click="console.log('funiona bien')"
            class="absolute left-[240px] top-3 text-3xl text-white"  :icon="faArrowCircleLeft"/>

        <!-- Sidebar Profesional -->
        <aside
            class="bg-gray-900 text-white w-64 flex flex-col shadow-xl transition-all duration-300"
        >
            <!-- Logo / User -->
            <div class="p-5 border-b border-gray-700">
                <ImageMenu :url="props.auth.user.profile_photo_url" />
            </div>

            <Divider />

            <!-- Menú de navegación -->
            <nav class="flex-1 px-3 py-4 space-y-2 overflow-y-auto">
                <LinkHeader
                    v-for="(item, index) in menuItems"
                    :key="index"
                    :href="item.url"
                    :class="[
            'flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200',
            isActive(item.activePath)
              ? (item.isPrimary
                  ? 'bg-emerald-600 text-white shadow-md'
                  : 'bg-blue-600 text-white')
              : 'text-gray-300 hover:bg-gray-800 hover:text-white',
          ]"
                >
                    <i :class="item.icon" class="text-lg"></i>
                    <span class="font-medium">{{ item.label }}</span>
                    <!-- Indicador visual para POS -->
                    <span v-if="item.isPrimary" class="ml-auto bg-emerald-500 text-xs px-2 py-0.5 rounded-full">
            POS
          </span>
                </LinkHeader>
            </nav>

        </aside>

        <!-- Contenido principal -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm h-16 flex items-center px-6 justify-between">
                <h1 class="text-lg font-semibold text-gray-800">
                    <slot name="header">{{ title }}</slot>
                </h1>
                <!-- Puedes agregar notificaciones, perfil, etc. -->
            </header>

            <!-- Contenido -->
            <main class="flex-1 overflow-y-auto p-6 bg-gray-50">
                <slot />
            </main>
        </div>
    </div>

</template>
