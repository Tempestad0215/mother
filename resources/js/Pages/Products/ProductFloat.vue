<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { successHttp } from '@/Global/Alert';
import {productBaseI} from '@/Interfaces/Product';
import { supplierI } from '@/Interfaces/Supplier';
import {useForm, usePage} from '@inertiajs/vue3';
import {computed, onMounted, Ref, ref} from 'vue';
import {categoryBaseI} from "@/Interfaces/Categories";
import {taxI} from "@/Interfaces/Global";
import {Money} from "v-money3";
import {getMoney, moneyConfig} from "@/Global/Helpers";
import {WHbaseI} from "@/Interfaces/WH";


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
    suppliers: supplierI[],
    warehouse: WHbaseI[]
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
    cost: 0,
    min_price: 0,
    special_price: 0,
    product_no_tax: 0,
    benefits: 0,
    benefits_rate: 0,
    type: "producto",
    category_id: 0,
    supplier_id: 0,
    warehouse_id: 0,
    search:"",
    tax_rate: 0,
    tax_tex: "",
    weigth:"",
    bar_code:"",
    sku:"",
    brand:"",
    dimensions:"",
    inventoried: true,
    has_fraction: true,
    status: true,
    has_tax: true,
    has_special: false,
    has_promotion: false,
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


    //Elegir el primer si existe
    if (propsW.warehouse.length > 0)
    {
        form.warehouse_id = propsW.warehouse[0].id;
    }

});


/*
Propiedades computada
 */
/**
 * Precio sin impuesto
 */
const priceNoTax = computed(()=>{
    let price:number = form.price * 100;
    let tax:number = form.tax_rate;
    let taxTotal:number = (price * tax) / 100;

    console.log(taxTotal);
    form.product_no_tax = price - taxTotal;
    return getMoney((form.product_no_tax / 100));
});


/**
 *Beneficios del producto
 */
const benefits = computed(()=>{
    let cost:number = form.cost * 100;
    let price:number = form.price * 100;

    form.benefits = Math.round((price - cost) / 100);

    return getMoney(form.benefits);
});

/**
 * Margen de beneficios
 */
