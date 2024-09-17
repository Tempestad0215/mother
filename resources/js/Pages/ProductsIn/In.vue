<script setup lang="ts">
import {Head, router, useForm, usePage} from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import InputLabel from '@components/InputLabel.vue';
import TextInput from '@components/TextInput.vue';
import {formatNumber, getCoin, getMoney, getPenny} from '@/Global/Helpers';
import PrimaryButton from '@components/PrimaryButton.vue';
import InputError from '@components/InputError.vue';
import FloatBox from '@components/FloatBox.vue';
import {computed, onMounted, ref} from 'vue';
import FloatSupplier from '@/Pages/Suppliers/FloatSupp.vue';
import {successHttp} from "@/Global/Alert";
import FormSearch from "@components/FormSearch.vue";
import {productDataI, productI, productTransI} from "@/Interfaces/Product";
import Pagination from "@components/Pagination.vue";
import LinkHeader from '@components/LinkHeader.vue';
import {pageI} from "@/Interfaces/Global";


/**
 * Datos de la pagina
 */
const page:pageI = usePage()

/**
 * Propiedades de la ventana
 */
// Props de la ventana
const props = defineProps<{
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
    price: 0.00,
    tax: 0.00,
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
const showError = computed(() => {
   if (form.errors.general !== "")
   {
       setTimeout(() =>{
           return false;
       },3000)
   }
});



/**
 * formulario de busquedda
 */
// Para la busqueda
const formSearch = useForm({
    search:""
});




/**
 * Datos de la ventnaa
 */
// Propiedades de la ventana
const registerProduct = ref(false);
const registerSupplier = ref(false);
const showForm = ref<boolean>(false);
const tax_rate = ref<number>(0);


/**
 * Al momento de cargar
 */
//Al momento de crearse
onMounted(()=>{
    // Para los datos a editar
    if(props.productEntrance)
    {
        showForm.value = true;
        form.product_id = props.productEntrance.id;
        form.product_name = props.productEntrance.name;
        form.stock = (props.productEntrance.stock || 0).toFixed(2);
        form.cost = (props.productEntrance.cost || 0).toFixed(2);
        form.price = (props.productEntrance.price || 0).toFixed(2);
        tax_rate.value = props.productEntrance.tax_rate;

        //Calcular los datos
        totalTax();
    }
    if(props.trans)
    {

        showForm.value = true;
        form.tran_id = props.trans.id;
        form.product_id = props.trans.product_id;
        form.product_name = props.trans.product_name;
        form.stock = props.trans.stock;
        form.cost = props.trans.cost;
        form.price = props.trans.price;
        tax_rate.value = props.trans.tax_rate;

        //Calcular la cantidad
        totalTax();
    }
});


/**
 * Propiedades computada
 */
const checkDiscount = computed(() => {
    if( form.cost > 0.00 && page.props.appSetting.save_cost && form.discount_amount >= form.cost)
   {
       form.setError('discount', `El Item : ${form.product_name} Esta Debajo del Costo`);
       return 'text-red-500';
   }
});



/**
 * Funciones
 */
// Computadas



/**
 * Funciones
 */
// funciones
// const getProduct = () => {
//     if(form.product_name.length < 2)
//     {
//         return false;
//     }else{
//         axios.get(route('product.get',{search: form.product_name}))
//             .then((res)=>{
//                 // Pasar los datos
//                 productData.value = res.data;
//
//             });
//     }
// }

//Obtener el valor del select
// const getValue = (id:number)=>{
//     form.product_id = id;
// }

// Enviar formulario
const submit = () => {
    //Para editar
    if(props.update)
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
            product_no_tax: formatNumber(data.product_no_tax),
            product_tax: formatNumber(data.product_tax),
            benefits: formatNumber(data.benefits),
            tax: formatNumber(data.tax)
        })).patch(route('product-in.update',{trans: form.tran_id}),{
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
            product_no_tax: formatNumber(data.product_no_tax),
            product_tax: formatNumber(data.product_tax),
            benefits: formatNumber(data.benefits),
            tax: formatNumber(data.tax)
        })).patch(route('product-in.store',form.product_id),{
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
    router.get(route('product-in.entrance', {productIn: id}));
}

// const destroy = (id:number) => {
//     Swal.fire({
//         title: `Desea eliminar el registro N°: ${id} ?`,
//         text: "Los cambios realizados son irreversible!",
//         icon: "question",
//         showCancelButton: true,
//         confirmButtonColor: "#393434",
//         cancelButtonColor: "#c6c2c2",
//         confirmButtonText: "Si, Eliminar!"
//     }).then((result) => {
//         if (result.isConfirmed) {
//             router.patch(route('product-in.destroy',{productIn: id}),{},{
//                 onSuccess:()=>{
//                     successHttp('Datos eliminado correctamente');
//                 }
//             });
//         }
//     });
//
//
// }

/**
 * Calcular el los impuesto de los ingreos
 */
const totalTax = () => {
    // Sacar los datos para el calculo
    let stock = form.stock || 0.00;
    let cost =  getPenny(form.cost || 0.00);
    let price = getPenny(form.price || 0.00);
    let taxRate = parseFloat(tax_rate.value / 100);
    let discount = form.discount / 100;


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
                :href="route('product-in.create')">
                Entrada
            </LinkHeader>
            <LinkHeader

                :href="route('product-in.show')">
                Mostrar
            </LinkHeader>

        </template>

        <!-- Contenido de la pagina -->
        <div class=" ">

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
                                    class=" w-full"
                                    v-model="form.product_name "
                                    name="product" />
                                <i
                                    class=" absolute inset-y-0 right-3 flex items-center fa-solid fa-circle-arrow-down">
                                </i>

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
                    <div class=" mt-4 grid grid-cols-4 gap-3 overflow-hidden">
                        <!-- Cantidad -->
                        <div>
                            <InputLabel
                                for="stock"
                                value="Cantidad"/>
                            <TextInput
                                class="w-full"
                                name="stock"
                                @blur="totalTax"
                                v-model="form.stock"
                                type="number"/>

                            <!-- Error -->
                            <InputError :message="form.errors.stock" />

                        </div>

                        <!-- Coste -->
                        <div>
                            <InputLabel
                                for="cost"
                                value="Costo"/>
                            <TextInput
                                class="w-full"
                                name="cost"
                                @blur="totalTax"
                                v-model="form.cost"
                                type="number"/>

                            <!-- Error -->
                            <InputError :message="form.errors.cost" />

                        </div>

                        <!-- Precio -->
                        <div>
                            <InputLabel
                                for="price"
                                value="Precio"/>
                            <TextInput
                                class=" w-full"
                                name="price"
                                @blur="totalTax"
                                v-model="form.price"
                                type="number"/>

                            <!-- Error -->
                            <InputError :message="form.errors.price" />

                        </div>

<!--                         Descuento -->
                        <div>
                            <InputLabel
                                for="discount"
                                value="Descuento"/>
                            <TextInput
                                :class="checkDiscount"
                                class=" w-full"
                                name="discount"
                                @blur="totalTax"
                                v-model="form.discount"
                                type="number"/>

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
                                    {{ tax_rate / 100 || 0 }} %
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
                            {{ props.update ? 'Actualizar' : 'Registrar' }}
                        </PrimaryButton>
                    </div>

<!--                    MEnsaje de error generales-->
                    <InputError :message="form.errors.general"/>
                </form>

<!--                //Crear la tabla para mostrar las entrada-->
                <div class=" mt-5 bg-gray-200 p-5 rounded-md">
                    <form
                        @submit.prevent="search" >
                        <FormSearch
                            holder="Buscar Entradas"
                            v-model="formSearch.search"/>
                    </form>


<!--                Datos de los productos para la entrada    -->
                    <table class="table-auto w-full mt-3">
                        <thead>
                            <tr class="text-left">
                                <th>Codigo</th>
                                <th>Cod. Barr.</th>
                                <th>Producto</th>
                                <th>Descripcion</th>
                                <th>Cant.</th>
                                <th>Cost</th>
                                <th>Precio</th>
                                <th>Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                class=" odd:bg-gray-400"
                                v-for="item in props.products?.data">
                                <td>{{item.code}}</td>
                                <td>{{item.bar_code ? item.bar_code : 'N/A'}}</td>
                                <td>{{item.name}}</td>
                                <td>{{item.description}}</td>
                                <td>{{item.stock}}</td>
                                <td>{{ getMoney(item.cost)}}</td>
                                <td>{{ getMoney(item.price)}}</td>
                                <td class="text-xl space-x-3 w-[75px] ">
                                    <i
                                        @click="edit(item.id)"
                                        title="Entrada"
                                        class=" icon-efect fa-solid fa-dolly"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination
                        :next="props.products?.next_page_url ? props.products?.next_page_url : ''"
                        :total-page="props.products?.to"
                        :prev="props.products?.prev_page_url ? props.products?.prev_page_url : ''"
                        :current-page="props.products?.current_page"/>
                </div>



            <!-- Mostrar regitro de producto -->
<!--            <Transition>-->
<!--                <FloatBox-->
<!--                    @close="registerProduct = false"-->
<!--                    v-if="registerProduct">-->
<!--                    <FloatProduct-->
<!--                        :supplier="props.s"-->
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
