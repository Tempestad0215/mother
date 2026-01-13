<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {ref} from "vue";
import {productFullI, productI} from "@/Interfaces/ProductInterface";
import {useRoute} from "ziggy-js";
import {DataTable, Column, Dialog} from "primevue";



const route = useRoute();
/*
Propiedades
 */
const propsW = defineProps<{
    products: productI
}>();

/*
Datos de la ventana
 */
const showProduct = ref<boolean>(false)




/*
funciones
 */
const getData = (item:productFullI) => {
    //Obtener los datos de productos
    // let info:purchaseInfoI | undefined = form.info.find((el) => el.name === item.uuid);
    //
    // Verificar si el producto exite
    // if (info?.uuid === item.id)
    // {
    //     info.quantity += 1.00;
    //     showProduct.value = false;
    //
    // }else{
    //
    //     //Pasar los datos al formulario
    //     form.info.push({
    //         uuid:"",
    //         amount: 0,
    //         discount: item.discount,
    //         quantity: 1,
    //         price: item.price,
    //         name: item.name,
    //         tax: item.tax,
    //         tax_rate: item.tax_rate / 100,
    //         status: 1
    //     });
    //
    //     //Cerrar la ventana
    //     showProduct.value = false;
    // }

    // //Conseguir el index para poder realizar el cálculo
    // let index = form.info.findIndex((el) => el.uuid === item.uuid);

    //Calcular el indice
    // totalAmount(index);
}

</script>

<template>
    <AppLayout>
        <DataTable>
            <Column header="ID" />
            <Column header="Name" />
        </DataTable>
        <Dialog
            modal
            header="Creacion de Compra">

        </Dialog>
    </AppLayout>
</template>
