import { useConfirm, useToast } from 'primevue';
import { ProductTableI } from '@/Interfaces/ProductInterface';
import { router } from '@inertiajs/vue3';

export function useProductDelete() {
  const confirm = useConfirm();
  const toast = useToast();

  const deleteProduct = (product: ProductTableI, event: Event) => {
    confirm.require({
      target: event.currentTarget as HTMLElement,
      message: '¿Desea eliminar este producto? Los cambios son irreversibles.',
      header: 'Confirmar Eliminación',
      icon: 'pi pi-exclamation-triangle',
      rejectProps: {
        label: 'Cancelar',
        severity: 'secondary',
        outlined: true,
      },
      acceptProps: {
        label: 'Eliminar',
        severity: 'danger',
      },
      accept: () => {
        router.delete(route('product.destroy', { product: product.uuid }), {
          onSuccess: () => {
            toast.add({
              severity: 'success',
              summary: 'Eliminado',
              detail: 'El producto fue eliminado correctamente.',
              life: 3000,
            });
          },
        });
      },
    });
  };

  return { deleteProduct };
}
