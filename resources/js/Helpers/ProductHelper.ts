import type { MenuItem } from 'primevue/menuitem';
import { PriceListBaseI, PriceListProducts } from '@/Interfaces/PriceListInterface';
import { WarehouseProductI } from '@/Interfaces/ProductInterface';

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
    url: route('product.index'),
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
