// src/directives/moneyFormat.js

export default {
  beforeMount(el) {
    el.value = formatMoney(el.value);
    el.addEventListener('input', onInput);
  },
  updated(el) {
    el.value = formatMoney(el.value);
  },
  unmounted(el) {
    el.removeEventListener('input', onInput);
  }
}

function onInput(event) {
  (event.target).value = formatMoney((event.target).value);
}

function formatMoney(value) {
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
