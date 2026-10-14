import { inject } from 'vue';
import { saleKey } from '@/Injections/saleKeys';
import { ProductTableI } from '@/Interfaces/ProductInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import { getInfoFromPriceList } from '@/Helpers/ProductHelper';
import axios from 'axios';
import { useNotify } from '@/Composable/Shared/useNotify';
import { infoSaleI } from '@/Interfaces/SaleInterface';

export interface UseSaleCartOptionsI {
  onTotalSale?: () => void;
  onTotalAmount?: (item: infoSaleI) => void;
}

export function useSaleCart(options: UseSaleCartOptionsI = {}) {
  const form = inject(saleKey)!;

  const notify = useNotify();
  const getDataProduct = (data: ProductTableI) => {
    const getIndex = form.info_sale.findIndex((el) => el.product_uuid === data.uuid);

    if (getIndex >= 0) {
      form.info_sale[getIndex].stock += 1.0;
    } else {
      const taxRate = PreciseCalculator.divide(data.tax.rate, 100) ?? 0;
      const taxPlus = Number(PreciseCalculator.multiply(taxRate.toString(), data.price));

      let taxForProduct: number;

      if (taxPlus === 0) {
        taxForProduct = 0;
      } else {
        taxForProduct = Number(PreciseCalculator.multiply(taxPlus, 1));
      }

      const priceList = getInfoFromPriceList(data.price_lists, data.default_price_list);

      form.info_sale.push({
        product_uuid: data.uuid,
        product_name: data.name,
        code: data.code,
        stock: 1,
        price: priceList?.price ?? 0,
        price_type: 'price',
        min_price: priceList?.min_price ?? 0,
        promotional_price: priceList?.promotional_price ?? 0,
        tax_uuid: data.tax.uuid,
        tax_amount: taxForProduct,
        warehouse_uuid: data.default_warehouse,
        tax_rate: parseFloat(PreciseCalculator.divide(data.tax.rate, 100).toString()),
        discount: 0,
        discount_amount: 0,
        reserved: 0,
        amount: data.price,
        is_service: Boolean(data.is_service),
        temp_price: data.price,
      });
    }
    options.onTotalSale?.();
  };

  const getProductCode = async () => {
    if (form.code_value.length > 0) {
      await axios
        .get(route('product.get.code', { search: form.code_value }))
        .then((res) => {
          const info = res.data as ProductTableI;
          const getIndex = form.info_sale.findIndex((el) => el.product_uuid === info.uuid);

          if (getIndex >= 0) {
            const infoCurrent = form.info_sale[getIndex];
            infoCurrent.stock += 1.0;

            options.onTotalAmount?.(infoCurrent);
          } else {
            const taxRate = PreciseCalculator.divide(info.tax.rate, 100) ?? 0;
            const taxAmount = PreciseCalculator.multiply(taxRate.toString(), info.price);

            form.info_sale.push({
              amount: info.price,
              price: info.price,
              price_type: 'price',
              product_name: info.name,
              product_uuid: info.uuid,
              discount: 0,
              discount_amount: 0,
              reserved: 0,
              stock: 1,
              tax_amount: parseFloat(taxAmount.toString()),
              tax_rate: parseFloat(taxRate.toString()),
              tax_uuid: info.tax_uuid,
              temp_price: 0,
              warehouse_uuid: info.default_warehouse,
            });
          }
          form.code_value = '';
        })
        .catch(() => {
          notify.error(`El código: ${form.code_value} no existe o no tiene stock.`);
        });
    }
  };

  return { getProductCode, getDataProduct };
}
