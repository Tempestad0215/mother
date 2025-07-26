<script setup lang="ts">
import {moneyConfig} from "@/Global/Helpers";
import {Money} from "v-money3";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import {taxI} from "@/Interfaces/GlobalInterface";


const propsW = defineProps<{
    dataUnit: string[],
    isProduct: boolean,
    taxes: taxI[],
}>()

const taxRate = defineModel<number>('taxRate', {
    default: 0,
})
const unit = defineModel<string>('unit')
const weigh = defineModel<string | number>('weigh', {
    default: 0
})
const brand = defineModel<string>('brand')
const dimension = defineModel<string>('dimension')


</script>

<template>
    <fieldset class="field">
        <legend>
            Datalles
        </legend>
        <!--                        Unidades-->
        <div>
            <InputLabel
                class="inline ml-2"
                for="tax_rate"
                value="Impuesto" />
            <select
                v-model="taxRate"
                class=" w-full inputGeneral py-1 ">
                <option
                    class="even:bg-blue-200"
                    v-for="(item, index) in taxes"
                    :key="index"
                    :value="item.amount">
                    {{item.name}}
                </option>
            </select>
        </div>


        <!-- Unidad -->
        <div
            v-if="propsW.isProduct"
            class="">
            <InputLabel
                class="inline ml-2"
                for="unit"
                value="Unidad" />
            <select
                v-model="unit"
                class=" w-full inputGeneral py-1 ">
                <option selected disabled value="" >-- UNIDAD --</option>
                <option
                    class="even:bg-blue-200"
                    v-for="(item, index) in propsW.dataUnit"
                    :key="index"
                    :value="item">
                    {{item}}
                </option>
            </select>
        </div>
        <div v-if="propsW.isProduct">
            <InputLabel
                class="inline ml-2"
                for="weight"
                value="Peso" />
            <Money
                class="inputGeneral w-full"
                v-bind="moneyConfig"
                v-model="weigh" />
        </div>
        <div>
            <InputLabel
                class="inline ml-2"
                for="brand"
                value="Marca" />
            <TextInput
                class="w-full"
                placeholder="Yamaha"
                v-model="brand"
                name="brand"/>
        </div>
        <div
            v-if="isProduct"
            class="">
            <InputLabel
                class="inline ml-2"
                for="dimension"
                value="Dimensiones" />
            <label for="dimension">Dimensiones</label>
            <TextInput
                class="w-full"
                v-model="dimension"
                placeholder="00 x 00 aa || 00 x 00 x 00 aa "
                v-mask="['## x ## aa', '## x ## x ## aa']"
                name="dimension"/>
        </div>
    </fieldset>
</template>
