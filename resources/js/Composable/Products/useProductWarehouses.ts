import axios from 'axios';
import { WarehouseProductI } from '@/Interfaces/ProductInterface';

export function useProductWarehouses() {


  const getWarehouses = async (product: string): Promise<WarehouseProductI[]> => {
    try {
      const res = await axios.get(route('warehouse.product', { product: product }));

      if (res.data && Array.isArray(res.data) && res.data.length > 0) {
        return res.data;
      } else {
        return [];
      }
    } catch (_) {
      return [];
    }
  };

  return { getWarehouses };
}
