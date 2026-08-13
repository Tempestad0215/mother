import { MenuItem } from 'primevue/menuitem';
import { PurchaseStatusEnum, TagSeverity } from '@/Enums/PurchaseEnum';

export const purchaseBreadCrumb: MenuItem[] = [
  {
    label: 'Compra',
    icon: 'pi pi-shopping-card',
    url: route('purchase.index'),
  },
  {
    label: 'Estado Compra',
    icon: 'pi pi-shopping-card',
    url: route('purchase.show'),
  },
];

export const PurchaseStatusSeverity: Record<PurchaseStatusEnum, TagSeverity> = {
  [PurchaseStatusEnum.Borrador]: 'info',
  [PurchaseStatusEnum.Pendiente]: 'warn',
  [PurchaseStatusEnum.Parcial]: 'warn',
  [PurchaseStatusEnum.Completada]: 'success',
  [PurchaseStatusEnum.Cancelada]: 'danger',
};
