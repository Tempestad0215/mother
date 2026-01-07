<script setup lang="ts">
import {Link, router} from "@inertiajs/vue3";
import { Ref, ref} from "vue";
import {useRoute} from "ziggy-js";
import {Avatar, Popover, Menu} from "primevue";


const route = useRoute();
defineProps<{
    url: string;
}>();

const op = ref()
//Par mostar la ventana
const show:Ref<boolean> = ref(false);
const items = ref([
    {label: 'Perfil', icon: 'pi pi-user-edit'},
    {label: 'Ajustes', icon: 'pi pi-cog'},
    {label: 'salir', icon: 'pi pi-sign-out'},
])


/**
 * funcion para salir
 */
const logOut = () => {
    router.post(route('logout'));
}

const toggle = (event:Event) => {
    op.value.toggle(event)
}


</script>

<template>
    <div class="mt-3 relative text-center ">
        <Avatar
            @click="toggle"
            size="large"
            :image="url" shape="circle" />

        <Popover ref="op" class="p-0!"  >
            <Menu :model="items" />
        </Popover>
    </div>


</template>
