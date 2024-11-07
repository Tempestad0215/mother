<script setup lang="ts">
import {Head, router, useForm, usePage} from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import InputLabel from '@components/InputLabel.vue';
import TextInput from '@components/TextInput.vue';
import {formatNumber, getCoin, getMoney, getPenny} from '@/Global/Helpers';
import PrimaryButton from '@components/PrimaryButton.vue';
import InputError from '@components/InputError.vue';
import FloatBox from '@components/FloatBox.vue';
import {onMounted, Ref, ref} from 'vue';
import FloatSupplier from '@/Pages/Suppliers/FloatSupp.vue';
import {successHttp} from "@/Global/Alert";
import FormSearch from "@components/FormSearch.vue";
import {productDataI, productI, productTransI} from "@/Interfaces/Product";
import Pagination from "@components/Pagination.vue";
import LinkHeader from '@components/LinkHeader.vue';
// import {appSettingI} from "@/Interfaces/Global";
import {InputNumber} from "primevue";
import {appSettingI} from "@/Interfaces/Global";


/**
 * Datos de la pagina
 */
// const {props} = usePage();
const setting:appSettingI = usePage().props.setting


/**
 * Propiedades de la ventana
 */
// propsW de la ventana
const propsW = defineProps<{
    products: productI,
    trans?: productTransI,
    productEntrance?: productDataI,
    update? : boolean
}>();


/**
 * Formulario para enviar los daots
 */
// Datos del formulario
const form = useForm({
    tran_id: 0,
    sale_id: 0,
    product_id:0,
    product_name:"",
    stock: 0.00,
    cost: 0.00,
    min_price:0.00,
    special_price:0.00,
    price: 0.00,
    tax: 0.00,
    tax_rate: 0 ,
    product_no_tax: 0.00,
    product_tax: 0.00,
    tax_amount: 0.00,
    discount: 0.00,
    discount_amount: 0.00,
    amount: 0.00,
    benefits: 0.00,
    general:""
});



/*
Computada
 */
// const showError = computed(() => {
//    if (form.errors.general !== "")
//    {
//        setTimeout(() =>{
//            return false;
//        },3000)
//    }
// });



/**
 * formulario de busquedda
 */
// Para la busqueda
const formSearch = useForm({
    search:"",
    perPage:15
});




/**
 * Datos de la ventnaa
 */
// Propiedades de la ventana
const registerSupplier:Ref<boolean> = ref(false);
const showForm:Ref<boolean> = ref(false);


/**
 * Al momento de cargar
 */
//Al momento de crearse
onMounted(()=>{
    // Para los datos a editar
    if(propsW.productEntrance)
    {
        showForm.value = true;
        form.product_id = propsW.productEntrance.id;
        form.product_name = propsW.productEntrance.name;
        form.stock = propsW.productEntrance.stock;
        form.cost = <number>propsW.productEntrance.cost;
        form.min_price = propsW.productEntrance.min_price;
        form.special_price = propsW.productEntrance.special_price;
        form.price = propsW.productEntrance.price;
        form.tax_rate =  propsW.productEntrance.tax_rate / 100;

        //Calcular los datos
        totalTax();
    }

    //Verificar si exsite la transacciones
    if(propsW.trans)
    {

        showForm.value = true;
        form.tran_id = propsW.trans.id;
        form.product_id = propsW.trans.product_id;
        form.product_name = propsW.trans.product_name;
        form.stock = propsW.trans.stock;
        form.cost = propsW.trans.cost;
        form.min_price = propsW.trans.min_price;
        form.special_price = propsW.trans.special_price;
        form.price = propsW.trans.price;
        form.tax_rate = propsW.trans.tax_rate / 100;

        //Calcular la cantidad
        totalTax();
    }
});


/**
 * Propiedades computada
 */
// const checkDiscount = computed(() => {
//     if( form.cost > 0.00 && props.setting.save_cost && form.discount_amount >= form.cost)
//    {
//        form.setError('discount', `El Item : ${form.product_name} Esta Debajo del Costo`);
//        return 'text-red-500';
//    }
// });


