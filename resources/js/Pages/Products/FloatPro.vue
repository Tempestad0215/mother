<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { successHttp } from '@/Global/Alert';
import { proSupResI } from '@/Interfaces/Product';
import { supplierI } from '@/Interfaces/Supplier';
import InputSelect from '@components/InputSelect.vue';
import SecondaryButton from '@components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, PropType, ref } from 'vue';
import {taxeI} from "@/Interfaces/Global";
import {categoryI} from "@/Interfaces/Categories";


const props = defineProps({
    productEdit: {
        type: Object as PropType<proSupResI>,
    },
    update: {
        type: Boolean
    }
});


const emit = defineEmits(['showSupplier']);


// Datos del formulario
const form = useForm({
    id: 0,
    name: "",
    description: "",
    unit: "",
    category_id:0,
    category_name:"",
    supplier_id:0,
    search:"",
    tax_rate: 0,
    tax_tex: "",
    weigth:"",
    bar_code:"",
    sku:"",
    brand:"",
    dimensions:""


});

// Cons datos de la ventana
const dataSelect = ref<supplierI[]>([]);
const taxes = ref<taxeI[]>([
    {
        value: 0,
        name: 'Ex.'
    },
    {
        value: 0.16,
        name: '16%'
    },
    {
        value: 0.18,
        name: '18%'
    },
]);


const showCategory = ref<boolean>(false);
const dataUnit = ref(["UNIDAD","CAJA","KG","LIBRA","LITRO","ONZA","GALON"]);
const categoryData = ref<categoryI[]>([]);

onMounted(()=>{
    // actualizar los suplidores
    getSupplier();
    // Pasar los datos a editar
    if(props.productEdit?.data)
    {
        form.id = props.productEdit.data.id;
        form.name = props.productEdit.data.name;
        form.description = <string>props.productEdit.data.description;
        form.category_name = props.productEdit.data.category.name;
        form.category_id = props.productEdit.data.category.id;
        form.tax_rate = props.productEdit.data.tax_rate;
        form.unit = props.productEdit.data.unit;
        form.supplier_id = props.productEdit.data.supplier.id;
        form.search = props.productEdit.data.supplier.name;
    }
})



// Funciones
const submit = () => {

    if(props.update)
    {
        form.patch(route('product.update',form.id),{
            onSuccess:()=>{
                successHttp('Datos actualizado correctamente')

            }
        })
    }else{
        // Formulario para guardar los productos
        form.post(route('product.store'),{
            onSuccess:()=>{
                // Datos de la alerta
                successHttp('Datos registrado correctamente')
                form.reset()
            }
        });
    }

}

// conseguir los nuevos suplidores
const getSupplier = () =>{
    axios.get(route('supplier.get.json', {search: form.search}))
        .then((res)=>{
            dataSelect.value = res.data

        }).catch((err)=>{
            console.log('error', err)
        })
}

const getValueSelect = (supplierId:number) =>{
    form.supplier_id = supplierId;
}


const getCategory = () => {
    if(form.category_name.length < 2)
    {
        return false;
    }else{
        axios.get(route('category.get.json',{search: form.category_name}))
            .then((res) =>{
                categoryData.value = res.data;
            }).catch(()=>{

        });
    }

}

const selectCategory = (item:categoryI) => {
    form.category_id = item.id;
    form.category_name = item.name;
    showCategory.value = false;
}

</script>



