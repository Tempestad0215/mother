import { router } from '@inertiajs/vue3';
import { RouteUrl } from 'ziggy-js';

export const getSearchTable = (urlName: RouteUrl): void => {
  try {
    router.get(
      urlName,
      {},
      {
        preserveState: true,
        preserveScroll: true,
      }
    );
  } catch (_) {}
};
