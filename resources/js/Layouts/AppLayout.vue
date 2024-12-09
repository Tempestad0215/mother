<script setup lang="ts">
import {onMounted, onUnmounted, ref} from 'vue';
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import LinkHeader from "@components/LinkHeader.vue";
import Divider from "@components/Divider.vue";
import ImageMenu from "@components/ImageMenu.vue";


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


/*
Al momento de cargar
 */
onMounted(()=>{
   document.addEventListener("click", handleClick)
});

/*
Cuando se cancela el evento
 */
onUnmounted(() => {
    document.removeEventListener("click", handleClick)
})



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

//Verificar si es admin
const checkIsAdmin = ():boolean => {
    return props.auth.user.role == 'admin';
}


/*
Datos de la ventana
 */
const showOption = ref<boolean>(false);
const menuItem = ref([
    {
        //Clientes
        label: 'Clientes',
        icon: 'fa-solid fa-user-group',
        items: [
            {
                label: 'Registrar',
                icon: 'fa-solid fa-plus',
                url: route('client.create'),
            },
            {
                label: 'Mostrar',
                icon: 'fa-solid fa-list-ol',
                url: route('client.show')
            }
        ],

    },
    {
        //Categorias
        label: 'Categorias',
        icon: 'fa-solid fa-folder-tree',
        items: [
            {
                label: 'Registrar',
                icon: 'fa-solid fa-plus',
                url: route('category.create')
            }
        ],

    },
    {
        //Suplidores
        label: 'Suplidores',
        icon: 'fa-solid fa-truck-field',
        items: [
            {
                label: 'Registrar',
                icon: 'fa-solid fa-plus',
                url: route('supplier.create')
            }
        ],

    },
    {
        //Productos
        label: 'Productos',
        icon: 'fa-solid fa-boxes-stacked',
        items: [
            {
                label: 'Registrar',
                icon: 'fa-solid fa-plus',
                url: route('product.create')
            },
            {
                label: 'Mostrar',
                icon: 'fa-solid fa-list-ol',
                url: route('product.show')
            },
            {
                label: 'Entrada',
                icon: 'fa-solid fa-warehouse',
                items: [
                    {
                        label: 'Recepción',
                        icon: 'fa-solid fa-plus',
                        url: route('in.create')
                    },
                    {
                        label: 'Mostrar',
                        icon: 'fa-solid fa-list-ol',
                        url: route('in.show')
                    }

                ]
            }
        ],

    },
    {
        //Ventas
        label: 'Ventas',
        icon: 'fa-solid fa-cash-register',
        items: [
            {
                label: 'Registrar',
                icon: 'fa-solid fa-plus',
                url: route('sale.create')
            },
            {
                label: 'Mostrar',
                icon: 'fa-solid fa-list-ol',
                url: route('sale.show')
            },
            {
                label: 'Informes',
                icon: 'fa-solid fa-chart-pie',
                items: [
                    {
                        label: 'Cuadre Caja',
                        icon: 'fa-solid fa-cash-register',
                        url: route('sale.report.index')
                    }
                ]
            }
        ],
    },
    {
        //Reportes
        label: 'Reportes',
        icon: 'fa-solid fa-chart-line',
        items: [
            {
                label: 'Rango',
                icon: 'fa-solid fa-calendar-days',
                url: route('report-sale.index')
            },

        ],

    },
    {
        separator: true,

    },
    {
        label: 'Miembros',
        icon: 'fa-solid fa-id-card-clip',
        items: [
            {
                label: 'Usuarios',
                icon: 'fa-solid fa-users',
                url: route('register')
            },
            {
                label: 'Pefil',
                icon: 'fa-solid fa-id-card',
                url: route('profile.show'),
            }
        ],


    },
    {
        //Ajsutes de usuario
        label: 'Ajustes',
        icon: 'fa-solid fa-screwdriver-wrench',
        items: [
            {
                label: 'General',
                icon: 'fa-solid fa-gears',
                url: route('setting.index')
            },
            {
                label: 'Secuencias',
                icon: 'fa-solid fa-arrow-down-1-9',
                url: route('sequence.create'),
            }

        ],

    },
    {
        separator: true,
    },
    {
        //Salir de la app
        label: 'Salir',
        icon: 'fa-solid fa-right-from-bracket',
        command: () => {
            router.post(route('logout'))
        }
    },

]);






</script>

<template>
    <Head :title="title"/>

<!--    Contenido de la ventana-->
    <div class=" flex">
        <aside class=" bg-gray-100 h-screen w-[10rem]">
<!--            Mostrar la imagen del menu-->
            <ImageMenu :url="props.auth.user.profile_photo_url"/>
<!--            Dividir la linea-->
            <Divider/>

            <div>
                <LinkHeader
                    title="Cliente"
                    :href="route('category.create')">
                    Cliente
                    <i class="relative left-12 fa-solid fa-user"></i>
                </LinkHeader>
            </div>

        </aside>



<!-- Contenido de la ventana-->
        <div
            class="flex-col flex-1">

<!--            Cabecera de la ventana-->
            <header
                class=" h-[4rem] w-full bg-gray-100 z-20">

<!--                Para el contenido-->
                <slot name="header"/>
            </header>

<!--            Contendio de la ventaa-->
            <div
                class="p-3 max-h-[90vh] overflow-x-auto">
                <slot/>
            </div>
        </div>
    </div>

</template>