// Enviar formulario
const submit = () => {

    // //Para editar
    if(propsW.update)
    {
        form.transform((data) =>({
            ...data,
            stock: formatNumber(data.stock),
            cost: formatNumber(data.cost),
            price: formatNumber(data.price),
            amount: formatNumber(data.amount),
            product_no_tax: formatNumber(data.product_no_tax),
            tax_amount: formatNumber(data.tax_amount),
            discount: formatNumber(data.discount),
            benefits: formatNumber(data.benefits),
            tax: formatNumber(data.tax),
            tax_rate: formatNumber(data.tax_rate)
        })).patch(route('in.update',{trans: form.tran_id}),{
            onSuccess:()=>{
              successHttp('Datos actualizado correctamente');
            },
            onError:()=>{
                setTimeout(()=>{
                    form.clearErrors('general');
                },5000);
            }
        });
    }else{
        form.transform((data) => ({
            ...data,
            stock: formatNumber(data.stock),
            cost: formatNumber(data.cost),
            price: formatNumber(data.price),
            amount: formatNumber(data.amount),
            product_no_tax: formatNumber(data.product_no_tax),
            tax_amount: formatNumber(data.tax_amount),
            discount: formatNumber(data.discount),
            benefits: formatNumber(data.benefits),
            tax: formatNumber(data.tax),
            tax_rate: formatNumber(data.tax_rate)
        })).patch(route('in.store',form.product_id),{
            onSuccess:()=>{
                successHttp('Datos registrado correctamente');
                form.reset();
            }
        });
    }
}

const search = () => {
    formSearch.get(`?search=${formSearch.search}`,{
        preserveScroll: true,
        preserveState: true,
    });
}

const edit = (id:number)=>{
    router.get(route('in.entrance', {productIn: id}));
}


/**
 * Calcular el los impuesto de los ingreos
 */
const totalTax = () => {
    // Sacar los datos para el calculo
    let stock:number = form.stock || 0.00;
    let cost:number =  getPenny(form.cost || 0.00);
    let price:number = getPenny(form.price || 0.00);
    let taxRate:number = form.tax_rate;
    let discount:number = form.discount / 100;


    // Tomar los datos para sacar el impuesto
    form.tax =  getCoin(price * taxRate);
    form.discount_amount = getCoin(price * discount);
    form.product_no_tax = getCoin(price) - form.tax;
    form.product_tax = form.price;
    form.amount = getCoin( stock * price);
    form.benefits =  getCoin(price - cost);
    form.tax_amount = form.stock * form.tax;

}


</script>


<template>
    <Head title="Entrada" />
    <AppLayout>
        <template #header >
            <LinkHeader
                :active="true"
                :href="route('in.create')">
                Entrada
            </LinkHeader>
            <LinkHeader

                :href="route('in.show')">
                Mostrar
            </LinkHeader>

        </template>

        <!-- Contenido de la pagina -->
        <div class="max-w-[1100px] mx-auto max-h-[85vh] overflow-y-auto">
                <form
                    v-if="showForm"
                    class=" p-5 rounded-md bg-gray-200"
                    @submit.prevent="submit">
                    <h3 class=" text-2xl font-bold text-center ">
                        Entrada de producto
                    </h3>
                    <!-- Seleccionar el producto -->
                    <div class="mt-4 max-w-2xl ">
                        <InputLabel
                            for="product" value="Producto"/>
                        <div class=" flex space-x-5">
                            <div class="relative flex-1">
                                <TextInput
                                    maxLength="50"
                                    class="w-3/4"
                                    v-model="form.product_name "
                                    name="product" />
                            </div>

