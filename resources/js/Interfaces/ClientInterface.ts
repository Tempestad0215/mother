export interface clientTypeI {
  contado: string;
  credito: string;
  anticipo: string;
}

export interface ClientRncI {
  rnc: string;
  razon_social: string;
  actividad: string;
  status: 'ACTIVO' | 'SUSPENDIDO';
  type: string;
}

export interface clientPriceI {
  normal: number;
  minimo: number;
  especial: number;
}

export interface clientDocumentI {
  cedula: string;
  pasaporte: string;
  rnc: string;
  otro: string;
}
/**
 *
 */
export interface clientBaseI {
  address: string | null;
  created_at: string;
  email: string | null;
  personal_id: string | null;
  document: string;
  type_price: number;
  type_rnc: string;
  uuid: string;
  name: string;
  type: string;
  phone: string | null;
  status: boolean;
  updated_at: string;
  comment: string | null;
}

/**
 *
 */
export interface clientEditI extends clientBaseI {
  document: string;
  comment: string;
  amount: number;
  due_date: number;
  late_fee: number;
  balance: number;
}

export interface rncClientI {
  rnc: string;
  razon_social: string;
  status: string;
  type: string;
}
