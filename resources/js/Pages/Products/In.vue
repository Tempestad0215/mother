<script setup lang="ts">
import HeaderBox from '@components/HeaderBox.vue';
import NavLink from '@components/NavLink.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import AppLayout from '@layout/AppLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputSelect from '@components/InputSelect.vue';
import ContentBox from '@components/ContentBox.vue';
import TextInput from '@components/TextInput.vue';
import {formatNumber, moneyConfig} from '@/Global/Helpers';
import PrimaryButton from '@components/PrimaryButton.vue';
import InputError from '@components/InputError.vue';
import SecondaryButton from '@components/SecondaryButton.vue';
import FloatBox from '@components/FloatBox.vue';
import {onMounted, PropType, ref} from 'vue';
import FloatProduct from '@/Pages/Products/FloatPro.vue';
import FloatSupplier from '@/Pages/Suppliers/FloatSupp.vue';
import axios from "axios";
import {successHttp} from "@/Global/Alert";
import FormSearch from "@components/FormSearch.vue";
import {productDataI, productI} from "@/Interfaces/Product";
import Pagination from "@components/Pagination.vue";
import Swal from "sweetalert2";
import LinkHeader from '@components/LinkHeader.vue';

const props = defineProps({
    products: {
        type: Object as PropType<productI>,
        required: true
    },
    productEdit:{
        type: Object as PropType<productDataI>
    },
    update:{
        type: Boolean,
        default: false
    }
});


const form = useForm({
    product_id:0,
    product_name:"",
    stock: "",
    cost:"",
    price:""
});

const formSearch = useForm({
    search:""
});


// Propiedades de la ventana
const registerProduct = ref(false);
const registerSupplier = ref(false);
const productData = ref([]);

//Al momento de crearse
onMounted(()=>{
    // Para los datos a editar
    if(props.productEdit)
    {
        form.product_id = props.productEdit.id;
        form.product_name = props.productEdit.name;
        form.stock = props.productEdit.stock;
        form.cost = props.productEdit.cost;
        form.price = props.productEdit.price;
    }
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
            price: formatNumber(data.price)
        })).patch(route('product-in.update',{productIn: form.product_id}));
    }else{
        form.transform((data) => ({
            ...data,
            stock: formatNumber(data.stock),
            cost: formatNumber(data.cost),
            price: formatNumber(data.price)
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
    router.get(route('product-in.edit', {productIn: id}));
}

const destroy = (id:number) => {
    Swal.fire({
        title: `Desea eliminar el registro N°: ${id} ?`,
        text: "Los cambios realizados son irreversible!",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#393434",
        cancelButtonColor: "#c6c2c2",
        confirmButtonText: "Si, Eliminar!"
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(route('product-in.destroy',{productIn: id}),{},{
                onSuccess:()=>{
                    successHttp('Datos eliminado correctamente');
                }
            });
        }
    });


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
                :href="route('product.show')">
                Mostrar
            </LinkHeader>
            <LinkHeader
                :href="route('product-in.create')">
                Entrada
            </LinkHeader>

        </template>

        <!-- Contenido de la pagina -->
        <div class="">

                <form
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
                                    name="product" />
                                <i
                                    class=" absolute inset-y-0 right-3 flex items-center fa-solid fa-circle-arrow-down">
                                </i>

                            </div>

                            <SecondaryButton
                                :disable="form.processing"
                                @click="registerProduct = !registerProduct"
                                type="button">
                                Pro...
                            </SecondaryButton>
                        </div>
                    </div>

                    <!-- Datos del producto -->
                    <div class=" mt-4 grid grid-cols-3 gap-3 overflow-hidden">
                        <!-- Cantidad -->
                        <div>
                            <InputLabel
                                for="stock"
                                value="Cantidad"/>
                            <TextInput
                                class="w-full"
                                name="stock"
                                v-money3="moneyConfig"
                                v-model.lazy="form.stock"
                                type="text"/>

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
                                v-money3="moneyConfig"
                                v-model.lazy="form.cost"
                                type="text"/>

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
                                v-money3="moneyConfig"
                                v-model.lazy="form.price"
                                type="text"/>

                            <!-- Error -->
                            <InputError :message="form.errors.price" />

                        </div>


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
                                <th>Id</th>
                                <th>Producto</th>
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
                                <td>{{item.id}}</td>
                                <td>{{item.name}}</td>
                                <td>{{item.stock}}</td>
                                <td>{{item.cost}}</td>
                                <td>{{item.price}}</td>
                                <td class="text-xl space-x-3 w-[75px] ">
                                    <i
                                        @click="edit(item.id)"
                                        class=" icon-efect fa-solid fa-pen-to-square"></i>
                                    <i
                                        @click="destroy(item.id)"
                                        class="icon-efect fa-solid fa-trash"></i>
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
                        class=" bg-gray-200 p-5 w-2/5 rounded-md"/>
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
