import { creditI } from '@/Interfaces/AccountInterface';

export interface SupplierI {
  company_name: string;
  contact: string | null;
  created_at: string;
  email: string | null;
  uuid: string;
  phone: string | null;
  status: number;
  payment: {
    name: string;
    value: string;
  } | null;
  account?: creditI;
  updated_at: string;
  comment: string;
}

export interface SupplierBaseI {
  uuid: string;
  code: string;
  company_name: string;
  contact: string;
  phone: string;
  account_bank: string;
  type_payment: string; // O puedes usar tu Enum: PaymentTypeEnum
  is_recurring: boolean;
  receive_email: boolean;
  status: boolean;
  comment?: string | null;
  email?: string | null;
  payment_day?: number | string | null;
  created_at?: string;
  updated_at?: string;
  deleted_at?: string | null;
}
