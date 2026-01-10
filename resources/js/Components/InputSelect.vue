<script setup lang="ts">
import { supplierI } from '@/Interfaces/SupplierInterface';
import {  PropType, ref } from 'vue';
import {productFullI } from "@/Interfaces/ProductInterface";
import {TaxI} from "@/Interfaces/GlobalInterface";


const props = defineProps({
    modelValue:{
        type:[ String , Number]
    },
    info: {
        type: Array as PropType<Array< supplierI | productFullI | TaxI>>,
        required: false,
    },
    field:{
        type: String as PropType<keyof supplierI | keyof productFullI | keyof TaxI>,
        default: 'name' as keyof supplierI | keyof productFullI | keyof TaxI,
    },
    fieldValue:{
        type: String as PropType<keyof supplierI | keyof productFullI | keyof TaxI>,
        default: 'id' as keyof supplierI | keyof productFullI | keyof TaxI,
    },
    read:{
        type: Boolean,
        default: false
    },
    holder:{
        type: String,
        default: ' -- Selection --'
    }

});

const emit = defineEmits(['update:modelValue', 'sendValue','updateData','getData']);
const showData = ref(false);



// Funciones
const isSupplier = (item:supplierI | productFullI | TaxI): item is supplierI => {
    return isDefined((item as supplierI).company_name)
}

const isProduct = (item:supplierI | productFullI | TaxI): item is productFullI => {
    return isDefined((item as productFullI).name)
}

const isTaxe = (item:supplierI | productFullI | TaxI):item is TaxI => {
    return  isDefined((item as TaxI).name)
}

// Para guardar el tipo de datos
const isDefined = <T> (val: T | undefined):val is T =>
{
    return val !== undefined;
}




const sendData = (e:Event) => {

    const target = e.target as HTMLInputElement;
    emit('update:modelValue', target.value);
    emit('getData');
}

//
const selectData = (item:supplierI | productFullI | TaxI) => {

    // Tomar el input
    const input:HTMLInputElement = document.getElementById('input-select') as HTMLInputElement;

    // Pasar los datos al input
    if (isSupplier(item)) {
        input.value = item[props.field as keyof supplierI] as string;
        emit('sendValue', item[props.fieldValue as keyof supplierI]);
    } else if (isProduct(item)) {
        input.value = item[props.field as keyof productFullI] as string;
        emit('sendValue', item[props.fieldValue as keyof productFullI]);
    }else if(isTaxe(item))
    {
        input.value = item[props.field as keyof TaxI] as string;
        emit('sendValue', item[props.fieldValue as keyof TaxI]);
    }

}

const showValue = (item: supplierI | productFullI | TaxI) =>{
    if(isProduct(item)){
        return item[props.field as keyof productFullI] as string;
    }else if(isSupplier(item))
    {
        return  item[props.field as keyof supplierI] as string;
    }else if(isTaxe(item))
    {
        return item[props.field as keyof TaxI] as string;
    }


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
                autocomplete="false"
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
                        v-for="(item, index) in props.info" :key="index"
                        @click="selectData(item)" >
                        {{ showValue(item) }}
                    </li>
                </ol>
            </div>
        </Transition>

    </div>
</template>
