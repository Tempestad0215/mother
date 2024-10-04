// src/inertia.d.ts
import { Page } from '@inertiajs/core';
import { appSettingI, userAuthI } from "@/Interfaces/Global";

// Extender la interfaz PageProps
declare module '@inertiajs/core' {
    interface PageProps {
        setting: appSettingI;
        auth: {
            user: userAuthI;
            canLogin: boolean;
        };
    }

    // Puedes ajustar la declaración de usePage si es necesario
    function usePage<SharedProps extends PageProps>(): Page<SharedProps>;
}
