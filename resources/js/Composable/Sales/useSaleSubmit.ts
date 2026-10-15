import { saleKey } from '@/Injections/saleKeys';
import { useNotify } from '@/Composable/Shared/useNotify';
import { InertiaForm } from '@inertiajs/vue3';
import { CreateSaleI } from '@/Interfaces/SaleInterface';

export interface UseSaleSubmitOptions {
  createCreditNotes: () => void;
  onSuccess?: () => void;
}

export function useSaleSubmit(form: InertiaForm<CreateSaleI>, options: UseSaleSubmitOptions) {
  const notify = useNotify();

  const createSale = () => {
    form.post(route('sale.store'), {
      onSuccess: () => notify.success('Registro Creado', 'Venta procesada correctamente.'),
      onError: () => notify.error('Error al Crear la ventas'),
    });
  };

  const updateSale = () => {
    form.patch(route('sale.update', { sale: form.uuid }), {
      onSuccess: () => notify.success('Registro Actualizado', 'Venta actualizada correctamente.'),
      onError: () => notify.error('Error al Actualizar la ventas'),
    });
  };

  const sendData = () => {
    if (form.pending > 0) {
      notify.warn('Verifique el monto pendiente antes de continuar.');
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
