import { PriceListProducts } from '@/Interfaces/PriceListInterface';


export function useProductInfo() {
  const getPriceInfo = (pricesLists: PriceListProducts[], uuid: string): PriceListProducts => {
    const priceInfo = pricesLists.find((el) => el.uuid === uuid);

    if (!priceInfo) {
      throw new Error(`PriceListProducts con uuid ${uuid} no encontrado`);
    }

    return priceInfo;
  };

  return { getPriceInfo };
}
