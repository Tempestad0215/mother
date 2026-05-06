<script setup>
import { onMounted, ref, useAttrs } from 'vue';

defineProps({
  modelValue: String | Number,
});

defineEmits(['update:modelValue']);
const attrs = useAttrs();

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
  <input
    ref="input"
    class="inputGeneral"
    :value="modelValue"
    v-bind="attrs"
    autocomplete="off"
    @input="$emit('update:modelValue', $event.target.value)"
  />
</template>
