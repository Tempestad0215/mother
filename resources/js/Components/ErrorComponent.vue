<script setup lang="ts">
import { computed, watch } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faClose } from '@fortawesome/free-solid-svg-icons';

const errors = defineModel<Object>('errors', {
  default: {},
});

const hasError = computed(() => {
  if (!errors.value || Object.keys(errors.value).length === 0) {
    return null;
  }

  const [firstKey, firstValue] = Object.entries(errors.value)[0];
  return `El campo "${firstKey}": ${firstValue}`;
});
</script>

<template>
  <Transition>
    <div
      v-if="hasError"
      class="relative max-w-2xl mx-auto text-center mt-3 bg-red-200 px-5 rounded-md py-2 shadow-md"
    >
      {{ hasError }}
      <FontAwesomeIcon
        @click="errors = {}"
        class="absolute top-[-0.5rem] right-[-1rem] py-2 px-3 bg-white rounded-full hover:bg-red-600 hover:text-red-100 duration-200"
        :icon="faClose"
      />
    </div>
  </Transition>
</template>

<style scoped></style>
