import { inject } from 'vue';
import { infoSaleI, saleDataI } from '@/Interfaces/SaleInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import { saleKey } from '@/Injections/saleKeys';

export interface UseSaleOpenOptionsI {
  onTotalAmount?: (item: infoSaleI) => void;
  onTotalSale?: () => void;
  onClose?: () => void;
}
export function useSaleOpen(options: UseSaleOpenOptionsI = {}) {
  const form = inject(saleKey)!;
  const getSaleOpen = (item: saleDataI) => {
    // 1. Resetear el carrito
    form.info_sale = [];

    // 2. Cargar items
    item.info_sale.forEach((el) => {
      form.info_sale.push({
        ...el,
        price_type: el.price_type ?? 'price',
        temp_price: el.price,
        tax_amount: parseFloat(PreciseCalculator.multiply(el.price, el.tax_rate).toFixed(2)),
      });

      // ✅ Avisar al padre por cada item
      options.onTotalAmount?.(el);
    });

    // 3. Avisar al padre que recalcule totales
    options.onTotalSale?.();

    // 4. Rellenar datos del cliente/comprobante
    form.uuid = item.uuid;
    form.update = true;
    form.client_uuid = item.client_uuid;
    form.client_rnc = item.client_document ?? '';
    form.ncf = item.ncf;
    form.invoice_type = item.invoice_type;
    form.client_name = item.client_name;
    form.close_table = item.close_table;
    form.comment = item.comment ?? '';

    // 5. Avisar al padre que cierre el modal
    options.onClose?.();
  };

  return { getSaleOpen };
}
