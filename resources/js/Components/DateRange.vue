<script setup lang="ts">
import TextInput from "@components/TextInput.vue";
import InputError from "@components/InputError.vue";
import InputLabel from "@components/InputLabel.vue";


/*
Propiedades de la ventana
 */
const propsW = defineProps<{
    fromValue: string,
    toValue: string,
}>();


/*
Emitir los eventos
 */
const emit = defineEmits<{
    //Evento desde
    (e: 'update:from', value: string):void;
    //Eventos hasta
    (e: 'update:to', value: string):void;
}>()



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
                @change="emit('update:from', ($event.target as HTMLInputElement).value)"
                :value="propsW.fromValue"
                id="from"
                name="from"
                type="datetime-local"/>
            <InputError message=""/>
        </div>

        <!--                    Fecha Hasta-->
        <div class="ml-5">
            <InputLabel for="to" value="Hasta" />
            <TextInput
                @change="emit('update:to', ($event.target as HTMLInputElement).value)"
                :value="propsW.toValue"
                id="to"
                name="to"
                type="datetime-local"/>
            <InputError message=""/>
        </div>
    </div>
</template>

<style scoped>

</style>
