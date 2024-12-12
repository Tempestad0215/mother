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
import SelectOption from "@components/SelectOption.vue";
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
    uuid: "",
    name: "",
    description: "",
    unit: "",
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
        form.uuid = propsW.productEdit.uuid;
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
        form.patch(route('product.update',form.uuid),{
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
                <ToggleButton
                    label="Inventario"
                    v-model="form.inventoried"
                    on-label="SI"
                    off-label="NO"/>
<!--                <ToggleButton-->
<!--                    v-model="form.inventoried"-->
<!--                    onLabel="SI"-->
<!--                    offLabel="NO" />-->
            </div>


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
                        <SelectOption
                            class="w-full"
                            placeholder="--Categoria--"
                            :is-read-only="true"
                            option-label="name"
                            v-model="form.category_id"
                            option-value="uuid"
                            :options="propsW.categories.map(category => ({...category}))"/>

<!--                        Mensaje de error-->
                        <InputError :message="form.errors.category_id"/>

                    </div>

                    <!-- Proveedor -->
                    <div class="">
                        <InputLabel
                            for="supplier_id"
                            value="Proveedor *"/>
                        <SelectOption
                            class="w-full"
                            placeholder="--Categoria--"
                            :is-read-only="true"
                            v-model="form.supplier_id"
                            option-label="company_name"
                            option-value="uuid"
                            :options="propsW.suppliers.map(supplier => ({...supplier}))"/>

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
                        <div class=" flex flex-col">
                            <InputLabel
                                class=" mb-2"
                                for="type" value="Tipo" />
                            <SelectOption
                                placeholder="--Tipo Producto--"
                                option-label="name"
                                :is-read-only="true"
                                v-model="form.type"
                                option-value="value"
                                :options="typeOptions"/>

                            <InputError :message="form.errors.type"/>
                        </div>


                    </fieldset>

<!--Detalle del producto-->
                    <fieldset class="field">
                        <legend>
                            Datalles
                        </legend>
                        <div>
                            <InputLabel
                                for="tax_rate"
                                value="Impuesto *" />
                            <SelectOption
                                class="w-full"
                                placeholder="--ITBIS--"
                                option-label="name"
                                :is-read-only="true"
                                option-value="amount"
                                v-model="form.tax_rate"
                                :options="taxes.map(tax => ({...tax}))"/>

                            <InputError :message="form.errors.tax_rate" />
                        </div>
                        <!-- Unidad -->
                        <div
                            v-if="form.type === 'producto'"
                            class="">
                            <InputLabel
                                for="unit"
                                value="Unidadades *"/>
                            <SelectOption
                                class="w-full"
                                placeholder="--Tipo Producto--"
                                :is-read-only="true"
                                option-label="name"
                                option-value="amount"
                                v-model="form.unit"
                                :options="dataUnit"/>

                            <!-- Error -->
                            <InputError :message="form.errors.unit" />
                        </div>
                        <div v-if="form.type === 'producto'">
                            <InputLabel
                                for="weight"
                                value="Peso"/>
                            <Money
                                class="inputGeneral"
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
                    {{propsW.update ? 'Actualizar' : 'Registrar'}}
                </PrimaryButton>
            </div>
        </form>


        <div>
<!--            <FloatShowPro -->
<!--                :products=""/>-->
        </div>
    </div>
</template>
