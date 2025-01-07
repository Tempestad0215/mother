<script setup lang="ts">
import {onMounted, onUnmounted, ref} from 'vue';
import {Head, usePage} from '@inertiajs/vue3';
import LinkHeader from "@components/LinkHeader.vue";
import Divider from "@components/Divider.vue";
import ImageMenu from "@components/ImageMenu.vue";
import FloatBox from "@components/FloatBox.vue";
import Exchange from "@/Pages/Setting/Exchange.vue";


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
/*
Al momento de cargar
 */
onMounted(()=>{
   document.addEventListener("click", handleClick);


    console.log(window.location.pathname)
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
        <aside class=" bg-blue-300 h-screen w-[10rem]">
<!--            Mostrar la imagen del menu-->
            <ImageMenu :url="props.auth.user.profile_photo_url"/>
<!--            Dividir la linea-->
            <Divider/>

            <div class="space-y-2">
                <LinkHeader
                    :class="{'link-active': isActive('/client')}"
                    title="Clientes"
                    :href="route('client.create')">
                    Clientes
                    <i class=" fa-solid fa-user"></i>
                </LinkHeader>
                <LinkHeader
                    :class="{'link-active': isActive('/category')}"
                    title="Categorias"
                    :href="route('category.create')">
                    Categorias
                    <i class="fa-solid fa-code-branch"></i>
                </LinkHeader>
                <LinkHeader
                    :class="{'link-active': isActive('/supplier')}"
                    title="Suplidores"
                    :href="route('supplier.create')">
                    Suplidores
                    <i class="fa-solid fa-truck-field"></i>
                </LinkHeader>
                <LinkHeader
                    :class="{'link-active': isActive('/product')}"
                    title="Productos"
                    :href="route('product.create')">
                    Productos
                    <i class="fa-solid fa-boxes-packing"></i>
                </LinkHeader>
                <LinkHeader
                    :class="{'link-active': isActive('/sale')}"
                    title="Ventas"
                    :href="route('sale.create')">
                    Ventas
                    <i class="fa-solid fa-cash-register"></i>
                </LinkHeader>
                <LinkHeader
                    :class="{'link-active': isActive('/report')}"
                    title="Ventas"
                    :href="route('report-sale.index')">
                    Reportes
                    <i class="fa-solid fa-clipboard"></i>
                </LinkHeader>

            </div>

        </aside>



<!-- Contenido de la ventana-->
        <div
            class="flex-col flex-1">

<!--            Cabecera de la ventana-->
            <header
                class=" h-[4rem] flex items-center justify-center space-x-3 w-full bg-blue-300 z-20 border-b-2">

<!--                Para el contenido-->
                <slot name="header"/>
            </header>

<!--            Contendio de la ventaa-->
            <div
                class="p-3 max-h-[90vh] overflow-x-auto">
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
