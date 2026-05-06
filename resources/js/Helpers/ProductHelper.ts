import type { MenuItem } from 'primevue/menuitem';

export const productBreadCrumb: MenuItem[] = [
  {
    label: 'Producto',
    url: route('product.index'),
    icon: 'pi pi-bottle',
  },
  {
    label: 'Entrada',
    url: route('product.in'),
    icon: 'pi pi-bottle',
  },
  {
    label: 'Salida',
    url: route('product.index'),
    icon: 'pi pi-bottle',
  },
];
