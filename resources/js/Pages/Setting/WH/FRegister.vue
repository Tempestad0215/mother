<script setup lang="ts">
import {useForm} from "@inertiajs/vue3";
import InputLabel from "@components/InputLabel.vue";
import TextInput from "@components/TextInput.vue";
import InputError from "@components/InputError.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {warehouseBaseI} from "@/Interfaces/WarehouseInterface";
import {watch} from "vue";
import {useRoute} from "ziggy-js";


const route = useRoute();

/**
 * Datos de la ventanan
 */
// const type = ref(['ACTIVO','PASIVO','INGRESO','GASTO','CAPITAL']);

const propsW = defineProps<{
    editWareHouse?: warehouseBaseI;
}>()
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




watch(
    () => propsW.editWareHouse,
    (newValue) => {
        if (newValue) {
            form.id = newValue.id;
            form.name = newValue.name;
            form.description = newValue.description;
            form.location = newValue.location;
            form.update = true;
        }
    }
)

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
                form.reset()
            }
        });
    }else{
        form.post(route('wh.store'),{
            onSuccess: () => {
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
                {{ form.update ? "Actualizar" :  "Registar" }}
            </PrimaryButton>
        </div>
    </form>
</template>

