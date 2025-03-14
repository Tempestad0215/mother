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
import {WHbaseI} from "@/Interfaces/Warehouse";
import InputLabel from "@components/InputLabel.vue";
import FloatBox from "@components/FloatBox.vue";
import FRegisterSupplier from "@/Pages/Suppliers/FRegister.vue";
import FRegisterCategory from "@/Pages/Categories/FRegister.vue";
import FRegisterWarehouse from "@/Pages/Setting/WH/FRegister.vue";


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
    warehouse: WHbaseI[],
    nextProduct?: number
}>();


/**
 * Datos de la ventana
 */
const showCategory = ref<boolean>(false);
const showSupplier = ref<boolean>(false);
const showWarehouse = ref<boolean>(false);


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
    tax: 0,
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
        form.sku = propsW.productEdit.sku || "";
        form.unit = propsW.productEdit.unit;
        form.brand = propsW.productEdit.brand || "";
        form.cost = propsW.productEdit.cost;
        form.price = propsW.productEdit.price;
        form.min_price = propsW.productEdit.min_price || 0;
        form.special_price = propsW.productEdit.special_price || 0;
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
    form.tax = (price *  (tax / 100)) /100;
    form.product_no_tax = (price - taxTotal) / 100;

    return getMoney(form.product_no_tax);
});


/**
 *Beneficios del producto
 */
const benefits = computed(()=>{
    let cost:number = form.cost * 100;
    let price:number = form.price * 100;

    // Tomar el beneficios
    form.benefits = Math.round((price - cost) / 100);

    // Devolver los datos
    return getMoney(form.benefits);
});

/**
 * Margen de beneficios
 */
const benefitsMargin = computed(() =>{
    let cost:number = form.cost * 100;
    let price:number = form.price * 100;

    // Calcular el margen de beneficios
    form.benefits_rate = ((price - cost) / cost) * 100 || 0;

    //Devolver el valor de los datos
    return  form.benefits_rate.toFixed(2)  + ' %'
});





/**
 * Funcion para enviar los datos
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


/**
 * calcular el impuesta
 * @param price
 * @param tax
 */
// const tax = (price:number, tax:number) => {
//     return Math.round((price * tax) / (10000 + tax));
// }



</script>



<template>
<!--Formulario-->
    <form
        @submit.prevent="submit" >
<!--Titulo-->
        <h3 class="text-2xl font-bold text-center">
            Registro de producto
        </h3>

        <div v-if="propsW.nextProduct">
            <p>Seguiente ID :
                <span class="px-2 py-1 rounded-md">
                    {{propsW.nextProduct}}
                </span>
            </p>
        </div>

<!--Informacion General-->
        <div class="">
            <fieldset class="field p-2 w-[10rem] block float-right ml-3 text-gray-50">
                <legend>Manejo</legend>
                <div>
                    <input
                        id="has_inventoried"
                        v-model="form.inventoried"
                        name="has_inventoried"
                        type="checkbox">
                    <InputLabel class="inline ml-2" for="has_inventoried" value="Inventariar" />
                </div>
                <div>
                    <input
                        id="has_fraction"
                        name="has_fraction"
                        v-model="form.has_fraction"
                        type="checkbox">
                    <InputLabel class="inline ml-2" for="has_fraction" value="Fraccionar" />
                </div>
                <div>
                    <input
                        v-model="form.status"
                        id="status"
                        type="checkbox">
                    <InputLabel class="inline ml-2" for="status" value="Estado" />
                </div>
                <div>
                    <input
                        v-model="form.has_tax"
                        id="has_tax"
                        type="checkbox">
                    <InputLabel class="inline ml-2" for="has_tax" value="Itbis" />
                </div>
                <div>
                    <input
                        id="has_special"
                        v-model="form.has_special"
                        type="checkbox">
                    <InputLabel class="inline ml-2" for="has_special" value="P. Especial" />
                </div>
                <div>
                    <input
                        v-model="form.has_promotion"
                        id="has_promotion"
                        type="checkbox">
                    <InputLabel class="inline ml-2" for="promotion" value="Promocion" />
                </div>
            </fieldset>


            <fieldset class="field">
                <legend>
                    Informacion
                </legend>

                <!-- Nombre -->
                <div>
                    <InputLabel
                        class="inline ml-2"
                        for="name"
                        value="Nombre" />
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
                        class="inline ml-2"
                        for="description"
                        value="Descripcion" />
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
                    <InputLabel
                        class="inline ml-2"
                        for="category"
                        value="Categoria" />
                    <div>
                        <select
                            v-model="form.category_id"
                            class=" w-[90%] inputGeneral py-1 ">
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
                        <i
                            @click="showCategory = true"
                            class="icon-efect text-cyan-400 text-[1.5rem] ml-3 fa-solid fa-code-branch"></i>
                    </div>

