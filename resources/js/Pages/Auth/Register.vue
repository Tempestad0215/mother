<script setup lang="ts">
import {Head, router, useForm} from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import {userI, userPaginationI} from "@/Interfaces/User";
import {computed, ref} from "vue";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";
import FormSearch from "@components/FormSearch.vue";
import Pagination from "@components/Pagination.vue";

defineProps<{
    users: userPaginationI
}>()


/**
 * Datos del formulario
 */
const form = useForm({
    id: 0,
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 1,
    terms: false,
    update: false,
    modify_password: false
});
//Datos de busqueda
const formSearch = useForm({
    search:""
})


/**
 * Datos de la ventana
 */
const showConfirm = ref<boolean>(true);


/**
 * Propiedades computada
 */
const showPassword = computed(()=>{
   if(form.update && !form.modify_password )
   {
       return false;
   }else if(!form.update && form.modify_password)
   {
       return false;
   }else if(form.update && form.modify_password)
   {
       return  true;
   }
   else if(!form.update)
   {
       return true
   }else{
       return false;
   }
});


/**
 * Enviar los datos
 */
const submit = () => {
    if(form.update)
    {
        form.patch(route('user.update',{user: form.id}),{
            onSuccess: () => {
                successHttp('Datos Actualizado Correctamente');
            }
        })
    }else{
        form.post(route('user.store'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
            onSuccess: () => form.reset(),
        });
    }


};

// editar los datos
const edit = (item:userI) => {

    //Para cambiar la version
    let role:number = 1;

    //Formatear los datos
    switch (item.role) {
        case 'USER':
            role = 1;
            break;
        case 'SUPERVISOR':
            role = 2;
            break;
        case 'ADMINFULL':
            role = 3;
            break;
    }

    //Pasar los datos al formulario
    form.id = item.id;
    form.name = item.name;
    form.email = item.email;
    form.role = role;

    //Poner el formulario en actualizar
    form.update = true;
}


// Eliminar los datos
const destroy = (item:userI) => {
    Swal.fire({
        title: "Desea eliminar este registro?",
        text: "Los cambios realizados son irreversible!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, Eliminar!"
    }).then((result) => {
        if (result.isConfirmed) {
            router.patch(route('user.destroy',{user: item.id}),{},{
                onSuccess: () => {
                    successHttp('Datos eliminado correctamente');
                }
            });
        }
    });
}

//Buscar los datos
const search = () => {
    formSearch.get('',{
        preserveState: true,
        preserveScroll: true
    });
}

</script>

<template>
    <Head title="Usuario" />

    <AppLayout>
        <template #header >

        </template>

        <form
            class="bg-gray-200 p-5 rounded-md grid grid-cols-2 gap-3"
            @submit.prevent="submit">

            <h3 class="text-3xl font-bold text-center col-span-full ">
                Registro de Usuario
            </h3>

            <div>
                <InputLabel for="name" value="Nombre *" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="">
                <InputLabel for="email" value="Correo *" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>


            <fieldset
                v-if="form.update"
                class="col-span-full flex">
                <legend>
                    Modificar Contraseña
                </legend>
                <div>
                    <input
                        v-model="form.modify_password"
                        class=""
                        type="radio"
                        :value="false"
                        name="no"
                        id="no">
                    <InputLabel
                         class="inline" for="no" value="No"/>
                </div>
                <div class="ml-10">
                    <input
                        v-model="form.modify_password"
                        type="radio"
                        :value="true"
                        name="si"
                        id="si">
                    <InputLabel class="inline" for="si" value="Si"/>
                </div>
            </fieldset>


            <div
                v-if="showPassword "
                class="">
                <InputLabel for="password" value="Contraseña *" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div
                v-if="showPassword "
                class="">
                <InputLabel for="password_confirmation" value="Confirmar contraseña *" />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>



            <!-- Rol de usuarios -->
            <div class=" col-span-full text-center ">
                <InputLabel for="role" value="Rol *"  />

                <div class="  space-x-5">
                    <!-- Usuarios -->
                    <div class="inline">
                        <input
                            id="user"
                            name="user"
                            :value="1"
                            v-model="form.role"
                            type="radio">
                        <label for="user">
                            Usuario
                        </label>
                    </div>


                    <!-- Admin -->
                    <div class="inline">
                        <input
                            id="supervisor"
                            name="supervisor"
                            :value="2"
                            v-model="form.role"
                            type="radio">
                        <label for="supervisor">
                            Supervisor
                        </label>
                    </div>


                    <!-- Adminfill -->
                    <div class="inline">
                        <input
                            id="admin"
                            name="admin"
                            :value="3"
                            v-model="form.role"
                            type="radio">
                        <label for="admin">
                            Administrador
                        </label>
                    </div>
                </div>

                <!-- Mensaje de error -->
                <InputError class="mt-2" :message="form.errors.role" />
            </div>


            <div
                class=" col-span-full flex items-center justify-end mt-4">
                <ActionMessage
                    :on="form.recentlySuccessful">
                    Usuario registrado correctamente
                </ActionMessage>
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Registrar
                </PrimaryButton>
            </div>
        </form>


        <div class="mt-5 bg-gray-200 p-5 rounded-md">
            <form
                @submit.prevent="search">
                <FormSearch
                    v-model="formSearch.search"/>
            </form>
            <table
                class="w-full table-auto  rounded-md">
                <thead class="border-b-2 border-gray-400">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Atc</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    class="odd:bg-gray-400"
                    v-for="(item, index) in users.data" :key="index">
                    <td>{{item.id}}</td>
                    <td>{{item.name}}</td>
                    <td>{{item.email}}</td>
                    <td>{{item.role}}</td>
                    <td class="space-x-4">
                        <i
                            @click="edit(item)"
                            class="icon-efect fa-solid fa-pen-to-square"></i>

                        <i
                            @click="destroy(item)"
                            class=" icon-efect fa-solid fa-trash"></i>
                    </td>
                </tr>
                </tbody>
            </table>

            <Pagination
                :total-page="users.meta.to"
                :prev="users.links.prev"
                :next="users.links.next"
                :current-page="users.meta.current_page "/>
        </div>





    </AppLayout>

</template>