<!--                            <SecondaryButton-->
<!--                                :disable="form.processing"-->
<!--                                @click="registerProduct = !registerProduct"-->
<!--                                type="button">-->
<!--                                Pro...-->
<!--                            </SecondaryButton>-->
                        </div>
                    </div>

                    <!-- Datos del producto -->
                    <div class=" mt-4 grid grid-cols-3 gap-3 overflow-hidden">
                        <!-- Cantidad -->
                        <div>
                            <InputLabel
                                for="stock"
                                value="Cantidad"/>
                            <InputNumber
                                @blur="totalTax"
                                input-id="stock"
                                locale="en-US"
                                placeholder="Cantidad"
                                :min-fraction-digits="2"
                                :max-fraction-digits="2"
                                fluid
                                v-model.number="form.stock"/>

                            <!-- Error -->
                            <InputError :message="form.errors.stock" />

                        </div>

                        <!-- Coste -->
                        <div>
                            <InputLabel
                                for="cost"
                                value="Costo"/>

                            <InputNumber
                                @blur="totalTax"
                                input-id="cost"
                                locale="en-US"
                                placeholder="Costo"
                                :min-fraction-digits="2"
                                :max-fraction-digits="2"
                                fluid
                                v-model="form.cost"/>

                            <!-- Error -->
                            <InputError :message="form.errors.cost" />

                        </div>

                        <!-- Precio Espcial -->
                        <div>
                            <InputLabel
                                for="minPrice"
                                value="Precio Especial"/>

                            <InputNumber
                                @blur="totalTax"
                                input-id="cost"
                                :min="setting.sequence ? form.cost : 0"
                                locale="en-US"
                                placeholder="Precio Especial"
                                :min-fraction-digits="2"
                                :max-fraction-digits="2"
                                fluid
                                v-model="form.special_price"/>

                            <!-- Error -->
                            <InputError :message="form.errors.min_price" />

                        </div>

                        <!-- Precio Special -->
                        <div>
                            <InputLabel
                                for="minPrice"
                                value="Precio Minímo"/>

                            <InputNumber
                                @blur="totalTax"
                                :min="setting.sequence ? form.cost : 0"
                                input-id="cost"
                                locale="en-US"
                                placeholder="Precio minimo"
                                :min-fraction-digits="2"
                                :max-fraction-digits="2"
                                fluid
                                v-model="form.min_price"/>


                            <!-- Error -->
                            <InputError :message="form.errors.min_price" />

                        </div>

                        <!-- Precio -->
                        <div>
                            <InputLabel
                                for="price"
                                value="Precio"/>

                            <InputNumber
                                @blur="totalTax"
                                input-id="cost"
                                locale="en-US"
                                placeholder="Precio"
                                :min-fraction-digits="2"
                                :max-fraction-digits="2"
                                fluid
                                v-model="form.price"/>

                            <!-- Error -->
                            <InputError :message="form.errors.price" />

                        </div>

<!--                         Descuento -->
                        <div>
                            <InputLabel
                                for="discount"
                                value="Descuento"/>


                            <InputNumber
                                @blur="totalTax"
                                prefix="%"
                                input-id="cost"
                                locale="en-US"
                                placeholder="Descuento en %"
                                fluid
                                v-model="form.discount"/>

                            <!-- Error -->
                            <InputError :message="form.errors.discount" />

                        </div>


                        <!-- Datos tributario -->
                        <fieldset
                            class=" col-span-full flex flex-row gap-3 border-2 border-gray-500 rounded-md p-5">

                            <legend>
                                Tributario
                            </legend>

                            <div class="flex-auto">
                                <InputLabel
                                    for="tax_rate"
                                    value="ITBIS %"/>
                                <span>
                                    {{ form.tax_rate || 0 }} %
                                </span>
                            </div>

                            <!-- Impuesto -->
                            <div class="flex-auto">
                                <InputLabel
                                    for="tax"
                                    value="ITBIS * 1 " />
                                <span>
                                    {{getMoney(form.tax)}}
                                </span>
                            </div>

                            <!-- Precio sin impuesto -->
                            <div class="flex-auto">
                                <InputLabel
                                    for="price-no-tax"
                                    value="Precio - ITBIS * 1" />
                                <span>
                                    {{getMoney(form.product_no_tax)}}
                                </span>
                            </div>

                            <!-- Precio con impuesto -->
                            <div class="flex-auto">
                                <InputLabel
                                    for="price-no-tax"
                                    value="Precio + ITBIS * 1" />
                                <span>
                                    {{getMoney(form.product_tax)}}
                                </span>
                            </div>

                            <!-- Impuesto -->
                            <div class="flex-auto">
                                <InputLabel
                                    for="tax-aomount"
                                    value="Total del impuesto" />
                                <span>
                                    {{getMoney(form.tax_amount)}}
                                </span>
                            </div>