const benefitsMargin = computed(() =>{
    let benefits:number = form.benefits * 100;
    let cost:number = form.price * 100;

    form.benefits_rate = (benefits / cost) * 100 || 0;

    return  form.benefits_rate.toFixed(2)  + ' %'
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


const tax = (price:number, tax:number) => {
    return Math.round((price * tax) / (10000 + tax));
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
            <div class="">
                <fieldset class="field p-2 w-[10rem] block float-right ml-3">
                    <legend>Manejo</legend>
                    <div>
                        <input
                            id="has_inventoried"
                            v-model="form.inventoried"
                            name="has_inventoried"
                            type="checkbox">
                        <label
                            for="has_inventoried">
                            Inventariar
                        </label>
                    </div>
                    <div>
                        <input
                            id="has_fraction"
                            name="has_fraction"
                            v-model="form.has_fraction"
                            type="checkbox">
                        <label
                            for="has_fraction">
                            Fraccionar
                        </label>
                    </div>
                    <div>
                        <input
                            v-model="form.status"
                            id="status"
                            type="checkbox">
                        <label
                            for="status">
                            Estado
                        </label>
                    </div>
                    <div>
                        <input
                            v-model="form.has_tax"
                            id="has_tax"
                            type="checkbox">
                        <label
                            for="has_tax">
                            Itbis
                        </label>
                    </div>
                    <div>
                        <input
                            id="has_special"
                            v-model="form.has_special"
                            type="checkbox">
                        <label
                            for="has_special">
                            P. Especial
                        </label>
                    </div>
                    <div>
                        <input
                            v-model="form.has_promotion"
                            id="has_promotion"
                            type="checkbox">
                        <label for="promotion">
                            Promocion
                        </label>
                    </div>
                </fieldset>


                <fieldset class="field">
                    <legend>
                        Informacion
                    </legend>

                    <!-- Nombre -->
                    <div>
                        <label for="name">
                            Nombre
                        </label>
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
                        <label for="description">
                            Descripcion
                        </label>
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
                        <label for="category">Categoria</label>
                        <select
                            v-model="form.category_id"
                            class=" w-full inputGeneral py-1 ">
                            <option
                                selected
                                disabled
                                :value="0" >
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
                        <label for="supplier">Proveedor</label>
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
                            <label for="sku">
                                Cod. Externo
                            </label>
                            <TextInput
                                name="sku"
                                v-model="form.sku"
                                class="w-full"
                            />
                            <InputError :message="form.errors.sku"/>
                        </div>
                        <div>
                            <label for="bar_code">Cod. Barra</label>
                            <TextInput
                                name="bar_code"
                                v-model="form.bar_code"
                                class="w-full"
                            />
                            <InputError :message="form.errors.bar_code"/>
                        </div>


<!--Opciones de producto, si sera producto o servicio-->
                        <div class="">
                            <label
                                for="type">
                                Tipo
                            </label>
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
                            <label
                                for="warehouse">
                                Almacen
                            </label>
                            <select
                                v-model="form.warehouse_id"
                                class=" w-full inputGeneral py-1 ">
                                <option
                                    class="even:bg-blue-200"
                                    v-for="(item, index) in propsW.warehouse"
                                    :key="index"
                                    :value="item.id">
                                    {{item.name}}
                                </option>
                            </select>
                            <InputError :message="form.errors.type"/>
                        </div>


                    </fieldset>

<!--Detalle del producto-->
                    <fieldset class="field">
                        <legend>
                            Datalles
                        </legend>
<!--                        Unidades-->
                        <div>
                            <label for="tax_rate">Impuesto</label>
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


                        <!-- Unidad -->
                        <div
                            v-if="form.type === 'producto'"
                            class="">
                            <label for="unit">Unidad</label>
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
                            <label for="weight">Peso</label>
                            <Money
                                class="inputGeneral w-full"
                                v-bind="moneyConfig"
                                v-model="form.weigth" />
                            <InputError :message="form.errors.weigth"/>
                        </div>
                        <div>
                            <label for="brand">Marca</label>
                            <TextInput
                                class="w-full"
                                v-model="form.brand"
                                name="brand"/>
                            <InputError :message="form.errors.brand" />
                        </div>
                        <div
                            v-if="form.type === 'producto'"
                            class="">
                            <label for="dimension">Dimensiones</label>
                            <TextInput
                                class="w-full"
                                v-model="form.dimensions"
                                name="dimension"/>
                            <InputError :message="form.errors.dimensions" />
                        </div>
                    </fieldset>

                </div>
                <fieldset class="field grid grid-cols-4 gap-3">
                    <legend>Datos de Ventas</legend>
                    <div>
                        <label for="sale_cost">Costo</label>
                        <Money
                            class="inputGeneral w-full"
                            v-bind="moneyConfig"
                            v-model.number="form.cost"/>
                        <input-error :message="form.errors?.cost" />
                    </div>
                    <!--                        Informacion de venta-->
                    <div>
                        <label for="sale_price">Precio</label>
                        <Money
                            class="inputGeneral w-full"
                            v-bind="moneyConfig"
                            v-model.number="form.price"/>
                        <input-error :message="form.errors?.price" />
                    </div>
                    <div>
                        <label for="sale_cost">Pre. Minimo</label>
                        <Money
                            class="inputGeneral w-full"
                            v-bind="moneyConfig"
                            v-model.number="form.min_price"/>
                        <input-error :message="form.errors?.min_price" />
                    </div>
                    <!--                        Informacion de venta-->
                    <div>
                        <label for="sale_price">Pre. Especial</label>
                        <Money
                            class="inputGeneral w-full"
                            v-bind="moneyConfig"
                            v-model.number="form.special_price"/>
                        <input-error :message="form.errors?.special_price" />
                    </div>
                </fieldset>

                <fieldset disabled class="field grid grid-cols-3 gap-3" >
                    <legend>Info Ventas</legend>
                    <p>
                        <strong>Precio - Itbis</strong>
                        <span class="inline-block px-3 bg-white rounded-md ml-3">{{priceNoTax}}</span>
                    </p>
                    <p>
                        <strong>Beneficio</strong>
                        <span class="inline-block px-3 bg-white rounded-md ml-3" >{{benefits}}</span>
                    </p>
                    <p>
                        <strong>Beneficios Margen </strong>
                        <span class="inline-block px-3 bg-white rounded-md ml-3">{{benefitsMargin}}</span>
                    </p>
                </fieldset>


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
