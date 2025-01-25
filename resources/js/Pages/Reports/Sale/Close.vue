<script setup lang="ts">
import {Head, useForm} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import TabLink from "@components/TabLink.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {userI} from "@/Interfaces/User";
import {totalSaleAmountI} from "@/Interfaces/Report";
import axios from "axios";
import {ref} from "vue";
import {errorHttp} from "@/Global/Alert";
import {getMoney} from "@/Global/Helpers";


/**
 * Propiedades
 */
const propsW =defineProps<{
    users: userI[],
    reports?: totalSaleAmountI,
}>()

/*
Datos de la ventana
 */
const processing = ref<boolean>(false);
const reportSale = ref<totalSaleAmountI | null>(null);


/**
 * Formularios
 */
const form = useForm({
    user: 0
});

/**
 * Ebnviar los datos
 */
const submit = () => {
    processing.value = true;
    axios.post(route('sale.get.close'),{
        ...form
    }).then((res) => {
        reportSale.value = res.data[0];
        processing.value = false;
    }).catch((err) => {
        errorHttp(`Error Al Obtener los datos :${err.message}`);
    });
}

</script>

<template>
    <Head title="Cierre" />
    <AppLayout>
        <template #header>
            <TabLink
                :href="route('sale.create')">
                Registrar
            </TabLink>
            <TabLink
                :href="route('sale.show')">
                Mostrar
            </TabLink>
            <TabLink
                :href="route('credit-note.show')">
                N. Credito
            </TabLink>
            <TabLink
                :active="true"
                :href="route('sale.close')">
                Cierre
            </TabLink>
        </template>
        <div class="max-w-[70rem] p-5 bg-blue-300 rounded-md">
            <div>
                <h3 class="title text-center">
                    Crear Cierre De Ventas
                </h3>
                <form
                    class="grid grid-cols-2 gap-3 items-end"
                    @submit.prevent="submit" >
                    <div>
                        <label
                            class="block"
                            for="User">Usuarios</label>
                        <select
                            v-model="form.user"
                            class="inputGeneral py-1"
                            name="use"
                            id="user">
                            <option :value="0">--Selecione Usuario</option>
                            <option
                                v-for="(item, index) in propsW.users"
                                :key="index"
                                :value="item.id">
                                {{ item.name }}
                            </option>
                        </select>
                    </div>

                    <div class="mt-3 text-right">
                        <PrimaryButton
                            :disabled="processing"
                            @click="submit">
                            Crear Cierre
                        </PrimaryButton>
                    </div>
                </form>

                <div class="border-t border-black mt-3 rounded-md"></div>
<!--                Informacin de la venta -->
                <div class="grid grid-cols-3 gap-3 mt-3">
                    <h3 class="text-xl col-span-full text-center">Reporte De Ventas Fecha :
                        <span class="font-bold">{{new Date().toLocaleDateString()}}</span>
                    </h3>
                    <div>
                        <label class="font-bold block" for="amount">Impuesto Total : </label>
                        <span class="inline-block bg-white px-3 py-1 rounded-md">
                            {{getMoney(reportSale?.sub_total)}}
                        </span>
                    </div>
                    <div>
                        <label class="font-bold block" for="amount">Sub Total :</label>
                        <span class="inline-block bg-white px-3 py-1 rounded-md" >
                            {{getMoney(reportSale?.sub_total)}}
                        </span>
                    </div>
                    <div>
                        <label class="font-bold block" for="amount">Total :</label>
                        <span class="inline-block bg-white px-3 py-1 rounded-md">
                            {{getMoney(reportSale?.amount)}}
                        </span>
                    </div>
                    <div>
                        <label class="font-bold block" for="amount">Beneficio :</label>
                        <span class="inline-block bg-white px-3 py-1 rounded-md">
                            {{getMoney(reportSale?.benefits)}}
                        </span>
                    </div>
                    <div>
                        <label class="font-bold block" for="amount">Descuento Aplicado :</label>
                        <span class="inline-block bg-white px-3 py-1 rounded-md">
                            {{getMoney(reportSale?.discount_amount)}}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
