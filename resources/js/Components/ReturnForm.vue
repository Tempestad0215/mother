<script setup lang="ts">

import TextInput from "@components/TextInput.vue";
import InputLabel from "@components/InputLabel.vue";
import {useForm} from "@inertiajs/vue3";
import InputError from "@components/InputError.vue";
import PrimaryButton from "@components/PrimaryButton.vue";

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
}>()



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
    <div class="bg-gray-200 p-5 rounded-lg">
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

                <div class="flex flex-row justify-center">

                    <!--                Consulta-->
                    <div class="flex">
                        <input
                            class="peer hidden"
                            :value="true"
                            v-model="form.type"
                            name="consulting"
                            id="consulting"
                            type="radio">
                        <InputLabel
                            class=" font-bold rounded-md border-2 border-gray-800 px-2 peer-checked:text-white peer-checked:bg-gray-900 duration-300"
                            for="consulting"
                            value="Consultar"/>
                    </div>
                    <!--                Consulta-->
                    <div class="flex ml-5">
                        <input
                            class="peer hidden"
                            :value="false"
                            v-model="form.type"
                            name="searchReturn"
                            id="searchReturn"
                            type="radio">
                        <InputLabel
                            class=" font-bold rounded-md border-2 border-gray-800 px-2 peer-checked:text-white peer-checked:bg-gray-900 duration-300"
                            for="searchReturn"
                            value="Seleccionar"/>
                    </div>
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
