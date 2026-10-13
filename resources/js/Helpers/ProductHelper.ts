import type { MenuItem } from 'primevue/menuitem';
import { PriceListProducts } from '@/Interfaces/PriceListInterface';
import { WarehouseProductI } from '@/Interfaces/ProductInterface';
import { WarehouseBaseI } from '@/Interfaces/WarehouseInterface';
import { PreciseCalculator } from '@/utils/Decimal';

export const productBreadCrumb: MenuItem[] = [
  {
    label: 'Producto',
    url: route('product.index'),
    icon: 'pi pi-bottle',
  },
  {
    label: 'Entrada',
    url: route('entry.index'),
    icon: 'pi pi-bottle',
  },
  {
    label: 'Salida',
    url: route('out.index'),
    icon: 'pi pi-bottle',
  },
];

// Para la lista de precios
export const getInfoFromPriceList = (
  priceLists: Array<PriceListProducts>,
  uuid: string
): PriceListProducts | undefined => {
  return priceLists.find((el) => el.uuid == uuid);
};

// Para el almacen

export const getInfoFromWarehouse = (
  warehouses: Array<WarehouseProductI>,
  uuid: string
): WarehouseProductI | undefined => {
  return warehouses.find((el) => (el.warehouse_uuid = uuid));
};

export const mergeProductWarehouses = (
  productWarehouses: WarehouseProductI[],
  availableWarehouses: WarehouseBaseI[]
): WarehouseProductI[] => {
  const map = new Map(productWarehouses.map((w) => [w.warehouse_uuid, w]));

  return availableWarehouses.map((wh) => {
    const existing = map.get(wh.uuid);

    const committed = existing?.committed ?? 0;
    const stock_quantity = existing?.stock_quantity ?? 0;

    return {
      warehouse_uuid: wh.uuid,
      prefix: wh.prefix,
      name: wh.name,

      // Si existe en el producto, usa sus valores; si no, 0
      stock_quantity: stock_quantity || 0,
      committed: committed || 0,
      min_stock: Number(existing?.min_stock) || 0,
      max_stock: Number(existing?.max_stock) || 0,
      reorder_level: Number(existing?.reorder_level) || 0,
      is_active: existing?.is_active ?? true,
      available: Number(PreciseCalculator.subtract(stock_quantity, committed)),
    };
  });
};
