<script setup lang="ts">
import {useForm} from "@inertiajs/vue3";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import InputError from "@components/InputError.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {successHttp} from "@/Global/Alert";



/**
 * Datos de la ventanan
 */
// const type = ref(['ACTIVO','PASIVO','INGRESO','GASTO','CAPITAL']);

/**
 * Formularios
 */
const form = useForm({
    id:0,
    name:"",
    description:"",
    location:"",
    update: false,
});



/*
funciones
 */
/**
 * Enviar los datos
 */
const submit = () => {
    if (form.update) {
        form.put(route('wh.update',{wh: form.id}),{
            onSuccess: () => {
                successHttp('Datos Actualizado Correctamente');
            }
        });
    }else{
        form.post(route('wh.store'),{
            onSuccess: () => {
                successHttp('Datos Registrado Correctamente');
                form.reset();
            }
        });
    }
}
</script>

<template>
    <form
        @submit.prevent="submit"
        class="grid grid-cols-3 gap-3 fondo p-5 rounded-md w-[50rem]">
        <h3
            class="title text-center col-span-full">
            Almacenes
        </h3>
        <!--                codigo-->
        <div>
            <InputLabel
                for="name"
                value="Nombre"/>
            <TextInput
                placeholder="Nombre"
                class="w-full"
                v-model="form.name"
            />
            <InputError :message="form.errors?.name" />
        </div>
        <!--                codigo-->
        <div>
            <InputLabel
                for="description"
                value="Descripcion"/>
            <TextInput
                placeholder="Descripcion"
                class="w-full"
                v-model="form.description"
            />
            <InputError :message="form.errors?.description" />
        </div>
        <!--                codigo-->
        <div>
            <InputLabel
                for="location"
                value="Ubicacion"/>
            <TextInput
                placeholder="Ubicacion"
                class="w-full"
                v-model="form.location"
            />
            <InputError :message="form.errors?.location" />
        </div>
        <!--                Botones para enviar-->
        <div class="col-span-full text-right">
            <PrimaryButton>
                Registar
            </PrimaryButton>
        </div>
    </form>
</template>

