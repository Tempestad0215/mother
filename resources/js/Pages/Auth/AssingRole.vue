<script setup lang="ts">
import AppLayout from '@layout/AppLayout.vue';
import { Card, FloatLabel, Select, MultiSelect, Button, useToast } from 'primevue';
import BreadCrumbComponent from '@components/BreadCrumbComponent.vue';
import { itemsSettings } from '@/Helpers/SettingHelpers';
import { RoleI, UserRoleI } from '@/Interfaces/UserInterface';
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';

const toast = useToast();

const propsW = defineProps<{
  users: Array<UserRoleI>;
  roles: Array<RoleI>;
}>();

const form = useForm({
  user_uuid: '',
  roles: [] as string[] | number[],
  update: false,
});

watch(
  () => form.user_uuid,
  (newUserUuuid: string) => {
    if (!newUserUuuid) {
      form.roles = [];
      return;
    }

    const selectedUser = propsW.users.find((u) => u.uuid === newUserUuuid);

    if (selectedUser && selectedUser.roles) {
      form.roles = selectedUser.roles.map((r) => r.uuid);
    } else {
      form.roles = [];
    }
  }
);

const submit = () => {
  form.patch(route('user.assing.role.post', { user: form.user_uuid }), {
    onSuccess: (data) => {
      toast.add({
        severity: 'success',
        summary: 'Existo',
        detail: data.flash.success,
        life: 3000,
      });
    },
    onError: () => {
      toast.add({
        severity: 'error',
        summary: 'Fallo al intentar asignar roles',
      });
    },
  });
};
</script>

<template>
  <AppLayout>
    <Card>
      <template #title>
        <BreadCrumbComponent :item-options="itemsSettings" />
      </template>
      <template #content>
        <form @submit.prevent="submit()" class="p-5">
          <div class="text-2xl font-bold text-center">
            <h2>Asignacion de Roles</h2>
          </div>
          <div class="my-5">
            <FloatLabel variant="on">
              <Select
                v-model="form.user_uuid"
                :options="propsW.users"
                optionLabel="name"
                optionValue="uuid"
                checkmark
                filter
                fluid
              />
              <label for="user">Usuario</label>
            </FloatLabel>
          </div>
          <div>
            <FloatLabel variant="on">
              <MultiSelect
                multiple
                checkmark
                optionLabel="name"
                optionValue="uuid"
                showClear
                v-model="form.roles"
                fluid
                :options="propsW.roles"
              />
              <label for="roles">Roles</label>
            </FloatLabel>
          </div>
          <div class="mt-5 text-right">
            <Button type="submit" label="Registrar" />
          </div>
        </form>
      </template>
    </Card>
  </AppLayout>
</template>

<style scoped></style>
