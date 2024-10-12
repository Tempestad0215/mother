<script setup lang="ts">
import {computed, ref} from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';




/*
Destructurar las variables
 */
const {url, props} = usePage();
const {auth} = props;

/*
Propiedads de la ventana
 */
defineProps({
    title: String,
});

/*
Datos de la ventana
 */
const showOption = ref<boolean>(false);



/*
Propiedades computada
 */
const checkRole = computed(()=>{
   let role:string = props.auth.user.role;

   if(role !== 'user')
   {
       return true
   }

});



/*
Funciones
 */
/**
 * Salir de la app
 */
const logout = () => {
    router.post(route('logout'));
};

/**
 * Verificar si la url comienza con el parametro
 * @param params
 */
const isUrl = (params:string) => {

    return url.startsWith(params);
}

</script>

<template>
    <Head :title="title"/>


    <div class="">
        <aside class=" fixed bg-gray-200 w-20 h-screen z-30">
            <img
                @click="showOption = !showOption"
                class="rounded-full mx-auto mt-5"
                :src="props.auth.user ? props.auth.user.profile_photo_url : ''"
                alt="Imagen de nombre">

            <ol
                class=" text-2xl space-y-2 text-center mt-5 border-t-2 border-black pt-5">
                <li
                    v-if="checkRole">
                    <NavLink
                        title="Clientes"
                        :active="isUrl('/client')"
                        :href="route('client.create')">
                        <i class=" fa-solid fa-users"></i>
                    </NavLink>
                </li>
                <li v-if="checkRole">
                    <NavLink
                        title="Categorias"
                        :active="isUrl('/category')"
                        :href="route('category.create')">
                        <i class="fa-solid fa-code-branch"></i>
                    </NavLink>
                </li>
                <li v-if="checkRole">
                    <NavLink
                        title="Suplidores"
                        :active="isUrl('/supplier')"
                        :href="route('supplier.create')">
                        <i class="fa-solid fa-truck-field"></i>
                    </NavLink>
                </li>
                <li v-if="checkRole">
                    <NavLink
                        title="Producto"
                        :active="isUrl('/product')"
                        :href="route('product.create')">
                        <i class="fa-solid fa-box-open"></i>
                    </NavLink>
                </li>
                <li v-if="checkRole">
                    <NavLink
                        title="Entrada"
                        :active="isUrl('/in')"
                        :href="route('in.create')">
                        <i class="fa-solid fa-dolly"></i>
                    </NavLink>
                </li>
                <li>
                    <NavLink
                        title="Ventas"
                        :active="isUrl('/sale')"
                        :href="route('sale.create')">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </NavLink>
                </li>
                <li v-if="checkRole">
                    <NavLink
                        title="Reportes"
                        :active="isUrl('/report')"
                        :href="route('report.index')">
                        <i class="fa-solid fa-chart-pie"></i>
                    </NavLink>
                </li>

                <li
                    class="absolute bottom-0 right-8 hover:scale-125 duration-300"
                    v-if="checkRole">
                    <Link
                        title="Ajustes"
                        :href="route('setting.index')">
                        <i class="fa-solid fa-sliders"></i>
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


        <div
            class="flex-col flex-1 overflow-hidden">
            <header
                class=" flex items-center justify-center space-x-3 fixed top-0 left-20 h-20 max-h-16 flex-1 w-full bg-gray-200 z-20 px-5">
                <slot name="header"/>
            </header>
            <div
                class="flex-1 ml-[80px] justify-center mt-[64px] rounded-md p-5 ">
                    <div class="max-w-[1100px] mx-auto">
                        <slot/>
                    </div>

            </div>
        </div>
    </div>

</template>
