import axios from 'axios';
import { useToast } from 'primevue';
import { WarehouseProductI } from '@/Interfaces/ProductInterface';
import { useNotify } from '@/Composable/Shared/useNotify';

export function useProductWarehouses() {
  const toast = useToast();

  const notify = useNotify();

  const getWarehouses = async (product: string): Promise<WarehouseProductI[]> => {
    try {
      const res = await axios.get(route('warehouse.product', { product: product }));

      if (res.data && Array.isArray(res.data) && res.data.length > 0) {
        return res.data;
      } else {
        return [];
      }
    } catch (_) {
      notify.error('No se Ha Podido Obtener Los datos');
      return [];
    }
  };

  return { getWarehouses };
}
