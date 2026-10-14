// Composables/shared/useNotify.ts
import { useToast } from 'primevue';

export function useNotify() {
  const toast = useToast();

  const success = (detail: string, summary = 'Éxito') => {
    toast.add({ severity: 'success', summary, detail, life: 3000 });
  };

  const error = (detail: string, summary = 'Error') => {
    toast.add({ severity: 'error', summary, detail, life: 3500 });
  };

  const warn = (detail: string, summary = 'Advertencia') => {
    toast.add({ severity: 'warn', summary, detail, life: 3500 });
  };

  const info = (detail: string, summary = 'Información') => {
    toast.add({ severity: 'info', summary, detail, life: 3000 });
  };

  return { success, error, warn, info };
}
