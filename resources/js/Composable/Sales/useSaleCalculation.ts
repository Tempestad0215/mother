import { infoSaleI } from '@/Interfaces/SaleInterface';
import { PreciseCalculator } from '@/utils/Decimal';
import { inject } from 'vue';
import { saleKey } from '@/Injections/saleKeys';

export function useSaleCalculation() {
  const form = inject(saleKey)!;
  const calculateItemRow = (item: infoSaleI, index: number) => {
    if (!item.stock || !item.price) return;

    // Calcular el porcentaje de descuento
    const discountRate = PreciseCalculator.divide(item.discount || 0, 100);
    const taxRate = PreciseCalculator.divide(item.tax_rate, 100);

    const formData = form.info_sale[index];

    formData.stock = item.stock;

    // Calcular Importe Bruto (Precio * Stock)
    formData.amount = parseFloat(
      PreciseCalculator.multiply(item.price.toString(), item.stock.toString()).toFixed(2)
    );

    // Calcular monto deducido por el descuento
    formData.discount_amount = parseFloat(
      PreciseCalculator.multiply(item.amount.toString(), discountRate.toString()).toFixed(2)
    );

    // Calcular ITBIS basado en el importe bruto
    formData.tax_amount = parseFloat(
      PreciseCalculator.multiply(item.amount.toString(), (taxRate || 0).toString()).toFixed(2)
    );

    // Recalcular los totales de la factura global
    calculateTotals();
  };

  const calculateTotals = () => {
    // 1) Totales base
    const subTotal = form.info_sale.reduce(
      (acc: number, currentValue: infoSaleI): number =>
        Number(PreciseCalculator.add(acc, currentValue.amount)),
      0
    );

    // 1.1) Totales de impuestos y descuentos
    const taxTotal = form.info_sale.reduce(
      (acc: number, currentValue: infoSaleI): number =>
        Number(PreciseCalculator.add(acc, currentValue.tax_amount)),
      0
    );

    // 1.2) Totales de descuentos
    const discountTotal = form.info_sale.reduce(
      (acc: number, currentValue: infoSaleI): number =>
        Number(PreciseCalculator.add(acc, currentValue.discount_amount || 0)),
      0
    );

    // 2) Subtotal sin impuestos (si ese es tu concepto)
    form.sub_total = Number(PreciseCalculator.subtract(subTotal, taxTotal));

    // 3) Guardar tax y descuento
    form.tax = taxTotal;
    form.discount_amount = discountTotal;

    // 4) Total final
    const subTotalNoTax = Number(PreciseCalculator.add(form.sub_total, taxTotal));

    form.amount = Number(PreciseCalculator.subtract(subTotalNoTax, discountTotal));
  };

  return { calculateItemRow, calculateTotals };
}
