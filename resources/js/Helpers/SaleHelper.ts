import { MenuItemI } from '@/Interfaces/GlobalInterface';

export const SaleBreadCrumbs: MenuItemI[] = [
  {
    label: 'Ventas',
    url: route('sale.index'),
  },
  {
    label: 'Reporte de Ventas',
  },
  {
    label: 'Cierre de Caja',
  },
];