<!--                        Mensaje de error-->
                    <InputError :message="form.errors.category_id"/>

                </div>

                <!-- Proveedor -->
                <div class="">
                    <InputLabel
                        class="inline ml-2"
                        for="supplier"
                        value="Proveedor" />
                    <div>
                        <select
                            v-model="form.supplier_id"
                            class=" w-[90%] inputGeneral py-1 ">
                            <option selected disabled :value="0" >-- Suplidor --</option>
                            <option
                                class="even:bg-blue-200"
                                v-for="(item, index) in propsW.suppliers"
                                :key="index"
                                :value="item.id">
                                {{item.company_name}}
                            </option>
                        </select>
                        <i
                            @click="showSupplier = true"
                            class="icon-efect text-cyan-400 text-[1.5rem] ml-3 fa-solid fa-truck"></i>
                    </div>

                    <!-- Error -->
                    <InputError :message="form.errors.supplier_id" />
                </div>
            </fieldset>

            <div class=" grid grid-cols-2 gap-4 mt-3">
                <fieldset class="field">
                    <legend>
                        Extra
                    </legend>
                    <div>
                        <InputLabel
                            class="inline ml-2"
                            for="sku"
                            value="Codigo Externo" />
                        <TextInput
                            name="sku"
                            v-model="form.sku"
                            class="w-full"
                        />
                        <InputError :message="form.errors.sku"/>
                    </div>
                    <div>
                        <InputLabel
                            class="inline ml-2"
                            for="bar_code"
                            value="Cod. Barra" />
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
                            class="inline ml-2"
                            for="type"
                            value="Tipo" />
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
                        <InputLabel
                            class="inline ml-2"
                            for="warehouse"
                            value="Almacen" />
                        <select
                            v-model="form.warehouse_id"
                            class=" w-[80%] inputGeneral py-1 ">
                            <option
                                class="even:bg-blue-200"
                                v-for="(item, index) in propsW.warehouse"
                                :key="index"
                                :value="item.id">
                                {{item.name}}
                            </option>
                        </select>
                        <i
                            @click="showWarehouse = true"
                            class="ml-2 icon-efect text-[1.5rem] text-cyan-400 fa-solid fa-warehouse"></i>
                        <InputError :message="form.errors.warehouse_id"/>
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
                            class="inline ml-2"
                            for="tax_rate"
                            value="Impuesto" />
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
                        <InputLabel
                            class="inline ml-2"
                            for="unit"
                            value="Unidad" />
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
                            class="inline ml-2"
                            for="weight"
                            value="Peso" />
                        <Money
                            class="inputGeneral w-full"
                            v-bind="moneyConfig"
                            v-model="form.weigth" />
                        <InputError :message="form.errors.weigth"/>
                    </div>
                    <div>
                        <InputLabel
                            class="inline ml-2"
                            for="brand"
                            value="Marca" />
                        <TextInput
                            class="w-full"
                            placeholder="Yamaha"
                            v-model="form.brand"

                            name="brand"/>
                        <InputError :message="form.errors.brand" />
                    </div>
                    <div
                        v-if="form.type === 'producto'"
                        class="">
                        <InputLabel
                            class="inline ml-2"
                            for="dimension"
                            value="Dimensiones" />
                        <label for="dimension">Dimensiones</label>
                        <TextInput
                            class="w-full"
                            v-model="form.dimensions"
                            placeholder="00 x 00 aa || 00 x 00 x 00 aa "
                            v-mask="['## x ## aa', '## x ## x ## aa']"
                            name="dimension"/>
                        <InputError :message="form.errors.dimensions" />
                    </div>
                </fieldset>

            </div>
            <fieldset class="field grid grid-cols-4 gap-3">
                <legend>Datos de Ventas</legend>
                <div>
                    <InputLabel
                        class="inline ml-2"
                        for="sale_cost"
                        value="Costo" />
                    <Money
                        class="inputGeneral w-full"
                        v-bind="moneyConfig"
                        v-model.number="form.cost"/>
                    <input-error :message="form.errors?.cost" />
                </div>
                <!--                        Informacion de venta-->
                <div>
                    <InputLabel
                        class="inline ml-2"
                        for="sale_price"
                        value="Precio" />
                    <Money
                        class="inputGeneral w-full"
                        v-bind="moneyConfig"
                        v-model.number="form.price"/>
                    <input-error :message="form.errors?.price" />
                </div>
                <div>
                    <InputLabel
                        class="inline ml-2"
                        for="sale_cost"
                        value="Pre. Minimo" />
                    <Money
                        class="inputGeneral w-full"
                        v-bind="moneyConfig"
                        v-model.number="form.min_price"/>
                    <input-error :message="form.errors?.min_price" />
                </div>
                <!--                        Informacion de venta-->
                <div>
                    <InputLabel
                        class="inline ml-2"
                        for="sale_price"
                        value="Pre. Especial" />
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
                    <span class="inline-block px-3 rounded-md ml-3">{{priceNoTax}}</span>
                </p>
                <p>
                    <strong>Beneficio</strong>
                    <span class="inline-block px-3 rounded-md ml-3" >{{benefits}}</span>
                </p>
                <p>
                    <strong>Beneficios Margen </strong>
                    <span class="inline-block px-3 rounded-md ml-3">{{benefitsMargin}}</span>
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

<!--    Mostrar la categorias-->
    <FloatBox
        v-if="showCategory"
        @close="showCategory = false"
        header="MAnejo de categorias">
        <FRegisterCategory
            class="w-[50rem]"
            />
    </FloatBox>

<!--    Mostar la ventana de suplidores-->
    <FloatBox
        v-if="showSupplier"
        @close="showSupplier = false"
        header="Manejo de Proveedores">
        <FRegisterSupplier
            />
    </FloatBox>

<!--    Mostra la ventana para agrear almacenes-->
    <FloatBox
        header="Manejos Almancenes">
        <FRegisterWarehouse/>
    </FloatBox>
</template>
