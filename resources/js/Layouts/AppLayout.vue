<script setup lang="ts">
import { ref} from 'vue';
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import PanelMenu from 'primevue/panelmenu';


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
        visible: checkIsAdmin()
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
        visible: checkIsAdmin()
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
        visible: checkIsAdmin()
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
        visible: checkIsAdmin()
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
        visible: checkIsAdmin()
    },
    {
        separator: true,
        visible: checkIsAdmin()
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
        visible: checkIsAdmin()

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
        visible: checkIsAdmin()
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


    <div class="">
        <aside class=" fixed bg-gray-200 w-[10rem] h-screen z-30">
            <img
                @click="showOption = !showOption"
                class="rounded-full mx-auto mt-5"
                :src="props.auth.user ? props.auth.user.profile_photo_url : ''"
                alt="Imagen de nombre">

            <div class="mt-5">
                <PanelMenu
                    :model="menuItem"/>
            </div>



        </aside>
        <Transition>
            <div
                v-if="showOption"
                class=" absolute top-14 left-12 w-52 rounded-md bg-gray-200 z-40 border-2 border-gray-500">
                <ol class=" text-xl text-center select-none">
                    <Link
                        class="image-link"
                        :href="route('profile.show')">
                        Perfil
                    </Link>
                    <Link
                        class="image-link"
                        :href="route('register')">
                        Usuario
                    </Link>
                    <Link
                        class="image-link"
                        method="post"
                        :href="route('logout')">
                        Salir
                    </Link>

                </ol>
            </div>
        </Transition>


<!-- Contenido de la ventana-->
        <div
            class="flex-col flex-1">
<!--            <div class="float-right">-->
<!--                &lt;!&ndash;                Para el modo nocturno&ndash;&gt;-->
<!--                <ToggleSwitch-->
<!--                    v-model="darkMode" />-->
<!--            </div>-->
            <header
                class=" flex items-center justify-center space-x-3 fixed top-0 h-[4rem] flex-1 w-full bg-gray-200 z-20">

<!--                Para el contenido-->
                <slot name="header"/>
            </header>
            <div
                class="flex pl-[11rem] pt-[5rem] rounded-md ">
                    <div
                        class=" flex-1 md:max-w-[1100px] mx-auto bg-gray-200 rounded-md">
                        <slot/>
                    </div>

            </div>
        </div>
    </div>

</template>
