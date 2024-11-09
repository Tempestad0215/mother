<script setup lang="ts">
import TextInput from "@components/TextInput.vue";
import InputError from "@components/InputError.vue";
import InputLabel from "@components/InputLabel.vue";



const from = defineModel('from');
const to = defineModel('to')



/*
Al momento de cargar
 */



/*
Funciones
 */

</script>

<template>
    <div
        class="flex">

        <!--                    Fecha desde-->
        <div>
            <InputLabel for="from" value="Desde" />
            <TextInput
                v-model="from"
                id="from"
                name="from"
                type="datetime-local"/>
            <InputError message=""/>
        </div>

        <!--                    Fecha Hasta-->
        <div class="ml-5">
            <InputLabel for="to" value="Hasta" />
            <TextInput
                v-model="to"
                id="to"
                name="to"
                type="datetime-local"/>
            <InputError message=""/>
        </div>
    </div>
</template>

<style scoped>

</style>
