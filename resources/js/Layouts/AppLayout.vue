<script setup lang="ts">
import { onMounted, onUnmounted, reactive, ref} from 'vue';
import { usePage } from '@inertiajs/vue3';
import Divider from "@components/Divider.vue";
import ImageMenu from "@components/ImageMenu.vue";
// ✅ Importa SOLO la función route de Ziggy
import { useRoute } from 'ziggy-js';
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {faArrowCircleLeft} from "@fortawesome/free-solid-svg-icons";
import {PanelMenu, ScrollPanel} from "primevue";

// ✅ Elimina: const route = useRoute();

const route = useRoute();
const { props } = usePage();

defineProps({
    title: String,
});

const menuImageRef = ref<HTMLElement | null>(null);
const showExchange = ref<boolean>(false);
const showOption = ref<boolean>(false);
const isHiddenMenu = ref<boolean>(true);


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
            @click="isHiddenMenu = !isHiddenMenu"
            class="absolute top-3 text-3xl cursor-pointer text-red-500 z-50 ease-in-out  duration-200 transition-[left, transform] "
            :icon="faArrowCircleLeft"
            :class="{
                'left-[10rem]': !isHiddenMenu,
                'left-[2rem] rotate-180': isHiddenMenu
              }"

        />

        <!-- Sidebar Profesional -->
        <aside
            class="bg-gray-900 text-white w-64 flex  flex-col shadow-xl transition-all duration-300"
            :style="{ width: isHiddenMenu ? '3rem' : '11rem' }"
        >
            <!-- Logo / User -->
            <div
                v-show="!isHiddenMenu"
                class="p-5 border-b border-gray-700">
                <ImageMenu :url="props.auth.user.profile_photo_url" />
            </div>

            <Divider />


            <PanelMenu :model="menuItems">
                <template #item="{ item }">
                    <a
                        class="block text-xl mx-2"
                        :class="[{'text-center': isHiddenMenu}]"
                        :title="item.label"
                        :href="item.url">
                        <i
                            class="mr-3 text-center"
                            :class="[item.icon,{'text-center': isHiddenMenu} ]" ></i>
                        <span
                            class="ease-in-out transition-[hidden] duration-200"
                            :class="{'hidden': isHiddenMenu }">{{item.label}}</span>
                    </a>
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

            <ScrollPanel class="flex-1 p-6 bg-gray-50 h-screen" >
                <slot/>
            </ScrollPanel>
        </div>
    </div>

</template>
