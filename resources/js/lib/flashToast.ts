import { router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import type { FlashToast } from '@/types/ui';

export function initializeFlashToast(): void {
  router.on('flash', (event) => {
    const flash = (event as CustomEvent).detail?.flash;
    const data = flash?.toast as FlashToast | undefined;

    if (!data) {
      return;
    }

    toast[data.type](data.message);
  });
}

export function initializeErrorHandle(): void {
  router.on('error', (event) => {
    const errors = (event as CustomEvent).detail?.errors;
    const firstError = Object.values(errors)[0];

    toast.error(firstError ?? 'Erorr de Validacion');
  });

  router.on('httpException', (event) => {
    toast.error('Error del sistema, contacte al administrador');
  });
}
