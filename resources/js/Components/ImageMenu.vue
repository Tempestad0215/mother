<script setup lang="ts">
import {Link, router} from "@inertiajs/vue3";
import { Ref, ref} from "vue";
import {useRoute} from "ziggy-js";



const route = useRoute();
defineProps<{
    url: string;
}>();

//Par mostar la ventana
const show:Ref<boolean> = ref(false);


/**
 * funcion para salir
 */
const logOut = () => {
    router.post(route('logout'));
}


</script>

<template>
    <div class="mt-3 relative ">
        <img
            @click="show = !show"
            class="rounded-full w-[5rem] mx-auto cursor-pointer"
            :src="url ? url : ''"
            alt="Imagen de nombre">


        <Transition>
            <div
                v-if="show"
                ref="menuImageRef"
                class=" absolute top-[2rem] left-[7.5rem] w-52 rounded-md bg-gray-100 z-40 border border-orange-500">
                <ol
                    class=" text-xl text-center select-none rounded-md ">
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
                        :href="route('setting.index')">
                        Ajuste
                    </Link>
                    <button
                        class="image-link w-full"
                        @click="logOut">
                        Salir
                    </button>

                </ol>
            </div>
        </Transition>
    </div>


</template>
