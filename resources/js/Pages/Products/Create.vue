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
import { ref } from 'vue';


// Datos del formulario
const form = useForm({
    name: "",
    description:"",
    unit: ""
});


// Cons datos de la ventana
const showSupplierForm = ref(false);


// Funciones
const submit = () => {
    // Formulario para guardar los productos
    form.post(route('product.store'),{
        onSuccess:()=>{
            // Datos de la alerta
            successHttp('Datos registrado correctamente');
        }
    })
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



                </template>

            </HeaderBox>
        </template>

        <!-- Contenido de la ventana de los productos -->
        <div>
            <ContentBox>
                <form @submit.prevent="submit" >
                    <!-- Nombre -->
                    <div>
                        <InputLabel
                            for="name"
                            value="Nombre"/>
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
                            value="Nombre"/>
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
                            for="name"
                            value="Proveedor"/>

                        <div class=" flex space-x-3">
                            <select
                                v-model="form.unit"
                                class=" w-full border-gray-300 rounded-md ">
                                <option selected disabled value="">
                                    --- Seleccione el Proveedor ---
                                </option>
                                <option value="ONZA">OZ</option>
                            </select>
                            <SecondaryButton
                                @click=" showSupplierForm = true"
                                type="button">
                                Agre.
                            </SecondaryButton>
                        </div>

                        <!-- Error -->
                        <InputError :message="form.errors.name" />
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


            <!-- Formulario para Agregar el suplidor -->
            <FloatBox
                v-if="showSupplierForm"
                @close=" showSupplierForm = !showSupplierForm "  >
                <Float/>

            </FloatBox>
        </div>
    </AppLayout>

</template>
