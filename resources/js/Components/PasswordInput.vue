<script setup lang="ts">
import { Eye, EyeOff } from '@lucide/vue';
import { InputGroup, InputGroupAddon, InputGroupInput } from './ui/input-group';
import { ref } from 'vue';
import { Button } from '@components/ui/button';

const showPassword = ref(false)

const togglePassword = () => {
  showPassword.value = !showPassword.value;
}

</script>

<template>
  <InputGroup>
    <InputGroupAddon  align="inline-end">
      <Button type="button" variant="ghost" :arial_label="showPassword ? 'Ocultar Password' : 'Mostrar Password'" @click="togglePassword" >
        <EyeOff v-if="showPassword" />
        <Eye v-else />
      </Button>
    </InputGroupAddon>
    <InputGroupInput v-bind="$attrs"  :type="showPassword ? 'text' : 'password'"  />
  </InputGroup>

</template>
