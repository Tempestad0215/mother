<script setup lang="ts">
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {successHttp} from '@/Global/Alert';
import {productBaseI, ProductOptionsI} from '@/Interfaces/ProductInterface';
import {supplierI} from '@/Interfaces/SupplierInterface';
import {useForm, usePage} from '@inertiajs/vue3';
import {computed, onMounted, Ref, ref} from 'vue';
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {taxI} from "@/Interfaces/GlobalInterface";
import {Money} from "v-money3";
import {getMoney, moneyConfig} from "@/Global/Helpers";
import {warehouseBaseI} from "@/Interfaces/WarehouseInterface";
import InputLabel from "@components/InputLabel.vue";
import FloatBox from "@components/FloatBox.vue";
import FRegisterSupplier from "@/Pages/Suppliers/FRegister.vue";
import FRegisterCategory from "@/Pages/Categories/FRegister.vue";
import axios from "axios";
import ProductExtra from "@/Pages/Products/ProductExtra.vue";
import ProductDetail from "@/Pages/Products/ProductDetail.vue";
import ProductGeneral from "@/Pages/Products/ProductGeneral.vue";
import Swal from "sweetalert2";


/**
 * Info general
 */
const {props} = usePage();

/**
 * Propiedades de la ventana
 */
const propsW = defineProps<{
    productEdit?: productBaseI,
    update?: boolean,
    categories: categoryBaseI[],
    suppliers: supplierI[],
    warehouse: warehouseBaseI[],
    nextProduct?: number
}>();


/**
 * Datos de la ventana
 */
const showCategory = ref<boolean>(false);
const showSupplier = ref<boolean>(false);
const checkProduct = ref<productBaseI[] | null>(null);


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
    search: "",
    tax: 0,
    tax_rate: 0,
    tax_tex: "",
    weight: "",
    bar_code: "",
    sku: "",
    brand: "",
    dimensions: "",
    inventoried: true,
    has_fraction: true,
    status: true,
    has_tax: true,
    has_special: false,
    has_promotion: false,
    update: false,
});

/**
 *Datos de la ventana
 */
const taxes: Ref<taxI[]> = ref(props.setting.tax);
const dataUnit: Ref<string[]> = ref(props.setting.unit);
const typeOptions: Ref<ProductOptionsI[]> = ref([
    {
        name: 'Producto',
        value: 'producto',
    },
    {
        name: 'Servicio',
        value: 'servicio'
    }]);


/**
 * Al momento de cargar
 */
