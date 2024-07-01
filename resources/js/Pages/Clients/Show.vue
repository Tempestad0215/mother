<script setup lang="ts">

import ContentBox from '@/Components/ContentBox.vue';
import HeaderBox from '@/Components/HeaderBox.vue';
import InputLabel from '@/Components/InputLabel.vue';
import NavLink from '@/Components/NavLink.vue';
import TextInput from '@/Components/TextInput.vue';
import { clientI } from '@/Interfaces/ClientInterface';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { PropType } from 'vue';

// Datos del backend
const props = defineProps({
    clients: {
        type: Object as PropType<clientI>,
        required: true
    }
});


// Formulario
const form = useForm({
    search:""
});


// Enviar los datos
const submit = () => {

    if(form.search.length < 2)
    {
        form.setError('search', 'El campo debes tener al menos 3 caracter');
        return false;
    }else{
        // Limpiar los errores
        form.clearErrors();
        // Enviar el formularios
        form.get(route('client.show'),{
            preserveScroll: true,
            preserveState: true
        });
    }

}

// Editar
const edit = (id:number) => {

    // Hacer la peticion
    router.get(route('client.edit', id));
}

// Eliminar el resistros
const destroy = (id:number) => {

    // Enviar los datos

}



</script>


<template>
    <!-- Titulo -->
    <Head title="Cliente"/>

    <!-- Contenido de todo -->
    <AppLayout>
        <!-- Cabecera de la ventana -->
        <template #header>
            <!-- Cabecera -->
            <HeaderBox>
                <h2>
                    Mostrar Cliente
                </h2>

                <!-- Links -->
                <div>
                    <NavLink
                        :href="route('client.create')" >
                        Registrar
                    </NavLink>
                    <NavLink
                        :href="route('client.create')" >
                        Registrar
                    </NavLink>
                </div>
            </HeaderBox>

        </template>

        <!-- Contenido principal -->
        <div>
            <ContentBox class="md:max-w-full mx-5" >
                <div class=" mb-4">
                    <form
                        @submit.prevent="submit"
                        class=" max-w-sm">
                        <InputLabel
                            for="search"
                            value="Buscar" />
                        <TextInput
                            class=" w-full"
                            v-model="form.search"
                            type="text"/>

                    </form>
                </div>

                <table class=" table-auto w-full">
                    <thead class=" border-b-2 text-left">
                        <tr class=" border-b-2">
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Telefono</th>
                            <th>Atc</th>

                        </tr>
                    </thead>

                    <!-- Contenido -->
                    <tbody>
                        <tr v-for="(item, index) in props.clients.data" :key="index" >
                            <td>
                                {{ item.id }}
                            </td>
                            <td>
                                {{item.name}}
                            </td>
                            <td>
                                {{item.email ? item.email : 'N/A'}}
                            </td>
                            <td>
                                {{ item.phone }}
                            </td>
                            <!-- Botones -->
                            <td class="text-xl space-x-5 w-[100px]">
                                <i
                                    @click="edit(item.id)"
                                    class="fa-solid fa-pen-to-square"></i>
                                <i
                                    @click="destroy(item.id)"
                                    class="fa-solid fa-trash"></i>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </ContentBox>

        </div>
    </AppLayout>


</template>
