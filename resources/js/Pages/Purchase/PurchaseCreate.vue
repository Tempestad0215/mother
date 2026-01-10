<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import SelectOption from "@components/SelectOption.vue";
import {getMoney, moneyConfig} from "@/Global/Helpers";
import {Money} from "v-money3";
import {purchaseInfoI} from "@/Interfaces/PurchaseInterface";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import TabLink from "@components/TabLink.vue";
import FloatBox from "@components/FloatBox.vue";
import FShow from "@/Pages/Products/FShow.vue";
import {ref} from "vue";
import {productFullI, productI} from "@/Interfaces/ProductInterface";
import {useRoute} from "ziggy-js";


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
   Formulario
 */
const form = useForm({
    uuid:"",
    info: [{
        uuid:"",
        name: "",
        quantity: 0,
        tax: 0,
        price: 0,
        tax_rate: 0,
        discount: 0,
        amount: 0,
        status: 0,
    }] as purchaseInfoI[],
    tax_total: 0,
    discount_total: 0,
    sub_total: 0,
    amount: 0,
    comment:"",
});


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
    <Head title="Compras" />
    <AppLayout>
        <template #header>
            <TabLink
                :href="route('supplier.create')">
                Registrar
            </TabLink>
            <TabLink
                :href="route('supplier.show')">
                Mostrar
            </TabLink>
            <TabLink
                :active="true"
                :href="route('purchase.index')">
                Compras
            </TabLink>
        </template>

        <form
            class="bg-blue-300 p-5 rounded-md max-w-[70rem] mx-auto"
            action="">
            <h3 class="title text-center">
                Compras
            </h3>

            <!--Datos de proveedor-->
            <fieldset>
                <legend>
                    Datos De Proveedor
                </legend>
                <div class="grid grid-cols-2 gap-3 ">
                    <!-- Para seleccionar los proveedores -->
                    <SelectOption
                        class="!w-[30rem]"
                        option-label=""
                        option-value=""
                        :options="[2,2,3,4,5,6]"/>
                    <div
                        class="text-right">
                        <!--Fecha del documentos-->
                        <div>
                            <InputLabel
                                class="inline"
                                for="date"
                                value="Documento"/>
                            <TextInput
                                type="date"
                                id="date"
                                name="date"/>
                        </div>
                        <!-- Fecha de vencimiento -->
                        <div
                            class="mt-2">
                            <InputLabel
                                class="inline"
                                for="date" value="Vencimiento"/>
                            <TextInput
                                type="date"
                                id="date"
                                name="date"/>
                        </div>
                    </div>
                </div>




                <!--  -->
                <table class="styleTable w-full mt-5">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto/Servicio</th>
                        <th>Cantidad</th>
                        <th>Itbis</th>
                        <th>Precio</th>
                        <th>Desc.</th>
                        <th>Importe</th>
                        <th>Act</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, index) in form.info" :key="index">
                        <td>
                            {{index+1}}
                        </td>
                        <td>
                            <div class="relative">
                                <TextInput
                                    class="bg-transparent w-full"
                                    v-model="item.name"/>
                                <i
                                    @click="showProduct = !showProduct"
                                    class=" flex items-center inset-y-0 absolute p-3 right-0 icon-efect fa-solid fa-magnifying-glass"></i>
                            </div>
                        </td>
                        <td class="max-w-[5rem]">
                            <Money
                                class=" bg-transparent h-[2rem] max-w-[6rem] rounded-md border-none"
                                v-bind="moneyConfig"
                                v-model.number="item.quantity"/>
                        </td>
                        <td>

                        </td>

                        <!--                                        Precio solo modificar si es servicio-->
                        <td class="max-w-[5rem]">
                            <!--                            <span v-if="item.type === 'producto'">-->
                            <!--                                {{getMoney(item.price)}}-->
                            <!--                            </span>-->
                            <Money
                                class=" bg-transparent h-[2rem] max-w-[6rem] rounded-md border-none"
                                v-bind="moneyConfig"
                                v-model.number="item.price"/>
                        </td>
                        <td class="max-w-[4rem]">
                            <Money
                                class=" bg-transparent h-[2rem] max-w-[5rem] rounded-md border-none"
                                v-bind="moneyConfig"
                                :min="0"
                                :max="100"
                                v-model.number="item.discount"/>
                        </td>
                        <td>

                        </td>

                        <!--Para eliminar los datos de la lista-->
                        <td>
                            <i
                                class=" icon-efect mr-3 fa-solid fa-circle-plus"></i>
                            <i
                                class=" icon-efect text-red-500 fa-solid fa-circle-xmark"></i>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <!--                            Comentario de la venta-->
                <div class="grid grid-cols-4 items-center gap-4">
                    <textarea
                        placeholder="Comentario"
                        v-model.trim="form.comment"
                        cols="60"
                        class="area col-span-2">
                    </textarea>
                    <div class=" col-end-7 col-span-2">
                        <table>
                            <tbody>
                            <tr>
                                <th class="text-left">Itbis :</th>
                                <td>{{getMoney(form.tax_total)}}</td>
                            </tr>
                            <tr>
                                <th class="text-left">Sub Total :</th>
                                <td>{{getMoney(form.sub_total)}}</td>
                            </tr>
                            <tr>
                                <th class="text-left">Decuento :</th>
                                <td>{{getMoney(form.discount_total)}}</td>
                            </tr>
                            <tr>
                                <th class="text-left">Total :</th>
                                <td class="w-[15rem]" >{{getMoney(form.amount)}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </fieldset>
        </form>

    </AppLayout>

    <!-- Ventana de productos-->

    <FloatBox
        header="Productos"
        @close="showProduct = false"
        v-if="showProduct">
        <FShow
            class=" bg-blue-300  rounded-md px-10 py-5"
            @select="getData"
            :products="propsW.products"/>
    </FloatBox>
</template>
