<script setup lang="ts">
import InputLabel from './InputLabel.vue';


const props = defineProps({
    modelValue:{
        type: String
    },
    holder:{
        type: String,
        default: "Place holder"
    }
})
const emit = defineEmits(['update:modelValue'])



const sendData = (e:Event) => {
    const target = e.target as HTMLInputElement;

    emit('update:modelValue',target.value);
}

</script>


<template>
    <div>
        <InputLabel
            for="search"
            value="Buscar" />
        <input
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            :value="props.modelValue"
            @input="sendData($event)"
            :placeholder="props.holder"
            type="text" />
    </div>

</template>
