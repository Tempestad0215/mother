import type { MenuItem } from 'primevue/menuitem';
import { UserRoundKey } from '@lucide/vue';

export const itemsSettings: MenuItem[] = [
  {
    label: 'Configuracion',
    url: route('setting.index'),
    icon: 'pi pi-cog',
  },
  {
    label: 'Almacenes',
    url: route('warehouse.index'),
    icon: 'pi pi-warehouse',
  },
  {
    label: 'Ramas',
    url: route('branch.index'),
    icon: 'pi pi-bookmark',
  },
  {
    label: 'Unidades',
    url: route('unit.index'),
    icon: 'pi pi-list-check',
  },
  {
    label: 'Impuestos',
    url: route('tax.index'),
    icon: 'pi pi-wallet',
  },
  {
    label: 'Lista Precio',
    url: route('price-list.index'),
    icon: 'pi pi-wallet',
  },
  {
    label: 'Roles',
    url: route('user.assing.role.index'),
    iconComponent: UserRoundKey,
  },
  {
    label: 'Secuencia',
    url: route('sequence.create'),
    icon: 'pi pi-wallet',
  },
];
