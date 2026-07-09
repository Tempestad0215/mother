import { MenuItemI } from '@/Interfaces/GlobalInterface';
import { FileChartLine, ShoppingBasket, FileChartColumn, DoorClosedLocked } from '@lucide/vue';

export const SaleBreadCrumbs: MenuItemI[] = [
  {
    label: 'Ventas',
    url: route('sale.index'),
    iconComponent: ShoppingBasket,
  },
  {
    label: 'Ventas Realizadas',
    iconComponent: FileChartLine,
    url: route('sale.show-sold'),
  },
  // {
  //   label: 'Reporte de Ventas',
  //   iconComponent: FileChartColumn,
  // },
  {
    label: 'Cierre de Caja',
    url: route('cash-register.close'),
    iconComponent: DoorClosedLocked,
  },
];

export const saleTypeOptions = [
  {
    name: 'TODO',
    value: null,
  },
  {
    name: 'CONTADO',
    value: 'CONTADO',
  },
  {
    name: 'CREDITO',
    value: 'CREDITO',
  },
  {
    name: 'CHEQUE',
    value: 'CHEQUE',
  },
  {
    name: 'TRANSFERENCIA',
    value: 'TRANSFERENCIA',
  },
  {
    name: 'ANTICIPO',
    value: 'ANTICIPO',
  },
];
