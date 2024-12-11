<script setup lang="ts">
import { onMounted, onUnmounted, ref, type Ref } from 'vue';

/**
 * Datos para la ventana
 */
const propsW = defineProps<{
    defaultValue?: string;
    labelValue: string;
    idValue?: string;
    isReadOnly?: boolean;
    optionLabel: string; // Se usa solo si las opciones son objetos
    optionValue: string; // Se usa solo si las opciones son objetos
    options: Record<string, unknown>[] | (string | number)[]; // Permitir ambos tipos
    placeholder?: string;
}>();

/*
Datos para enviar
 */
const model = defineModel();
const modelShow = defineModel('show');

/*
Datos de la ventana
 */
const showData: Ref<boolean> = ref(false);
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

// Enviar datos seleccionados
const sendData = (item: Record<string, unknown> | string | number) => {
    if (typeof item === 'object') {
        // Si es un objeto, verificar y extraer según las propiedades definidas
        if (propsW.optionValue && item.hasOwnProperty(propsW.optionValue)) {
            model.value = item[propsW.optionValue];
            modelShow.value = item[propsW.optionLabel];
        }
    } else {
        // Si es un string o número, asignarlo directamente
        model.value = item;
        modelShow.value = String(item);
    }

    // Cerrar la ventana
    showData.value = false;
};

// Cierra el menú si el clic ocurre fuera del componente
const handleClickOutside = (event: MouseEvent) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
        showData.value = false;
    }
};
</script>

<template>
    <div ref="dropdownRef" class="relative w-fit">
        <input
            @click="showData = !showData"
            class="border border-orange-500 px-2 pr-6 min-w-full rounded-md focus:ring-0 focus:border-blue-800 focus:outline-none h-[2rem]"
            :class="{'read-only': isReadOnly}"
            type="text"
            :placeholder="propsW.placeholder"
            v-model="modelShow"
            :readonly="isReadOnly"
        >
        <i
            class="absolute flex p-1 right-1 items-center inset-y-0 transition duration-150 pointer-events-none fa-solid fa-arrow-down"
            :class="{'rotate-180': showData}"
        ></i>

        <ol
            class="opacity-0 -z-50 bg-blue-300 absolute w-full rounded-md transition duration-100 ease-linear border border-blue-800"
            :class="{'opacity-100 z-10': showData}"
        >
            <li
                class="px-3 border-b border-blue-600 rounded-md text-sm odd:bg-blue-400 font-semibold"
                v-for="(item, index) in propsW.options"
                :key="index"
                @click="sendData(item)"
            >
                <!-- Diferenciar entre arrays de objetos y arrays simples -->
                <span v-if="typeof item === 'object'">
                    <span v-for="(field, idx) in propsW.optionLabel.split('|')" :key="idx">
                        {{ item[field.trim()] }}
                        <span v-if="idx < propsW.optionLabel.split('|').length - 1"> | </span>
                    </span>
                </span>
                <span v-else>
                    {{ item }}
                </span>
            </li>
        </ol>
    </div>
</template>
