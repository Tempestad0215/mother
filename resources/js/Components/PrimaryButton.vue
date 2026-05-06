<script setup>
defineProps({
  type: {
    type: String,
    default: 'submit',
  },
});
</script>

<template>
  <button
    :type="type"
    class="inline-flex items-center px-4 py-1 h-[2rem] bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-600 active:bg-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150"
  >
    <slot />
  </button>
</template>
