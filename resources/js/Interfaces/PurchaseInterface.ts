import { SupplierI } from '@/Interfaces/SupplierInterface';
import { PurchaseStatusEnum } from '@/Enums/PurchaseEnum';

export interface purchaseInfoI {
  uuid: string;
  code: string;
  name: string;
  quantity: number;
  warehouse_uuid: string;
  cost: number;
  tax_uuid: string;
  discount_rate: number;
  discount_amount: number;
  amount: number;
  tax: number;
}

interface timeStampsI {
  created_at: string;
  updated_at: string;
  deleted_at: string | null;
}

export interface PurchaseBaseI {
  uuid: string;
  code: string;
  supplier_uuid: string;
  user_uuid: string;
  doc_date: string | Date;
  amount: number;
  tax: number;
  sub_total: number;
  discount: number;
  status: PurchaseStatusEnum;
  comment: string | null;
}

export interface PurchaseFormI extends PurchaseBaseI {
  supplier_name: string;
  items: PurchaseItemI[];
  supplier?: SupplierI;
}

export interface PurchaseSupplierI extends PurchaseBaseI, timeStampsI {
  supplier: SupplierI;
  items: PurchaseItemI[];
}

export interface PurchaseItemI {
  amount: number;
  cost: number;
  created_at?: string;
  deleted_at?: string | null;
  discount: number;
  uuid: string;
  product_uuid: string;
  product_name: string;
  tax_rate: number;
  tax_amount: number;
  purchase_uuid: string;
  quantity: number;
  tax_uuid: string;
  updated_at?: string;
  warehouse_uuid: string;
  warehouse_name: string;
  isReadOnly?: boolean;
}
