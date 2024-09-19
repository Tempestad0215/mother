<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { successHttp } from '@/Global/Alert';
import {productSupplierI} from '@/Interfaces/Product';
import { supplierI } from '@/Interfaces/Supplier';
import SecondaryButton from '@components/SecondaryButton.vue';
import {useForm, usePage} from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import {pageI, taxeI} from "@/Interfaces/Global";
import {categoryI} from "@/Interfaces/Categories";


/**
 * Info general
 */
const page:pageI = usePage();

/**
 * Propiedades de la ventana
 */
const props = defineProps<{
    productEdit? : productSupplierI,
    update? : boolean,
    categories: categoryI[],
    suppliers: supplierI[]
}>();


/**
 * Emitir eventos
 */
const emit = defineEmits(['showSupplier']);


/**
 * Datos del formulario
 */
const form = useForm({
    id: 0,
    name: "",
    description: "",
    unit: "",
    type: "producto",
    category_id:0,
    supplier_id:0,
    inventoried: true,
    search:"",
    tax_rate: 0,
    tax_tex: "",
    weigth:"",
    bar_code:"",
    sku:"",
    brand:"",
    dimensions:""


});

/**
 *Datos de la ventana
 */
const taxes = ref<taxeI[]>(page.props.appSetting.tax);
const dataUnit = ref(page.props.appSetting.unit);


/**
 * Al momento de cargar
 */
onMounted(()=>{

    // Pasar los datos a editar
    if(props.productEdit)
    {
        form.id = props.productEdit.id;
        form.name = props.productEdit.name;
        form.type = props.productEdit.type;
        form.description = props.productEdit.description;
        form.bar_code = props.productEdit.bar_code;
        form.category_id = props.productEdit.category_id;
        form.supplier_id = props.productEdit.supplier_id;
        form.tax_rate = props.productEdit.tax_rate;
        form.unit = props.productEdit.unit;
    }
});


/**
 * Funciones
 */
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




</script>



<template>
<!--Formulario-->
    <div  >
        <form
            @submit.prevent="submit" >

<!--Titulo-->
            <h3 class="text-2xl font-bold text-center">
                Registro de producto
            </h3>

            <div class="flex flex-col float-right text-center">
                <InputLabel for="inventoried" value="Inventariar" />
                <div class="flex">
                    <div class="">
                        <input
                            type="radio"
                            name="yes_inventoried"
                            v-model="form.inventoried"
                            :value="true"
                            id="no_inventoried">
                        <InputLabel
                            class="inline ml-2"
                            for="yes_inventoried" value="SI" />
                    </div>
                    <div class="ml-5">
                        <input
                            type="radio"
                            :value="false"
                            v-model="form.inventoried"
                            name="no_inventoried"
                            id="no_inventoried">
                        <InputLabel
                            class="inline ml-2"
                            for="yes_inventoried" value="No" />
                    </div>
                </div>


            </div>


<!--Informacion General-->
            <div class=" clear-both">
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
                            autocomplete="false"
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
                        <select
                            name="category"
                            id="category"
                            class="rounded-md border-gray-300 w-full"
                            v-model="form.category_id">
                            <option disabled :value="0">
                                --- Seleccione ---
                            </option>
                            <option
                                v-for="(item, index) in props.categories"
                                :key="index"
                                :value="item.id">
                                {{item.name}}
                            </option>
                        </select>

<!--                        Mensaje de error-->
                        <InputError :message="form.errors.category_id"/>

                    </div>

                    <!-- Proveedor -->
                    <div class="">
                        <InputLabel
                            for="supplier_id"
                            value="Proveedor *"/>

                        <div class="flex items-center ">
                            <select
                                v-model="form.supplier_id"
                                name="supplier_id"
                                class="w-full rounded-md border-gray-300"
                                id="supplier_id">
                                <option disabled :value="0">
                                    --- Seleccione ---
                                </option>
                                <option
                                    v-for="(item, index) in props.suppliers" :key="index"
                                    :value="item.id">
                                    {{item.company_name}}
                                </option>
                            </select>
                            <SecondaryButton
                                class=" py-3 ml-1"
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
                                name="sku"
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
                                name="bar_code"
                                v-model="form.bar_code"
                                class="w-full"
                            />
                            <InputError :message="form.errors.bar_code"/>
                        </div>


<!--Opciones de producto, si sera producto o servicio-->
                        <div class=" flex flex-col">
                            <InputLabel
                                class=" mb-2"
                                for="type" value="Tipo" />
                            <div class="flex">
                                <div>
                                    <input
                                        class="peer hidden"
                                        type="radio"
                                        v-model="form.type"
                                        value="producto"
                                        name="cli_cash"
                                        id="cli_cash">
                                    <label
                                        class=" border-2 px-2 py-1 rounded-md border-gray-400 peer-checked:bg-gray-800 peer-checked:text-white duration-300 "
                                        for="cli_cash">
                                        Producto
                                    </label>

                                </div>
                                <div class="ml-5">
                                    <input
                                        class="peer hidden"
                                        v-model="form.type"
                                        value="servicio"
                                        type="radio"
                                        name="cli_credit"
                                        id="cli_credit">
                                    <label
                                        class=" border-2 px-2 py-1 rounded-md border-gray-400 peer-checked:bg-gray-800 peer-checked:text-white duration-300 "
                                        for="cli_credit">
                                        Servicio
                                    </label>
                                </div>
                                <InputError :message="form.errors.type"/>
                            </div>
                        </div>


                    </fieldset>

<!--Detalle del producto-->
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
                                    <option value="" selected>--- Seleccione El Itbis  ---</option>
                                    <option
                                        v-for="item in taxes"
                                        :value="item.amount">
                                        {{item.name }} | {{item.amount}}
                                    </option>
                                </select>

                            </div>

                            <InputError :message="form.errors.tax_rate" />
                        </div>
                        <!-- Unidad -->
                        <div
                            v-if="form.type === 'producto'"
                            class="">
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
                        <div v-if="form.type === 'producto'">
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
                        <div
                            v-if="form.type === 'producto'"
                            class="col-span-full">
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
