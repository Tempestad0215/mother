<script setup lang="ts">
import { Fieldset } from 'primevue';
import { inject } from 'vue';
import { formProductKey } from '@/Injections/InjectionKeys';
import { PreciseCalculator } from '@/utils/Decimal';

const form = inject(formProductKey)!!;
</script>

<template></template>
