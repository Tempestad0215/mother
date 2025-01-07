<script setup lang="ts">
import {Head, router, useForm} from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import {userI, userPaginationI} from "@/Interfaces/User";
import {computed, ref, Ref} from "vue";
import Swal from "sweetalert2";
import {successHttp} from "@/Global/Alert";
import FormSearch from "@components/FormSearch.vue";
import Pagination from "@components/Pagination.vue";
import {paginationJoin} from "@/Global/Helpers";
import ToggleButton from "@components/ToggleButton.vue";


/*
Propiedad de la ventana
 */
defineProps<{
    users: userPaginationI
}>();

/*
 * Datos del formulario
 */
const form = useForm({
    id: 0,
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'user',
    terms: false,
    update: false,
    modify_password: false
});


/*
Formulario de busqueda
 */
const formSearch = useForm({
    search:"",
    perPage: 15
});

/*
Datos d la ventana
 */
const role:Ref<any[]> = ref([
    {
        name: 'user',
        label: 'USER',
    },
    {
        name: 'supervisor',
        label: 'SUPERVIOR',
    },
    {
        name: 'admin',
        label: 'ADMIN',
    }
]);




/*
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
   else return !form.update;
});


/*
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

    //Pasar los datos al formulario
    form.id = item.id;
    form.name = item.name;
    form.email = item.email;
    form.role = item.role;

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
            class="bg-blue-300 max-w-[70rem] p-5 rounded-md grid grid-cols-2 gap-3"
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

            <!--        Preguntar si desea cambiar la password-->
            <div
                v-if="form.update"
                class="flex-1">
                <InputLabel for="modifyPassoword" value="Modificar Contraseña" />
                <ToggleButton
                    label="Modificar Password"
                    on-label="SI"
                    v-model="form.modify_password"
                    off-label="NO"/>

            </div>

            <!-- Rol de usuarios -->
            <div class="flex-1">
                <InputLabel for="role" value="Rol *"  />
                <select
                    class="inputGeneral py-1 w-full"
                    v-model="form.role"
                    name="role"
                    id="role">
                    <option
                        v-for="(item, index) in role"
                        :key="index"
                        :value="item.name">
                        {{ item.label}}
                    </option>
                </select>

                <!-- Mensaje de error -->
                <InputError class="mt-2" :message="form.errors.role" />
            </div>

<!--            Contrase;a-->
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
<!--            confirma passwod-->
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

<!--            Botones-->
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

        <div class=" rounded-md p-5 bg-blue-300 mt-3">
            <form

                @submit.prevent="search">
                <FormSearch
                    class=""
                    holder="Buscar"
                    v-model:select-value="formSearch.perPage"
                    v-model="formSearch.search"/>
            </form>
            <table
                class="w-full table-auto styleTable mt-3">
                <thead
                    class=" sticky top-0">
                    <tr
                        class="">
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Atc</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        class=""
                        v-for="(item, index) in users.data" :key="index">
                        <td>{{item.id}}</td>
                        <td>{{item.name}}</td>
                        <td>{{item.email}}</td>
                        <td class="uppercase">{{item.role}}</td>
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
                :prev="users.links.prev
                    ? paginationJoin(users.links.prev, formSearch.search, formSearch.perPage)
                    : ''"
                :next="users.links.next
                    ? paginationJoin(users.links.next, formSearch.search, formSearch.perPage)
                    : ''"
                :current-page="users.meta.current_page "/>
        </div>





    </AppLayout>

</template>
