<script setup lang="ts">

import InputLabel from "@components/InputLabel.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import InputError from "@components/InputError.vue";
import TextInput from "@components/TextInput.vue";
import {useForm} from "@inertiajs/vue3";
import {categoryBaseI} from "@/Interfaces/CategoriesInterface";
import {onMounted} from "vue";
import {useRoute} from "ziggy-js";


const route = useRoute()

const propsW = defineProps<{
    categoryEdit?: categoryBaseI,
    update?: boolean
}>();



/*
Formularios
 */
const form = useForm({
    id: 0,
    name:"",
    description:"",
});


// Al momento de cargar
onMounted(()=>{
   if (propsW.categoryEdit)
   {
       form.id = propsW.categoryEdit.id;
       form.name = propsW.categoryEdit.name;
       form.description = propsW.categoryEdit.description || '';
   }
});


// Funciones
const submit = () => {
    //si es para actualizar
    if(propsW.update)
    {
        form.patch(route('category.update',{category: form.id}),{
            onSuccess: ()=>{

            }
        })
    }else {
        form.post(route('category.store'),{
            onSuccess:()=>{

                form.reset();
            }
        });
    }
}


</script>

<template>
    <form
        @submit.prevent="submit"
        class="fondo max-w-5xl mx-auto rounded-md p-5 grid grid-cols-2 gap-3">
        <h3 class=" text-2xl font-bold col-span-full text-center">
            Registro de Categoria
        </h3>
        <div class="mt-4">
            <input-label
                for="name"
                value="Nombre *" />
            <text-input
                class="w-full"
                name="name"
                maxLength="75"
                v-model="form.name"
                placeholder="Nombre"/>
            <input-error
                :message="form.errors.name"/>
        </div>

        <div class="mt-4">
            <input-label
                for="description"
                value="Descripción" />
            <text-input
                class="w-full"
                name="description"
                maxLength="255"
                v-model="form.description"
                placeholder="Describe brevemente"/>
            <input-error
                :message="form.errors.description"/>
        </div>

        <div class="mt-4 text-right col-span-full">
            <primary-button
                :disabled="form.processing">
                Registrar
            </primary-button>
        </div>

    </form>
</template>

<style scoped>

</style>
