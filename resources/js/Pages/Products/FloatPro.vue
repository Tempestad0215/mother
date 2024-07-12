<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { successHttp } from '@/Global/Alert';
import { proSupResI } from '@/Interfaces/Product';
import { supplierI } from '@/Interfaces/Supplier';
import InputSelect from '@components/InputSelect.vue';
import SecondaryButton from '@components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { onMounted, PropType, ref } from 'vue';




const props = defineProps({
    productEdit: {
        type: Object as PropType<proSupResI>,
    },
    update: {
        type: Boolean
    }
});


const emit = defineEmits(['showSupplier']);


// Datos del formulario
const form = useForm({
    id: 0,
    name: "",
    description: "",
    unit: "",
    supplier_id:0,
    search:"",
});

// Cons datos de la ventana
ref(false);
const dataSelect = ref<supplierI[]>([]);

onMounted(()=>{
    // actualizar los suplidores
    getSupplier();
    // Pasar los datos a editar
    if(props.productEdit?.data)
    {
        form.id = props.productEdit.data.id
        form.name = props.productEdit.data.name
        form.description = <string>props.productEdit.data.description
        form.unit = props.productEdit.data.unit
        form.supplier_id = props.productEdit.data.supplier.id
        form.search = props.productEdit.data.supplier.name
    }
})



// Funciones
const submit = () => {

    if(props.update)
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
    <div  >
        <form
            @submit.prevent="submit" >
            <h3 class="text-2xl font-bold text-center">
                Registro de producto
            </h3>
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
                        :info="dataSelect"
                        @send-value="getValueSelect"
                        @update-data="getSupplier()"
                        v-model="form.search"
                        class=" w-full" />
                    <SecondaryButton
                        @click="emit('showSupplier')"
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
    </div>


</template>
