import { ProductBaseI } from '@/Interfaces/ProductInterface';

export interface entryBaseI {
  uuid: string;
  quantity: number;
  cost: number;
  description?: string;
  type?: string;
  status?: boolean;
  was_updated?: boolean;
  created_at?: string;
}

export interface entryProductI extends entryBaseI {
  product: ProductBaseI;
}
