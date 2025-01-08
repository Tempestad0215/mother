<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { successHttp } from '@/Global/Alert';
import {productBaseI} from '@/Interfaces/Product';
import { supplierI } from '@/Interfaces/Supplier';
import {useForm, usePage} from '@inertiajs/vue3';
import {onMounted, Ref, ref} from 'vue';
import {categoryBaseI} from "@/Interfaces/Categories";
import {taxI} from "@/Interfaces/Global";
import {Money} from "v-money3";
import {moneyConfig} from "@/Global/Helpers";
import ToggleButton from "@components/ToggleButton.vue";


/**
 * Info general
 */
const {props} = usePage();

/**
 * Propiedades de la ventana
 */
const propsW = defineProps<{
    productEdit? : productBaseI,
    update? : boolean,
    categories: categoryBaseI[],
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
    price: 0,
    type: "producto",
    category_id: "",
    supplier_id: "",
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
const taxes:Ref<taxI[]>  = ref(props.setting.tax);
const dataUnit:Ref<string[]> = ref(props.setting.unit);
const typeOptions:Ref<any> = ref([
    {
        name:'Producto',
        value:'producto',
    },
    {
        name:'Servicio',
        value:'servicio'
    }]);


/**
 * Al momento de cargar
 */
onMounted(()=>{

    // Pasar los datos a editar
    if(propsW.productEdit)
    {
        form.id = propsW.productEdit.id;
        form.name = propsW.productEdit.name;
        form.type = propsW.productEdit.type;
        form.description = propsW.productEdit.description ? propsW.productEdit.description : "";
        form.bar_code = propsW.productEdit.bar_code ? propsW.productEdit.bar_code : "";
        form.category_id = propsW.productEdit.category_id;
        form.supplier_id = propsW.productEdit.supplier_id;
        form.tax_rate = propsW.productEdit.tax_rate;
        form.unit = propsW.productEdit.unit;
    }

});


/**
 * Funciones
 */
const submit = () => {

    if(propsW.update)
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

<!--Informacion General-->
            <div class=" clear-both">
                <fieldset class="field">
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
                            v-model="form.category_id"
                            class=" w-full inputGeneral py-1 ">
                            <option
                                selected
                                disabled
                                value="" >
                                -- Categoria --
                            </option>
                            <option
                                class="even:bg-blue-200"
                                v-for="(item, index) in propsW.categories"
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
                        <select
                            v-model="form.supplier_id"
                            class=" w-full inputGeneral py-1 ">
                            <option selected disabled value="" >-- Suplidor --</option>
                            <option
                                class="even:bg-blue-200"
                                v-for="(item, index) in propsW.suppliers"
                                :key="index"
                                :value="item.id">
                                {{item.company_name}}
                            </option>
                        </select>
                        <!-- Error -->
                        <InputError :message="form.errors.search" />
                    </div>
                </fieldset>

                <div class=" grid grid-cols-2 gap-4 mt-3">
                    <fieldset class="field">
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
                        <div class="">
                            <InputLabel
                                class=" mb-2"
                                for="type" value="Tipo" />
                            <select
                                v-model="form.type"
                                class=" w-full inputGeneral py-1 ">
                                <option
                                    class="even:bg-blue-200"
                                    v-for="(item, index) in typeOptions"
                                    :key="index"
                                    :value="item.value">
                                    {{item.name}}
                                </option>
                            </select>
                            <InputError :message="form.errors.type"/>
                        </div>

                        <div class="">
                            <ToggleButton
                                label="Inventario"
                                v-model="form.inventoried"
                                on-label="SI"
                                off-label="NO"/>
                        </div>
                    </fieldset>

<!--Detalle del producto-->
                    <fieldset class="field">
                        <legend>
                            Datalles
                        </legend>
<!--                        Unidades-->
                        <div>
                            <InputLabel
                                for="tax_rate"
                                value="Impuesto *" />
                            <select
                                v-model="form.tax_rate"
                                class=" w-full inputGeneral py-1 ">
                                <option
                                    class="even:bg-blue-200"
                                    v-for="(item, index) in taxes"
                                    :key="index"
                                    :value="item.amount">
                                    {{item.name}}
                                </option>
                            </select>
                            <InputError :message="form.errors.tax_rate" />
                        </div>
<!--                        Informacion de venta-->
                        <div>
                            <InputLabel for="sale_price" value="Precio de Venta"/>
                            <Money
                                class="inputGeneral w-full"
                                v-bind="moneyConfig"
                                v-model="form.price"/>
                        </div>

                        <!-- Unidad -->
                        <div
                            v-if="form.type === 'producto'"
                            class="">
                            <InputLabel
                                for="unit"
                                value="Unidadades *"/>
                            <select
                                v-model="form.unit"
                                class=" w-full inputGeneral py-1 ">
                                <option selected disabled value="" >-- UNIDAD --</option>
                                <option
                                    class="even:bg-blue-200"
                                    v-for="(item, index) in dataUnit"
                                    :key="index"
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
                            <Money
                                class="inputGeneral w-full"
                                v-bind="moneyConfig"
                                v-model="form.weigth" />
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
                            class="">
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
                    {{propsW.update ? 'Actualizar' : 'Registrar'}}
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
