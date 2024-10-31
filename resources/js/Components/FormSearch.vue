<script setup lang="ts">
import InputLabel from './InputLabel.vue';

/*
Propiedades de la ventana
 */
const props = defineProps<{
    modelValue: string | number,
    holder: string | null,
    selectValue: number

}>()

/*
Emitir los eventos para actualizar los datos
 */
const emit = defineEmits<{
    (e: 'update:modelValue', value:any ):void,
    (e: 'update:selectValue', value:number):void,
}>();


/**
 *Enviar los datos el imput
 * @param e
 */
const sendData = (e:Event) => {
    const target = e.target as HTMLInputElement;

    emit('update:modelValue',target.value);
}

/**
 *Enbviar los datos del select
 * @param e
 */
const updateSelectValue = (e:Event) => {
    //Tomar los datos para enviar
    const target = e.target as HTMLInputElement;
    //Emitir el evnetos y los datos
    emit('update:selectValue', parseInt(target.value))
}


</script>


<template>
    <div class="w-full">
        <InputLabel
            for="search"
            value="Buscar" />

        <div class="relative">
            <input
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm min-w-[500px] w-[500px] max-w-sm pr-[60px]"
                :value="props.modelValue"
                @input="sendData($event)"
                :placeholder="props.holder ? props.holder : ''"
                type="search" />
            <div class="absolute inset-y-0 right-0 flex items-center">
                <select
                    class="h-full rounded-md border-0 bg-transparent py-0 pl-2 pr-7 text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm"
                    name="perPage"
                    :value="props.selectValue"
                    @change="updateSelectValue($event)"
                >
                    <option selected >15</option>
                    <option>{{ 30 }}</option>
                    <option>{{ 45 }}</option>
                    <option>{{ 60 }}</option>
                    <option>{{ 100 }}</option>
                    <option>{{ 150 }}</option>
                </select>
            </div>

        </div>

    </div>

</template>
