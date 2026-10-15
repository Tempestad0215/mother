import { PriceListProducts } from '@/Interfaces/PriceListInterface';
import { useNotify } from '@/Composable/Shared/useNotify';

export function useProductInfo() {
  const notify = useNotify();
  const getPriceInfo = (pricesLists: PriceListProducts[], uuid: string): PriceListProducts => {
    const priceInfo = pricesLists.find((el) => el.uuid === uuid);

    if (!priceInfo) {
      notify.error(`PriceListProducts con uuid ${uuid} no encontrado`);
      throw new Error(`PriceListProducts con uuid ${uuid} no encontrado`);
    }

    return priceInfo;
  };

  return { getPriceInfo };
}
