<script setup lang="ts">
import {Head} from "@inertiajs/vue3";
import AppLayout from "@layout/AppLayout.vue";
import TabLink from "@components/TabLink.vue";
import PrimaryButton from "@components/PrimaryButton.vue";


/**
 * Propiedades
 */
const propsW =defineProps<{
    users: any
}>()

/**
 * Ebnviar los datos
 */
const submit = () => {

}

</script>

<template>
    <Head title="Cierre" />
    <AppLayout>
        <template #header>
            <TabLink
                :href="route('sale.create')">
                Registrar Ventas
            </TabLink>
            <TabLink
                :href="route('sale.show')">
                Mostrar Ventas
            </TabLink>
            <TabLink
                :href="route('credit-note.show')">
                Nosta De Creditos
            </TabLink>
            <TabLink
                :active="true"
                :href="route('credit-note.show')">
                Cierre De Ventas
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
                            class="inputGeneral py-1"
                            name="use"
                            id="user">
                            <option value="">--Selecione Usuario</option>
                            <option
                                v-for="(item, index) in propsW.users"
                                :key="index"
                                :value="item.id">
                                {{ item.name }}
                            </option>
                        </select>
                    </div>

                    <div class="mt-3 text-right">
                        <PrimaryButton>
                            Craer Cierre
                        </PrimaryButton>
                    </div>
                </form>
            </div>

        </div>
    </AppLayout>
</template>
