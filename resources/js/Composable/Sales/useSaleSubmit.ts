import { inject } from 'vue';
import { useToast } from 'primevue';
import { saleKey } from '@/Injections/saleKeys';

export interface UseSaleSubmitOptions {
  createCreditNotes: () => void;
  onSuccess?: () => void;
}

export function useSaleSubmit(options: UseSaleSubmitOptions) {
  const form = inject(saleKey)!;
  const toast = useToast();

  const handleSuccess = (summary: string, detail: string) => {
    toast.add({ summary, detail, severity: 'success', life: 3000 });
    form.reset();
    options.onSuccess?.();
  };

  const handleError = (err: Record<string, string>) => {
    toast.add({
      summary: 'Error',
      detail: Object.values(err)[0],
      severity: 'error',
      life: 3500,
    });
  };

  const createSale = () => {
    form.post(route('sale.store'), {
      onSuccess: () => handleSuccess('Registro Creado', 'Venta procesada correctamente.'),
      onError: handleError,
    });
  };

  const updateSale = () => {
    form.patch(route('sale.update', { sale: form.uuid }), {
      onSuccess: () => handleSuccess('Registro Actualizado', 'Venta actualizada correctamente.'),
      onError: handleError,
    });
  };

  const sendData = () => {
    if (form.pending > 0) {
      toast.add({
        summary: 'Advertencia',
        detail: 'Verifique el monto pendiente antes de continuar.',
        severity: 'warn',
        life: 3500,
      });
      return;
    }

    if (form.type === 'DEVOLUCION' && form.info_sale.length > 0) {
      options.createCreditNotes();
      return;
    }

    form.update ? updateSale() : createSale();
  };

  return { sendData, createSale, updateSale };
}