onMounted(() => {

    // Pasar los datos a editar
    if (propsW.productEdit) {
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
    if (propsW.warehouse.length > 0) {
        form.warehouse_id = propsW.warehouse[0].id;
    }

});


/*
Propiedades computada
 */
/**
 * Precio sin impuesto
 */
const priceNoTax = computed(() => {
    let price: number = form.price * 100;
    let tax: number = form.tax_rate;
    let taxTotal: number = (price * tax) / 100;
    form.tax = (price * (tax / 100)) / 100;
    form.product_no_tax = (price - taxTotal) / 100;

    return getMoney(form.product_no_tax);
});


/**
 *Beneficios del producto
 */
const benefits = computed(() => {
    let cost: number = form.cost * 100;
    let price: number = form.price * 100;

    // Tomar el beneficios
    form.benefits = Math.round((price - cost) / 100);

    // Devolver los datos
    return getMoney(form.benefits);
});

/**
 * Margen de beneficios
 */
const benefitsMargin = computed(() => {
    let cost: number = form.cost * 100;
    let price: number = form.price * 100;

    // Calcular el margen de beneficios
    form.benefits_rate = ((price - cost) / cost) * 100 || 0;

    //Devolver el valor de los datos
    return form.benefits_rate.toFixed(2) + ' %'
});


/**
 * Funcion para enviar los datos
 */
const submit = () => {

    if (propsW.update || form.update) {
        form.patch(route('product.update', form.id), {
            onSuccess: () => {
                successHttp('Datos actualizado correctamente')

            }
        })
    } else {
        // Formulario para guardar los productos
        form.post(route('product.store'), {
            onSuccess: () => {
                // Datos de la alerta
                successHttp('Datos registrado correctamente')
                form.reset()
            }
        });
    }

}


const checkProductExits = () => {
    axios.get(route('product.get.json', {search: form.name}))
        .then(res => {
            if (res.status === 200) {
                checkProduct.value = res.data;
            }
        });
}


function selectProduct(item: productBaseI) {
    Swal.fire({
        title: "Desea Actualizar?",
        text: `Desea actualizar el producto : ${item.name} !`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si Actualizar!",
        cancelButtonText: "Cancelar!",
    }).then((result) => {
        if (result.isConfirmed) {

            form.id = item.id;
            form.name = item.name;
            form.type = item.type;
            form.description = item.name;
            form.cost = item.cost;
            form.price = item.price;
            form.category_id = item.category_id;
            form.supplier_id = item.supplier_id;
            form.sku = item.sku ?? '';
            form.unit = item.unit;
            form.bar_code = item.bar_code ?? '';
            form.type = item.type;
            form.tax_rate = item.tax_rate ?? 0;
            form.weight = item.weight ?? '';
            form.brand = item.brand ?? '';
            form.cost = item.cost ?? 0;
            form.price = item.price ?? 0;
            form.min_price = item.min_price ?? 0;
            form.special_price = item.special_price ?? 0;
            form.inventoried = item.inventoried;
            form.has_fraction = item.has_fraction;
            form.status = item.status;
            form.has_tax = item.has_tax;
            form.update = true

        }
    });
}

</script>


<template>
    <!--Formulario-->
    <form
        @submit.prevent="submit">
        <!--Titulo-->
        <h3 class="text-2xl font-bold text-center">
            Registro de producto
        </h3>

        <div v-if="propsW.nextProduct">
            <p>Seguiente ID :
                <span class="px-2 py-1 rounded-md">
                    {{ propsW.nextProduct }}
                </span>
            </p>
        </div>

        <!--Informacion General-->
        <div class="">
            <ProductGeneral
                v-model:inventoried="form.inventoried"
                v-model:has-fraction="form.has_fraction"
                v-model:status="form.status"
                v-model:has_tax="form.has_tax"
                v-model:has_special="form.has_special"
                v-model:has_promotion="form.has_promotion"
            />


            <fieldset class="field">
                <legend>
                    Informacion
                </legend>

                <!-- Nombre -->
                <div>
                    <InputLabel
                        class="inline ml-2"
                        for="name"
                        value="Nombre"/>
                    <div class="relative">
                        <TextInput
                            @keyup="checkProductExits"
                            class=" w-full peer"
                            name="name"
                            required
                            autocomplete="off"
                            v-model="form.name"
                            placeholder="Nombre del producto"
                        />
                        <div
                            class=" opacity-0  peer-focus:opacity-100 z-20 text-gray-50 absolute w-full bg-gray-800 border-2 rounded-md">
                            <ol
                                v-for="(item, index) in checkProduct"
                                :key="index"
                                class="odd:bg-cyan-400 rounded-md">
                                <li
                                    @click="selectProduct(item)"
                                    class="rounded-md px-5">
                                    {{ item.name }}
                                </li>
                            </ol>
                        </div>
                    </div>

                </div>

                <!-- Descricion -->
                <div class="">
                    <InputLabel
                        class="inline ml-2"
                        for="description"
                        value="Descripcion"/>
                    <TextInput
                        class=" w-full"
                        name="name"
                        v-model="form.description"
                        placeholder="Descripcion"
                    />
                </div>

                <div>
                    <InputLabel
                        class="inline ml-2"
                        for="category"
                        value="Categoria"/>
                    <div>
                        <select
                            v-model="form.category_id"
                            class=" w-[90%] inputGeneral py-1 ">
                            <option
                                selected
                                disabled
                                :value="0">
                                -- Categoria --
                            </option>
                            <option
                                class="even:bg-blue-200"
                                v-for="(item, index) in propsW.categories"
                                :key="index"
                                :value="item.id">
                                {{ item.name }}
                            </option>
                        </select>
                        <i
                            @click="showCategory = true"
                            class="icon-efect text-cyan-400 text-[1.5rem] ml-3 fa-solid fa-code-branch"></i>
                    </div>

                </div>

                <!-- Proveedor -->
                <div class="">
                    <InputLabel
                        class="inline ml-2"
                        for="supplier"
                        value="Proveedor"/>
                    <div>
                        <select
                            v-model="form.supplier_id"
                            class=" w-[90%] inputGeneral py-1 ">
                            <option selected disabled :value="0">-- Suplidor --</option>
                            <option
                                class="even:bg-blue-200"
                                v-for="(item, index) in propsW.suppliers"
                                :key="index"
                                :value="item.id">
                                {{ item.company_name }}
                            </option>
                        </select>
                        <i
                            @click="showSupplier = true"
                            class="icon-efect text-cyan-400 text-[1.5rem] ml-3 fa-solid fa-truck"></i>
                    </div>
                </div>
            </fieldset>

            <div class=" grid grid-cols-2 gap-4 mt-3">
                <ProductExtra
                    v-model:sku="form.sku"
                    v-model:bar-code="form.bar_code"
                    v-model:type="form.type"
                    v-model:ware-house-id="form.warehouse_id"
                    :type-options="typeOptions"
                    :ware-houses="propsW.warehouse"/>

                <!--Detalle del producto-->
                <ProductDetail
                    v-model:tax-rate="form.tax_rate"
                    v-model:unit="form.unit"
                    v-model:weigh="form.weight"
                    v-model:brand="form.brand"
                    v-model:dimension="form.dimensions"
                    :data-unit="dataUnit"
                    :is-product="form.type == 'producto'"
                    :taxes="taxes"/>

            </div>
            <fieldset class="field grid grid-cols-4 gap-3">
                <legend>Datos de Ventas</legend>
                <div>
                    <InputLabel
                        class="inline ml-2"
                        for="sale_cost"
                        value="Costo"/>
                    <Money
                        class="inputGeneral w-full"
                        v-bind="moneyConfig"
                        v-model.number="form.cost"/>
                </div>
                <!--                        Informacion de venta-->
                <div>
                    <InputLabel
                        class="inline ml-2"
                        for="sale_price"
                        value="Precio"/>
                    <Money
                        class="inputGeneral w-full"
                        v-bind="moneyConfig"
                        v-model.number="form.price"/>
                </div>
                <div>
                    <InputLabel
                        class="inline ml-2"
                        for="sale_cost"
                        value="Pre. Minimo"/>
                    <Money
                        class="inputGeneral w-full"
                        v-bind="moneyConfig"
                        v-model.number="form.min_price"/>
                </div>
                <!--                        Informacion de venta-->
                <div>
                    <InputLabel
                        class="inline ml-2"
                        for="sale_price"
                        value="Pre. Especial"/>
                    <Money
                        class="inputGeneral w-full"
                        v-bind="moneyConfig"
                        v-model.number="form.special_price"/>
                </div>
            </fieldset>

            <fieldset disabled class="field grid grid-cols-3 gap-3">
                <legend>Info Ventas</legend>
                <p>
                    <strong>Precio - Itbis</strong>
                    <span class="inline-block px-3 rounded-md ml-3">{{ priceNoTax }}</span>
                </p>
                <p>
                    <strong>Beneficio</strong>
                    <span class="inline-block px-3 rounded-md ml-3">{{ benefits }}</span>
                </p>
                <p>
                    <strong>Beneficios Margen </strong>
                    <span class="inline-block px-3 rounded-md ml-3">{{ benefitsMargin }}</span>
                </p>
            </fieldset>


        </div>


        <!-- Botones -->
        <div class="mt-4 text-right">
            <PrimaryButton
                :disabled="form.processing">
                {{ propsW.update ? 'Actualizar' : 'Registrar' }}
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


</template>
