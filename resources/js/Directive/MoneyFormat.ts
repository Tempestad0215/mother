// src/directives/moneyFormat.js
import { ref } from 'vue';

export default {
  beforeMount(el:HTMLInputElement) {
    el.value = formatMoney(el.value);
    el.addEventListener('input', onInput);
  },
  updated(el:HTMLInputElement) {
    el.value = formatMoney(el.value);
  },
  unmounted(el:HTMLInputElement) {
    el.removeEventListener('input', onInput);
  }
}

function onInput(event:Event) {
  (event.target as HTMLInputElement ).value = formatMoney((event.target as HTMLInputElement).value);
}

function formatMoney(value:string) {
  if (!value) return '';

  // Remove any non-digit characters
  const numberString = value.replace(/[^\d.-]/g, '');
  const number = parseFloat(numberString);

  if (isNaN(number)) return '';

  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(number);
}
