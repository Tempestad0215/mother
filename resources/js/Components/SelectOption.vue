<script setup lang="ts">
import {onMounted, onUnmounted, onUpdated, ref, type Ref} from 'vue'


/**
 * Datos para la ventana
 */
const propsW = defineProps<{
    defaultValue?: string;
    labelValue: string;
    idValue?: string;
    isReadOnly?: boolean;
    optionLabel: string;
    optionValue: string;
    options: Record<string, unknown>[];
    placeholder?: string;

}>();

/*
datos para enviar
 */
const model = defineModel();
const modelShow = defineModel('show');


/*
Datos de la ventana
 */
const showData:Ref<boolean> = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);


onMounted(() => {
    document.addEventListener('click', handleClickOutside);

});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});


/*
Funciones
 */

const sendData = (item:Record<string, unknown>) => {

    //Verificar si existe la propieda
    if (propsW.optionValue && item.hasOwnProperty(propsW.optionValue)) {
        // Para enviar el valor
        model.value = item[propsW.optionValue];
        //Para mostrar en el label
        modelShow.value = item[propsW.optionLabel];

        //Cerrar la ventana
        showData.value = false;
    }

}

// Cierra el menú si el clic ocurre fuera del componente
const handleClickOutside = (event: MouseEvent) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
        showData.value = false;
    }

};


</script>

<template>
    <div
        ref="dropdownRef"
        class="relative w-fit ">
        <input
            @click="showData = !showData"
            class=" border border-orange-500 px-2 pr-6 min-w-full rounded-md focus:ring-0 focus:border-blue-800 focus:outline-none h-[2.5rem]"
            :class="{'read-only': isReadOnly}"
            type="text"
            :placeholder="propsW.placeholder"
            v-model="modelShow"
            :readonly="isReadOnly"
            name=""
            id="">
        <i class="absolute flex p-1 right-1 items-center inset-y-0 transition duration-150 pointer-events-none fa-solid fa-arrow-down"
           :class="{'rotate-180' : showData}"></i>

        <ol
            class=" opacity-0 -z-50 bg-blue-300 absolute w-full rounded-md transition duration-100 ease-linear border border-blue-800"
            :class="{'opacity-100 z-10': showData}">
            <li
                class="px-3 border-b border-blue-600 rounded-md text-sm odd:bg-blue-400 font-semibold "
                v-for="(item, index) in options" :key="index"
                @click="sendData(item)">
                <!--          Para dividir la ceda-->
                <span v-for="(field, idx) in optionLabel.split('|')" :key="idx" >
          {{item[field.trim() as keyof typeof item]}}
                    <!--            Espacio entro los campos-->
          <span v-if="idx < optionLabel.split('|').length - 1">
            |
          </span>
        </span>
            </li>
        </ol>
    </div>

</template>
