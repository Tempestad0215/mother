import axios from 'axios';
import { useToast } from 'primevue';
import { WarehouseProductI } from '@/Interfaces/ProductInterface';

export function useProductWarehouses() {
  const toast = useToast();

  const getWarehouses = async (product: string): Promise<WarehouseProductI[]> => {
    try {
      const res = await axios.get(route('warehouse.product', { product: product }));

      if (res.data && Array.isArray(res.data) && res.data.length > 0) {
        return res.data;
      } else {
        return [];
      }
    } catch (_) {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: 'No se Ha Podido Obtener Los datos',
        life: 5000,
      });
      return [];
    }
  };

  return { getWarehouses };
}
