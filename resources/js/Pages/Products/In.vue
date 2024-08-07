<script setup lang="ts">
import {Head, router, useForm} from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@components/TextInput.vue';
import {formatNumber, getMoney} from '@/Global/Helpers';
import PrimaryButton from '@components/PrimaryButton.vue';
import InputError from '@components/InputError.vue';
import SecondaryButton from '@components/SecondaryButton.vue';
import FloatBox from '@components/FloatBox.vue';
import {computed, onMounted, PropType, reactive, ref} from 'vue';
import FloatProduct from '@/Pages/Products/FloatPro.vue';
import FloatSupplier from '@/Pages/Suppliers/FloatSupp.vue';
import axios from "axios";
import {successHttp} from "@/Global/Alert";
import FormSearch from "@components/FormSearch.vue";
import {productDataI, productI} from "@/Interfaces/Product";
import Pagination from "@components/Pagination.vue";
import Swal from "sweetalert2";
import LinkHeader from '@components/LinkHeader.vue';


// Props de la ventana
const props = defineProps({
    products: {
        type: Object as PropType<productI>,
        required: true
    },
    productEntrance:{
        type: Object as PropType<productDataI>
    },
    update:{
        type: Boolean,
        default: false
    }
});


// Datos del formulario
const form = useForm({
    product_id:0,
    product_name:"",
    stock: "",
    cost: "",
    price: "",
    tax: "",
    tax_price:"",
    product_no_tax:"",
    tax_amount:"",
    discount:"",
    total:""
});

// Para la busqueda
const formSearch = useForm({
    search:""
});

// Datos de todo
const taxData = reactive({
    tax: 0,
    product_no_tax: 0,
    tax_amount: 0,
    discount: 0,
    total:0,
});



// Propiedades de la ventana
const registerProduct = ref(false);
const registerSupplier = ref(false);
const productData = ref([]);

//Al momento de crearse
onMounted(()=>{
    // Para los datos a editar
    if(props.productEntrance)
    {
        form.product_id = props.productEntrance.id;
        form.product_name = props.productEntrance.name;
        form.stock = props.productEntrance.stock || "";
        form.cost = props.productEntrance.stock || "";
        form.price = props.productEntrance.stock || ""
        ;
    }
})



// Computadas
const isSelected = computed(() => {
    return !props.productEntrance?.id;
})


// funciones
const getProduct = () => {
    if(form.product_name.length < 2)
    {
        return false;
    }else{
        axios.get(route('product.get',{search: form.product_name}))
            .then((res)=>{
                // Pasar los datos
                productData.value = res.data;

            });
    }
}

