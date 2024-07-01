<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import HeaderBox from '@/Components/HeaderBox.vue';
import NavLink from '@/Components/NavLink.vue';
import ContentBox from '@/Components/ContentBox.vue';
import ActionMessage from '@/Components/ActionMessage.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 1,
    terms: false,
});

const submit = () => {
    form.post(route('user.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Registro" />

    <AppLayout>
        <template #header >
            <HeaderBox>
                <h2>
                    Registro de usuario
                </h2>
                <!-- Links de navegacion -->
                <div>
                    <NavLink
                        :href="route('profile.show')">
                        Perfil
                    </NavLink>
                    <NavLink
                        :href="route('register')">
                        Registrar
                    </NavLink>
                </div>

            </HeaderBox>
        </template>

            <!-- Contenido de la ventana -->
            <ContentBox>

                <form
                    @submit.prevent="submit">
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

                    <div class="mt-4">
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

                    <div class="mt-4">
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

                    <div class="mt-4">
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
                    <div class="mt-4">
                        <InputLabel for="role" value="Rol *"  />

                        <div class=" flex justify-between">
                            <!-- Usuarios -->
                            <div>
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
                            <div>
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
                            <div>
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

                        <!-- Error de todo -->
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>

                    <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
                        <InputLabel for="terms">
                            <div class="flex items-center">
                                <Checkbox id="terms" v-model:checked="form.terms" name="terms" required />

                                <div class="ms-2">
                                    I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy Policy</a>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors.terms" />
                        </InputLabel>
                    </div>

                    <div
                        class="flex items-center justify-end mt-4">
                        <ActionMessage
                            :on="form.recentlySuccessful">
                            Usuario registrado correctamente
                        </ActionMessage>
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Registrar
                        </PrimaryButton>
                    </div>
                </form>

            </ContentBox>

    </AppLayout>

</template>
