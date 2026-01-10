<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {Head} from "@inertiajs/vue3";
import {onMounted} from "vue";
import LinkHeader from "@components/LinkHeader.vue";
import ProductMostSold from "@/Charts/ProductMostSold.vue";
import {mostSoldI} from "@/Interfaces/ReportInterface";



/*
 * Propiedades de la ventana
 */
defineProps<{
    mostSold: mostSoldI[];
}>();

/*
 * Al momento de cargar
 */
onMounted(()=>{

})


/*
 * Dato del formulario
 */
// const form = useForm({
//     from: "",
//     to:"",
//     general: ""
//
// });


/*
 * Datos para rellenar
 */
// const info = ref({
//    tax: 0,
//    sub_total: 0,
//    amount: 0
// });

</script>

<template>
<!--    Titulo de la ventana-->
    <Head title="Reportes"/>


    <AppLayout>
<!--      Naveghaciond e la ventana-->
        <template #header >
            <LinkHeader href="/">
                Ventas
            </LinkHeader>
            <LinkHeader href="/">
                Inventario
            </LinkHeader>
            <LinkHeader href="/">
                Financiero
            </LinkHeader>
            <LinkHeader href="/">
                Cliente
            </LinkHeader>
            <LinkHeader href="/">
                Servicios
            </LinkHeader>
            <LinkHeader href="/">
                Devoluciones
            </LinkHeader>
            <LinkHeader href="/">
                Decuento y Promociones
            </LinkHeader>
        </template>

        <div class="bg-gray-200 rounded-md p-5 max-w-[70rem]">

<!--         Productos  Mas Vendido-->
            <ProductMostSold
                :mostSold="mostSold" />


        </div>
    </AppLayout>

</template>

<style scoped>

</style>
