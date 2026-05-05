<script setup lang="ts">
import AppLayout from "@layout/AppLayout.vue";
import {Breadcrumb, Button, Card, FloatLabel, InputText} from "primevue";
import {itemsSettings} from "@/Helpers/SettingHelpers";
import {SquarePlus} from "@lucide/vue";

</script>

<template>
    <AppLayout>
        <Card>
            <template #title>
                <div>
                    <Breadcrumb :model="itemsSettings" />
                </div>
                <div class="text-right">

<!--                    <Button @click="createTax = true" title="Nuevo"  >-->
<!--                        <template #icon>-->
<!--                            <SquarePlus/>-->
<!--                        </template>-->
<!--                    </Button>-->
                </div>
            </template>
            <template #content>
                <form action="" class="grid grid-cols-2 gap-3">
                    <div class="col-span-full">
                        <h3 class="text-2xl font-bold text-center" >Lista de Precio</h3>
                    </div>
                    <FloatLabel>
                        <InputText fluid name="currency" />
                        <label for="name">Nombre</label>

                    </FloatLabel>
                    <FloatLabel>
                        <InputText fluid name="currency" />
                        <label for="currency">Moneda</label>
                    </FloatLabel>
                    <div class="mt-3 text-right col-span-full space-x-3">
                        <Button label="Limpiar" />
                        <Button label="Registrar" />
                    </div>
                </form>
            </template>
        </Card>
    </AppLayout>
</template>

<style scoped>

</style>