//Obtener el valor del select
const getValue = (id:number)=>{
    form.product_id = id;
}

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
            total: formatNumber(data.total),
            product_no_tax: formatNumber(data.product_no_tax),
            tax_amount: formatNumber(data.tax_amount),
            discount: formatNumber(data.discount),
        })).patch(route('product-in.update',{productIn: form.product_id}));
    }else{
        form.transform((data) => ({
            ...data,
            stock: formatNumber(data.stock),
            cost: formatNumber(data.cost),
            price: formatNumber(data.price),
            total: formatNumber(data.total),
            product_no_tax: formatNumber(data.product_no_tax),
            tax_amount: formatNumber(data.tax_amount),
            discount: formatNumber(data.discount),
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

const totalTax = () => {
    // Sacar los datos para el calculo
    let stock = formatNumber(form.stock) || 0.00;
    let cost = formatNumber(form.cost) || 0.00;
    let price = formatNumber(form.price) || 0.00;
    let tax_rate = props.productEntrance ? props.productEntrance?.tax_rate : 0;

    // Tomar los datos para sacar el impuesto
    let tax = ((cost * 100) * tax_rate) / 100;

    // tomar los datos del decuento
    let discount = formatNumber(form.discount) / 100 || 0;



    // Guardar los datos en el formulario
    taxData.tax = tax;
    taxData.product_no_tax = (cost - tax);
    taxData.tax_amount = tax * stock;

    // tamar el calor de stock y cost
    taxData.total = (stock * price);
    taxData.discount = taxData.total * discount;

    // Pasar la info al
    form.tax = (taxData.tax).toFixed(2);
    form.tax_amount = (taxData.tax_amount).toFixed(2);
    form.discount = (taxData.discount).toFixed(2);
    form.product_no_tax = (taxData.product_no_tax).toFixed(2);
    form.total = (taxData.total).toFixed(2);

}


</script>


<template>
    <Head title="Entrada" />
    <AppLayout>
        <template #header >
            <LinkHeader
                :href="route('product.create')">
                Registrar
            </LinkHeader>
            <LinkHeader
                :href="route('product-sale.create')">
                Venta
            </LinkHeader>
            <LinkHeader
                :active="true"
                :href="route('product-in.create')">
                Entrada
            </LinkHeader>

        </template>

        <!-- Contenido de la pagina -->
        <div class="">

                <form
                    v-if="props.productEntrance"
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
                                :readonly="isSelected"
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
                                :readonly="isSelected"
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
                                :readonly="isSelected"
                                @blur="totalTax"
                                v-model="form.price"
                                type="number"/>

                            <!-- Error -->
                            <InputError :message="form.errors.price" />

                        </div>

                        <!-- Precio -->
                        <div>
                            <InputLabel
                                for="discount"
                                value="Descuento"/>
                            <TextInput
                                class=" w-full"
                                name="discount"
                                :readonly="isSelected"
                                @blur="totalTax"
                                v-model="form.discount"
                                type="number"/>

                            <!-- Error -->
                            <InputError :message="form.errors.discount" />

                        </div>


                        <!-- Datos tributario -->
                        <fieldset
                            class=" col-span-full grid grid-cols-6 gap-3 border-2 border-gray-500 rounded-md p-5">

                            <legend>
                                Tributario
                            </legend>

                            <div>
                                <InputLabel
                                    for="tax_rate"
                                    value="ITBIS %"/>
                                <span>
                                    {{ productEntrance ? productEntrance.tax_rate * 100 : 0 }} %
                                </span>
                            </div>

                            <!-- Impuesto -->
                            <div>
                                <InputLabel
                                    for="tax"
                                    value="ITBIS * 1 " />
                                <span>
                                    {{getMoney(taxData.tax)}}
                                </span>
                            </div>

                            <!-- Impuesto -->
                            <div>
                                <InputLabel
                                    for="price-no-tax"
                                    value="Precio sin ITBIS * 1" />
                                <span>
                                    {{getMoney(taxData.product_no_tax)}}
                                </span>
                            </div>

                            <!-- Impuesto -->
                            <div>
                                <InputLabel
                                    for="tax-aomount"
                                    value="Total del impuesto" />
                                <span>
                                    {{getMoney(taxData.tax_amount)}}
                                </span>
                            </div>

                            <!-- Decuento -->
                            <div>
                                <InputLabel
                                    for="discount"
                                    value="Descuento" />
                                <span>
                                    {{getMoney(taxData.discount)}}
                                </span>
                            </div>


                            <!-- Decuento -->
                            <div>
                                <InputLabel
                                    for="discount"
                                    value="Total Ingresado" />
                                <span>
                                    {{getMoney(taxData.total)}}
                                </span>
                            </div>
                        </fieldset>


                    </div>

                    <!-- Boton para el producto -->
                    <div class="mt-4 text-right col-span-full">
                        <PrimaryButton>
                            {{ props.update ? 'Actualizar' : 'Registrar' }}
                        </PrimaryButton>
                    </div>

                </form>

<!--                //Crear la tabla para mostrar las entrada-->
                <div class=" mt-5 bg-gray-200 p-5 rounded-md">
                    <form
                        @submit.prevent="search" >
                        <FormSearch
                            holder="Buscar Entradas"
                            v-model="formSearch.search"/>
                    </form>

                    <table class="table-auto w-full mt-3">
                        <thead>
                            <tr class="text-left">
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
                                <td>{{item.name}}</td>
                                <td>{{item.description}}</td>
                                <td>{{item.stock}}</td>
                                <td>{{item.cost}}</td>
                                <td>{{item.price}}</td>
                                <td class="text-xl space-x-3 w-[75px] ">
                                    <i
                                        @click="edit(item.id)"
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
            <Transition>
                <FloatBox
                    @close="registerProduct = false"
                    v-if="registerProduct">
                    <FloatProduct
                        @show-supplier="registerSupplier = true"
                        class=" bg-gray-200 p-5 w-4/5 rounded-md"/>
                </FloatBox>
            </Transition>

            <!-- Mostrar registro de suplidores -->
            <Transition>
                    <FloatBox
                        @close=" registerSupplier = false"
                        v-if="registerSupplier">
                        <FloatSupplier/>
                    </FloatBox>
            </Transition>
        </div>

    </AppLayout>

</template>
