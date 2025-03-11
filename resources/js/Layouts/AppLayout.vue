<script setup lang="ts">
import {onMounted, onUnmounted, reactive, ref} from 'vue';
import {Head, usePage} from '@inertiajs/vue3';
import LinkHeader from "@components/LinkHeader.vue";
import Divider from "@components/Divider.vue";
import ImageMenu from "@components/ImageMenu.vue";
import FloatBox from "@components/FloatBox.vue";
import Exchange from "@/Pages/Setting/CU/Exchange.vue";


/*
Destructurar las variables
 */
const {props} = usePage();

/*
Propiedads de la ventana
 */
defineProps({
    title: String,
});


const menuImageRef = ref<HTMLElement | null>(null);
const showExchange = ref<boolean>(false);
const urlTab = reactive([
    {
        label: "Cliente",
        url: route("client.create"),
        urlActive: "/client",
        icon: "fa-solid fa-user"
    },
    {
        label: "Categorias",
        url: route("category.create"),
        urlActive: "/category",
        icon: "fa-solid fa-code-branch"
    },
    {
        label: "Suplidores",
        url: route("supplier.create"),
        urlActive: "/supplier",
        icon: "fa-solid fa-truck-field"
    },
    {
        label: "Productos",
        url: route("product.create"),
        urlActive: "/product",
        icon: "fa-solid fa-boxes-packing"
    },
    {
        label: "Ventas",
        url: route("sale.create"),
        urlActive: "/sale",
        icon: "fa-solid fa-cash-register"
    },
    {
        label: "Reportes",
        url: route("report-sale.index"),
        urlActive: "/report",
        icon: "fa-solid fa-clipboard"
    },
])
/*
Al momento de cargar
 */
onMounted(()=>{
   document.addEventListener("click", handleClick);

    //Ventana de cambio
    showExchangeWindow();
});

/*
Cuando se cancela el evento
 */
onUnmounted(() => {
    document.removeEventListener("click", handleClick);

    //Ventana de cambio
    showExchangeWindow();
})


/**
 * funciones computada
 */

const isActive = (url:string):boolean => {
    return  window.location.pathname.startsWith(url);
}


/*
Funciones
 */
/**
 * Verificar si el click fue afuera
 */
const handleClick = (event: MouseEvent) => {
    if (menuImageRef.value && !menuImageRef.value.contains(event.target as Node)){
        showOption.value = false;
    }
}

const showExchangeWindow = () => {
    //Esto es para monstrar la ventana de cambio
    if (props.isExchange) {
        showExchange.value = true;
    }

}


/*
Datos de la ventana
 */
const showOption = ref<boolean>(false);

</script>

<template>
    <Head :title="title"/>

<!--    Contenido de la ventana-->
    <div class=" flex">
        <aside class=" fondo h-screen w-[10rem]">
<!--            Mostrar la imagen del menu-->
            <ImageMenu :url="props.auth.user.profile_photo_url"/>
<!--            Dividir la linea-->
            <Divider/>

            <div class="space-y-2">
                <LinkHeader
                    v-for="(item, index) in urlTab"
                    :key="index"
                    :title="item.label"
                    :active="isActive(item.urlActive)"
                    :href="item.url">
                    {{item.label}}
                    <i :class="item.icon"></i>
                </LinkHeader>
            </div>
        </aside>



<!-- Contenido de la ventana-->
        <div
            class="flex-col flex-1">

<!--            Cabecera de la ventana-->
            <header
                class=" h-[4rem] flex items-center justify-center space-x-3 w-full fondo z-20 border-b-2 text-sm">

<!--                Para el contenido-->
                <slot name="header"/>
            </header>

<!--            Contendio de la ventaa-->
            <div
                class="p-3 overflow-x-auto">
                <slot/>
            </div>
        </div>

        <FloatBox
            v-if="showExchange"
            header="Tasa de Cambio">
            <Exchange
                @closeWindow="showExchange = false"/>
        </FloatBox>
    </div>

</template>
