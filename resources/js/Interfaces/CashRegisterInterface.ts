export interface CashMovement {
  uuid: string;
  type: CashMovementType;
  type_label: string;
  amount: string; // 💡 Viene como string por la precisión de BCMath
  concept: string;
  comment: string | null;
  created_at: string;
}

export type CashMovementType = 'income' | 'expense' | 'vault_deposit' | 'initial_fund';

export interface CashRegisterCloseDataI {
  uuid: string;
  opening_balance: string;
  status: string; // O el enum si lo manejas en el front (ej: 'open' | 'closed')
  created_at: string;

  // Datos de la ventas
  total_contado: string;
  total_credito: string;
  total_transferencia: string;
  total_cheque: string;
  total_anticipo: string;
  total_tarjeta: string;

  // 📊 Totales calculados de auditoría
  total_income: string;
  total_expense: string;
  total_vault_deposit: string;
  total_initial_fund: string;

  // 🎯 El monto esperado final en gaveta
  expected_balance: string;

  // 🔄 Listado de transacciones
  movements: CashMovement[];
}