<!--                             Decuento-->
                            <div class="flex-auto">
                                <InputLabel
                                    for="discount"
                                    value="Descuento" />
                                <span>
                                    {{getMoney(form.discount_amount)}}
                                </span>
                            </div>


                            <!-- Decuento -->
                            <div class="flex-auto">
                                <InputLabel
                                    for="discount"
                                    value="Total Ingresado" />
                                <span>
                                    {{getMoney(form.amount)}}
                                </span>
                            </div>


                            <!-- Beneficio -->
                            <div class="flex-auto">
                                <InputLabel
                                    for="benefit"
                                    value="Beneficio * 1" />
                                <span>
                                    {{getMoney(form.benefits)}}
                                </span>
                            </div>
                        </fieldset>


                    </div>

                    <!-- Boton para el producto -->
                    <div class="mt-4 text-right col-span-full">
                        <PrimaryButton
                            :disabled="form.processing">
                            {{ propsW.update ? 'Actualizar' : 'Registrar' }}
                        </PrimaryButton>
                    </div>

<!--                    MEnsaje de error generales-->
                    <InputError :message="form.errors.general"/>
                </form>

<!--                //Crear la tabla para mostrar las entrada-->
                <div class=" mt-5 bg-gray-200 p-5 rounded-md">

                    <div class="flex justify-between items-center">
                        <form
                            @submit.prevent="search" >
                            <FormSearch
                                v-model:select-value="formSearch.perPage"
                                holder="Buscar Entradas"
                                v-model="formSearch.search"/>
                        </form>
                        <h3 class="text-3xl font-bold">
                            Productos
                        </h3>
                    </div>


                    <div
                        class="max-h-[600px] overflow-y-auto ">
                        <!--                Datos de los productos para la entrada    -->
                        <table
                            class="table-auto w-full mt-5">
                            <thead class="sticky top-0">
                                <tr class="text-left">
                                    <th >Cod. Barr.</th>
                                    <th >Producto</th>
                                    <!--                                <th>Descripcion</th>-->
                                    <th>Cant.</th>
                                    <th>Cost</th>
                                    <th>Precio</th>
                                    <th>Act</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr
                                class=""
                                v-for="item in propsW.products?.data">
                                <td class="">{{item.bar_code ? item.bar_code : 'N/A'}}</td>
                                <td class="overflow-hidden">{{item.name}}</td>
                                <!--                                <td>{{item.description}}</td>-->
                                <td>{{item.stock}}</td>
                                <td>{{ getMoney(item.cost)}}</td>
                                <td>{{ getMoney(item.price)}}</td>
                                <td class="text-xl space-x-3 ">
                                    <i
                                        @click="edit(item.id)"
                                        title="Entrada"
                                        class=" icon-efect fa-solid fa-dolly"></i>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    <Pagination
                        :next="propsW.products?.next_page_url
                                ? propsW.products?.next_page_url+'&perPage='+formSearch.perPage
                                : ''"
                        :total-page="propsW.products?.to"
                        :prev="propsW.products?.prev_page_url
                                ? propsW.products?.prev_page_url+'&perPage='+formSearch.perPage
                                : ''"
                        :current-page="propsW.products?.current_page"/>

                </div>



            <!-- Mostrar regitro de producto -->
<!--            <Transition>-->
<!--                <FloatBox-->
<!--                    @close="registerProduct = false"-->
<!--                    v-if="registerProduct">-->
<!--                    <FloatProduct-->
<!--                        :supplier="propsW.s"-->
<!--                        @show-supplier="registerSupplier = true"-->
<!--                        class=" bg-gray-200 p-5 w-4/5 rounded-md"/>-->
<!--                </FloatBox>-->
<!--            </Transition>-->

            <!-- Mostrar registro de suplidores -->
            <Transition>
                    <FloatBox
                        @close=" registerSupplier = false"
                        v-if="registerSupplier">
                        <FloatSupplier
                            class="w-full"/>
                    </FloatBox>
            </Transition>
        </div>

    </AppLayout>

</template>
