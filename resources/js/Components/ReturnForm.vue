<script setup lang="ts">

import TextInput from "@components/TextInput.vue";
import InputLabel from "@components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@components/InputError.vue";
import PrimaryButton from "@components/PrimaryButton.vue";
import {ref} from "vue";
/*
Propiedades de la ventana
 */
const propsW = defineProps<{
    error?: string
}>();



/*
fomulario
 */
const form = useForm({
    type:true,
    saleCode: "",
    general:""
});


/*
Enviar el evento para emitir
 */
const emit = defineEmits<{
    (e:'closeFormReturn'): void;
    (e:'hasError'):void;
}>();



/*
Data de la ventana
 */
const options = ref([
    {
        name: 'Consultar',
        value: true,
    },
    {
        name: 'Seleccionar',
        value: false,
    }
]);



/*
Funciones
 */
const submit = () => {

    if (form.type)
    {

    }else{
        //Enviar los datos
        form.get(route('credit-note.index'),{
            preserveState: true,
            onError:() => {
                emit('hasError');
            },
            onSuccess: () => {
                emit('closeFormReturn');
            }
        });
    }

}

</script>

<template>
    <div class="bg-blue-300 p-5 rounded-lg">
        <h3
            class="title">
            Formulario Para Devolución
        </h3>
        <form
            @submit.prevent="submit">

<!--            Si es consulta o para selccionar-->
            <div class="mt-5">
<!--                Titulo-->
                <InputLabel
                    class="flex"
                    for="askReturn" value="Tipo de Consulta" />
                <div class="flex justify-center">
                    <select
                        class="inputGeneral py-1 w-full"
                        v-model="form.type"
                        name="askReturn"
                        id="askReturn">
                        <option
                            v-for="(item, index) in options"
                            :key="index"
                            :value="item.value">
                            {{item.name}}
                        </option>
                    </select>
                </div>

            </div>

            <div
                class="mt-5">
                <!--           Etiqueta de la ventana-->
                <InputLabel
                    for="invoiceReturn"
                    value="Codigo de Fáctura"/>
                <!--            Entrada de texto-->
                <TextInput
                    class="w-full"
                    v-model="form.saleCode"
                />

<!--         Mensaje de error       -->
                <InputError :message="form.errors.saleCode"/>
            </div>

<!--            MEnasje General-->
            <div>
                <InputError :message="propsW.error"/>
            </div>

<!--            Boton de enviar-->
            <div
                class="mt-5 text-right ">
                <PrimaryButton
                    @click="submit">
                    Buscar
                </PrimaryButton>
            </div>

        </form>
    </div>
</template>
