<script setup lang="ts">
import HeaderBox from '@/Components/HeaderBox.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ContentBox from '@components/ContentBox.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@components/TextInput.vue';
import InputError from '@components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { successHttp } from '@/Global/Alert';
import SecondaryButton from '@components/SecondaryButton.vue';
import Float from '@/Pages/Suppliers/Float.vue'
import FloatBox from '@/Components/FloatBox.vue'
import InputSelect from '@/Components/InputSelect.vue'
import { onMounted, PropType, ref } from 'vue';
import axios from 'axios';
import { supplierI } from '@/Interfaces/Supplier';
import NavLink from '@components/NavLink.vue';
import type { proSupResI } from '@/Interfaces/Product';


const props = defineProps({
    productEdit: {
        type: Object as PropType<proSupResI>,
    },
    update: {
        type: Boolean
    }
})


// Datos del formulario
const form = useForm({
    name: "",
    description: "",
    unit: "",
    supplier_id:0,
    search:"",
});

// Cons datos de la ventana
const showSupplierForm = ref(false);
const dataSelect = ref<supplierI[]>([]);

onMounted(()=>{
    // actualizar los suplidores
    getSupplier();
    // Pasar los datos a editar
    if(props.productEdit?.data)
    {
        form.name = props.productEdit.data.name
        form.description = <string>props.productEdit.data.description
        form.unit = props.productEdit.data.unit
        form.supplier_id = props.productEdit.data.supplier.id
        form.search = props.productEdit.data.supplier.name
    }
})



// Funciones
const submit = () => {
    // Formulario para guardar los productos
    form.post(route('product.store'),{
        onSuccess:()=>{
            // Datos de la alerta
            successHttp('Datos registrado correctamente');
            form.reset();
        }
    });
}

// conseguir los nuevos suplidores
const getSupplier = () =>{
    axios.get(route('supplier.get', {search: 'hola'}))
        .then((res)=>{
            dataSelect.value = res.data

        }).catch((err)=>{
            console.log('error', err)
        })
}

const getValueSelect = (supplierId:number) =>{
    form.supplier_id = supplierId;
}

</script>



<template>
    <Head title="Productos"/>

    <!-- Contenido de la ventana -->
    <AppLayout>
        <!-- cabecera -->
        <template #header >
            <HeaderBox>
                <h2>
                    Registro de productos
                </h2>

                <template #link>
                    <NavLink
                        :active="true"
                        :href="route('product.create')" >
                        Registrar
                    </NavLink>
                    <NavLink
                        :href="route('product.show')" >
                        Mostrar
                    </NavLink>
                    <NavLink
                        :href="route('product.create')" >
                        Entrada
                    </NavLink>


                </template>

            </HeaderBox>
        </template>

        <!-- Contenido de la ventana de los productos -->
        <div>
            <ContentBox>
                <form
                    @submit.prevent="submit" >
                    <!-- Nombre -->
                    <div>
                        <InputLabel
                            for="name"
                            value="Nombre *"/>
                        <TextInput
                            class=" w-full"
                            name="name"
                            v-model="form.name"
                            placeholder="Nombre del producto"
                            />
                        <!-- Error -->
                        <InputError :message="form.errors.name" />
                    </div>
                    <!-- Descricion -->
                    <div class="mt-4">
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
                    <!-- Unidad -->
                    <div class="mt-4">
                        <InputLabel
                            for="name"
                            value="Nombre *"/>
                        <select
                            v-model="form.unit"
                            class=" w-full border-gray-300 rounded-md ">
                            <option selected disabled value="">
                                --- Seleccione la unidad ---
                            </option>
                            <option value="ONZA">OZ</option>
                            <option value="CAJA">CAJ</option>
                            <option value="UNIDAD">UND</option>
                        </select>
                        <!-- Error -->
                        <InputError :message="form.errors.name" />
                    </div>

                    <!-- Proveedor -->
                    <div class="mt-4">
                        <InputLabel
                            for="supplier_id"
                            value="Proveedor *"/>

                        <div class=" flex space-x-3">
                            <InputSelect
                                field="company_name"
                                :read="true"
                                :data="dataSelect"
                                :model-value="form.search"
                                @send-value="getValueSelect"
                                @update-data="getSupplier()"
                                v-model="form.search"
                                class=" w-full" />
                            <SecondaryButton
                                @click=" showSupplierForm = true"
                                type="button">
                                Agre.
                            </SecondaryButton>
                        </div>

                        <!-- Error -->
                        <InputError :message="form.errors.search" />
                    </div>

                    <!-- Botones -->
                    <div class="mt-4 text-right">
                        <PrimaryButton
                            :disabled="form.processing">
                            Registrar
                        </PrimaryButton>
                    </div>
                </form>

            </ContentBox>

            <Transition>
                <!-- Formulario para Agregar el suplidor -->
                <FloatBox
                    v-if="showSupplierForm"
                    @close=" showSupplierForm = !showSupplierForm "  >
                    <Float
                        />

                </FloatBox>
            </Transition>
        </div>
    </AppLayout>

</template>