<template>
    <div  >
        <form
            @submit.prevent="submit" >
            <h3 class="text-2xl font-bold text-center">
                Registro de producto
            </h3>

            <div class="">
                <fieldset class=" grid grid-cols-4 gap-3 field-box">
                    <legend>
                        Informacion
                    </legend>


                    <!-- Nombre -->
                    <div>
                        <InputLabel
                            for="name"
                            value="Nombre *"/>
                        <TextInput
                            class=" w-full"
                            name="name"
                            required
                            v-model="form.name"
                            placeholder="Nombre del producto"
                        />
                        <!-- Error -->
                        <InputError :message="form.errors.name" />
                    </div>


                    <!-- Descricion -->
                    <div class="">
                        <InputLabel
                            for="name"
                            value="Descripción"/>
                        <TextInput
                            class=" w-full"
                            name="name"
                            v-model="form.description"
                            placeholder="Descripcion"
                        />
                        <!-- Error -->
                        <InputError :message="form.errors.description" />
                    </div>

                    <div>
                        <InputLabel for="category" value="Categoria" />
                        <div class="relative">
                            <TextInput
                                @focus="showCategory = true"
                                @input="getCategory"
                                aria-required="true"
                                v-model="form.category_name"
                                aria-autocomplete="none"
                                class=" w-full"
                            />
                            <i
                                class="icon-rotate fa-solid fa-circle-arrow-down"
                                :class="{'rotate-180' : showCategory}"></i>


                            <div
                                class="bg-gray-100 rounded-md absolute w-full border-2 border-gray-800 "
                                v-if="showCategory">
                                <ol>
                                   <li
                                       class="list-data"
                                       v-for="(item,index) in categoryData" :key="index"
                                       @click="selectCategory(item)">
                                       {{item.name}}
                                   </li>
                                </ol>
                            </div>
                        </div>
                        <InputError :message="form.errors.category_id"/>

                    </div>

                    <!-- Proveedor -->
                    <div class="">
                        <InputLabel
                            for="supplier_id"
                            value="Proveedor *"/>

                        <div class=" flex space-x-3">
                            <InputSelect
                                field="company_name"
                                :info="dataSelect"
                                @send-value="getValueSelect"
                                @update-data="getSupplier()"
                                aria-required="true"
                                autocomplete="false"
                                v-model="form.search"
                                class=" w-full" />
                            <SecondaryButton
                                @click="emit('showSupplier')"
                                type="button">
                                Agre.
                            </SecondaryButton>
                        </div>

                        <!-- Error -->
                        <InputError :message="form.errors.search" />
                    </div>
                </fieldset>

                <div class=" grid grid-cols-2 gap-4 mt-3">
                    <fieldset class=" grid grid-cols-2 gap-3 field-box">
                        <legend>
                            Extra
                        </legend>
                        <div>
                            <InputLabel
                                for="sku"
                                value="Cod. Externo"/>
                            <TextInput
                                v-model="form.sku"
                                class="w-full"
                            />
                            <InputError :message="form.errors.sku"/>
                        </div>
                        <div>
                            <InputLabel
                                for="bar_code"
                                value="Codigo de Barra"/>
                            <TextInput
                                v-model="form.bar_code"
                                class="w-full"
                            />
                            <InputError :message="form.errors.bar_code"/>
                        </div>
                    </fieldset>


                    <fieldset class=" grid grid-cols-2 gap-3 field-box">
                        <legend>
                            Datalles
                        </legend>
                        <div>
                            <InputLabel
                                for="tax_rate"
                                value="Impuesto *" />
                            <div class=" relative">
                                <select
                                    class=" w-full border-gray-300 rounded-md "
                                    required
                                    name="tax_rate"
                                    v-model="form.tax_rate">
                                    <option value="">--- Seleccione El Itbis  ---</option>
                                    <option
                                        v-for="item in taxes"
                                        :value="item.value">
                                        {{item.name}}
                                    </option>
                                </select>

                            </div>

                            <InputError :message="form.errors.tax_rate" />
                        </div>
                        <!-- Unidad -->
                        <div class="">
                            <InputLabel
                                for="unit"
                                value="Unidad *"/>
                            <select
                                name="unit"
                                v-model="form.unit"
                                required
                                class=" w-full border-gray-300 rounded-md ">
                                <option selected disabled value="">
                                    --- Seleccione la unidad ---
                                </option>
                                <option v-for="item in dataUnit"
                                    :value="item">
                                    {{item}}
                                </option>
                            </select>
                            <!-- Error -->
                            <InputError :message="form.errors.unit" />
                        </div>
                        <div>
                            <InputLabel
                                for="weight"
                                value="Peso"/>
                            <TextInput
                                v-model="form.weigth"
                                class="w-full"
                                name="waight"/>
                            <InputError :message="form.errors.weigth"/>
                        </div>
                        <div>
                            <InputLabel
                                for="brand"
                                value="Rama"/>
                            <TextInput
                                class="w-full"
                                v-model="form.brand"
                                name="brand"/>
                            <InputError :message="form.errors.brand" />
                        </div>
                        <div class="col-span-full">
                            <InputLabel
                                for="dimension"
                                value="Dimensiones"/>
                            <TextInput
                                class="w-full"
                                v-model="form.dimensions"
                                name="dimension"/>
                            <InputError :message="form.errors.dimensions" />
                        </div>
                    </fieldset>
                </div>


            </div>


            <!-- Botones -->
            <div class="mt-4 text-right">
                <PrimaryButton
                    :disabled="form.processing">
                    {{props.update ? 'Actualizar' : 'Registrar'}}
                </PrimaryButton>
            </div>
        </form>


        <div>
<!--            <FloatShowPro -->
<!--                :products=""/>-->
        </div>
    </div>
</template>
