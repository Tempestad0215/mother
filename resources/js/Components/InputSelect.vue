<script setup lang="ts">
import { supplierI } from '@/Interfaces/Supplier';
import {  PropType, ref } from 'vue';

const props = defineProps({
    modelValue:{
        type: String
    },
    data: {
        type: Array as PropType< supplierI[] >,
        required: false,
    },
    field:{
        type: String as PropType<keyof supplierI> ,
        default: 'name'
    },
    fieldValue:{
        type: String as PropType<keyof supplierI> ,
        default: 'id'
    },
    read:{
        type: Boolean,
        default: false
    },
    holder:{
        type: String,
        default: ' -- Selecciona --'
    }

});

const emit = defineEmits(['update:modelValue', 'sendValue','updateData']);
const showData = ref(false);




// Funciones
const sendData = (e:Event) => {

    const target = e.target as HTMLInputElement;
    emit('update:modelValue', target.value);
}

//
const selectData = (item:supplierI) => {

    // Tomar el input
    const input:HTMLInputElement = document.getElementById('input-select') as HTMLInputElement;

    // Pasar los datos al input
    input.value = <string>item[props.field]

    // Enviar el campo para registrar
    emit('sendValue', item[props.fieldValue]);

}

const update = () => {
    showData.value = true;

    emit('updateData');
}



</script>


<template>
    <div class=" relative">
        <div class=" relative">
            <input
                @focus="update()"
                @blur=" showData = false"
                @input="sendData($event)"
                :readonly="props.read"
                :placeholder="holder"
                :value="props.modelValue"
                id="input-select"
                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                type="text">
            <i
                class=" absolute inset-y-0 right-2 flex items-center fa-regular fa-circle-down duration-300 ease-in"
                :class=" showData ? 'rotate-180 ' : '' "
                ></i>
        </div>

        <Transition>
            <div
                v-if="showData"
                class="  ">
                <ol
                    class=" absolute w-full max-h-[250px] overflow-x-auto  bg-gray-100 border-2 rounded-md" >
                    <li
                        class=" odd:bg-gray-300 px-5 py-1 hover:bg-gray-400 duration-300 ease-in select-none "
                        v-for="(item, index) in props.data" :key="index"
                        @click="selectData(item)" >
                        {{ item[field] }}
                    </li>
                </ol>
            </div>
        </Transition>

    </div>
</template>
