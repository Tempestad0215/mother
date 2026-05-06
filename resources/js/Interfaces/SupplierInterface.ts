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
