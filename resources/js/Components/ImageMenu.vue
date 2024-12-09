<script setup lang="ts">
import {Link} from "@inertiajs/vue3";
import {onMounted, onUnmounted, Ref, ref} from "vue";

defineProps<{
    url: string;
}>();

//Par mostar la ventana
const show:Ref<boolean> = ref(false);
const menuImageRef = ref<HTMLImageElement |null>(null);

//al momento de cargar
onMounted(()=>{
    document.addEventListener('click', handleClick);
});

//al momento de desmontar
onUnmounted(()=>{
    document.removeEventListener('click', handleClick);
});



const handleClick = (e: MouseEvent) => {
    if (show.value && !menuImageRef.value?.contains(e.target as Node)){
        show.value = false;
    }

    console.log(menuImageRef.value);
}


</script>

<template>
    <div>
        <img
            @click="show = !show"
            class="rounded-full w-[5rem] mx-auto"
            :src="url ? url : ''"
            alt="Imagen de nombre">

        <Transition>
            <div
                v-if="show"
                ref="menuImageRef"
                class=" absolute top-14 left-12 w-52 rounded-md bg-gray-100 z-9999 border-2 border-green-700">
                <ol

                    class=" text-xl text-center select-none ">
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
                    <!--                    <button type="button">-->
                    <!--                        Salir-->
                    <!--                    </button>-->
                    <!--                    <Link-->
                    <!--                        class="image-link"-->
                    <!--                        method="__post"-->
                    <!--                        :href="route('logout')">-->
                    <!--                        Salir-->
                    <!--                    </Link>-->

                </ol>
            </div>
        </Transition>
    </div>


</template>

<style scoped>

</style>
