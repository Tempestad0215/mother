<script setup lang="ts">
import { Form, Link, useForm } from '@inertiajs/vue3';
import { ref, Ref } from 'vue';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import Button from '@/Components/ui/button/Button.vue';
import { Send } from '@lucide/vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Switch } from '@/Components/ui/switch';
import InputError from '@/Components/InputError.vue';
import { store } from '@/actions/Laravel/Fortify/Http/Controllers/AuthenticatedSessionController';
import { request } from '@/routes/password';
import PasswordInput from '@components/PasswordInput.vue';

/**
 * Propiedades de la ventna
 */
defineProps<{
  canResetPassword: Boolean;
  status: String;
}>();



</script>

<template>
  <div >
    <div v-if="status" class="mb-4 font-medium text-sm">
      {{ status }}
    </div>
    <Card class="w-full max-w-xl mx-auto">
      <CardHeader>
        <CardTitle>Inicio de usuario</CardTitle>
        <CardDescription></CardDescription>
      </CardHeader>
      <CardContent>
        <Form :action="store()" class="max-w-xl space-y-3" #default="{ processing, errors }">
          <div class="space-y-2">
            <Label for="email">Correo electronico</Label>
            <Input placeholder="Correo electronico" name="email" />
            <InputError :message="errors.email" />
          </div>
          <div class="space-y-2">
            <Label for="password">Contraseña</Label>
            <PasswordInput name="password" placeholder="Password" />
            <InputError :message="errors.password" />
          </div>
          <div class="mt-5 flex justify-between items-center">
            <div class="flex gap-3">
              <Switch name="rememberme" />
              <Label>Recuerdame</Label>
            </div>
            <div>
              <Link
                v-if="canResetPassword"
                :href="request()"
                class="underline text-sm text-blue-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                Olvidó su contraseña?
              </Link>
            </div>
          </div>
          <div class="mt-5 text-right">
            <Button :disable="processing"> <Send /> Iniciar</Button>
          </div>
        </Form>
      </CardContent>
    </Card>
  </div>
</template>
