// src/inertia.d.ts
import { Page } from '@inertiajs/core';
import { AppSettingI, UserAuthI } from '@/Interfaces/GlobalInterface';

// Extender la interfaz PageProps
declare module '@inertiajs/core' {
  interface PageProps {
    setting: AppSettingI;
    auth: {
      user: UserAuthI;
      canLogin: boolean;
    };
  }

  // Puedes ajustar la declaración de usePage si es necesario
  function usePage<SharedProps extends PageProps>(): Page<SharedProps>;
}
