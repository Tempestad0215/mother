<script setup lang="ts">
import {ref} from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import {pageI} from "@/Interfaces/Global";




const page:pageI = <pageI>usePage();

defineProps({
    title: String,
});

const showOption = ref<boolean>(false);



const logout = () => {
    router.post(route('logout'));
};

const isUrl = (params:string) => {

    return page.url.startsWith(params);
}

const profile = () => {
    router.get(route('profile.show'));
}


</script>

<template>
    <Head :title="title"/>
    <div class="">
        <aside class=" fixed bg-gray-200 w-20 h-screen z-30">
            <div>

            </div>
            <img
                @click="showOption = !showOption"
                class="rounded-full mx-auto mt-5"
                :src="page.props.auth.user ? page.props.auth.user.profile_photo_url : ''"
                alt="Imagen de nombre">

            <ol
                class=" text-2xl space-y-2 text-center mt-5 border-t-2 border-black pt-5">
                <li>
                    <NavLink
                        title="Clientes"
                        :active="isUrl('/client')"
                        :href="route('client.create')">
                        <i class="fa-solid fa-users"></i>
                    </NavLink>
                </li>
                <li>
                    <Link
                        title="Categorias"
                        :active="isUrl('/category')"
                        :href="route('category.create')">
                        <i class="fa-solid fa-code-branch"></i>
                    </Link>
                </li>
                <li>
                    <Link
                        title="Suplidores"
                        :href="route('supplier.create')">
                        <i class="fa-solid fa-truck-field"></i>
                    </Link>
                </li>
                <li>
                    <Link
                        title="Producto"
                        :href="route('product.create')">
                        <i class="fa-solid fa-box-open"></i>
                    </Link>
                </li>
                <li>
                    <Link
                        title="Entrada"
                        :href="route('product-in.create')">
                        <i class="fa-solid fa-dolly"></i>
                    </Link>
                </li>
                <li>
                    <Link
                        title="Ventas"
                        :href="route('product-sale.create')">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </Link>
                </li>
                <li>
                    <Link
                        title="Reportes"
                        :href="route('report.index')">
                        <i class="fa-solid fa-chart-pie"></i>
                    </Link>
                </li>


            </ol>

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


        <div class="flex-col flex-1 overflow-hidden">
            <header class=" flex items-center justify-center space-x-3 fixed top-0 left-20 h-20 max-h-16 flex-1 w-full bg-gray-200 z-20 px-5">
                <slot name="header"/>
            </header>
            <div class="mt-20 ml-24 mr-4">
                <slot/>
            </div>
        </div>
    </div>

</template>
