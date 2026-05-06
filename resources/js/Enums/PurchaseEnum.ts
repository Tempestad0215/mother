export enum PurchaseStatusEnum {
  Borrador = 'Borrador',
  Pendiente = 'Pendiente',
  Parcial = 'Parcial',
  Completada = 'Completada',
  Cancelada = 'Cancelada',
}

export type TagSeverity =
  | 'primary'
  | 'secondary'
  | 'success'
  | 'info'
  | 'warn'
  | 'danger'
  | undefined;
