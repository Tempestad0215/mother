<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Input } from '@components/ui/input';
import { Label } from '@components/ui/label';
import PasswordInput from '@components/PasswordInput.vue';
import PasswordController from '@/actions/Laravel/Fortify/Http/Controllers/PasswordController';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(PasswordController.update(), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }

            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <FormSection @submitted="updatePassword">
        <template #title> Actualizar contraseña </template>

        <template #description>
            Asegúrate de que tu cuenta utiliza una contraseña larga y aleatoria para mantener la
            seguridad.
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4 space-y-2">
                <Label for="current_password">Contraseña actual</Label>
                <PasswordInput
                    name="current_password"
                    id="current_password"
                    ref="currentPasswordInput"
                    autocomplete="current-password"
                    v-model="form.current_password"
                />
                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4 space-y-2">
                <Label for="current_password">Nueva actual</Label>

                <PasswordInput
                    v-model="form.password"
                    name="password"
                    id="password"
                    autocomplete="new-password"
                />
                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="col-span-6 sm:col-span-4 space-y-2">
                <Label for="password_confirmation">Confirmar Contraseña </Label>
                <PasswordInput
                    name="password_confirmation"
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                />
                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <ActionMessage :on="form.recentlySuccessful" class="me-3"> Guardado. </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Guardar
            </PrimaryButton>
        </template>
    </FormSection>
</template>
