export interface SaleTypeEnumI {
  venta: string;
  cotizacion: string;
  devolucion: string;
}

export interface infoSaleI {
  transID?: number;
  amount: number;
  deleted_at?: string | null;
  discount?: number;
  discount_amount?: number;
  min_price?: number;
  special_price?: number;
  price: number;
  warehouse_uuid: string;
  price_temp: number;
  product_uuid: string;
  product_name: string;
  is_service?: boolean;
  sale_uuid?: string;
  status?: boolean;
  stock: number;
  reserved: number;
  tax_uuid: string;
  tax_amount: number;
  tax_rate: number;
  type?: string;
  trans_type?: string;
  updated_at?: string;
  created_at?: string;
}

export interface CreateSaleI {
  uuid: string;
  code_value: string;
  ncf: string;
  ncf_m: string;
  client_name: string;
  client_uuid: string;
  client_rnc: string;
  client_rnc_status: string;
  client_social: string;
  info_sale: infoSaleI[];
  tax: number;
  discount_amount: number;
  amount: number;
  sub_total: number;
  comment: string;
  comment_uuid: string;
  close_table: boolean;
  received: number;
  returned: number;
  general: string;
  type: string;
  type_payment: string;
  update: boolean;
  sequence: string;
  sequence_type: string;
  invoice_type: string;
  credit_notes_value: string;
  credit_notes: creditNotesSaleI[];
  credit_notes_amount: number;
  pending: number;
}

export type WarehouseMapType = Record<string, number>;

export interface saleI {
  id: number;
  code: string;
  client_name: string;
  tax: number;
  sub_total: number;
  amount: number;
  close_table: boolean;
}

export interface saleDataI {
  client_uuid: string;
  client_name: string;
  client_document: string | null;
  client_rnc: string;
  close_table: boolean;
  invoice_type: string;
  ncf: string;
  ncf_m: string;
  comment: '' | null;
  created_at: string;
  discount: number;
  uuid: string;
  info_sale: infoSaleI[];
  status: boolean;
  sub_total: number;
  tax: number;
  amount: number;
  updated_at: string;
}

export interface saleFullI {
  id: number;
  code: string;
  ncf: string;
  invoice_type: string;
  client_id: number | null;
  client_name: string | null;
  client_rnc: string | null;
  discount_amount: number;
  close_table: boolean;
  tax: number;
  sub_total: number;
  amount: number;
  type: string;
  type_payment: string;
  received: number;
  returned: number;
  status: boolean;
  credit_notes: creditNotesSaleI[];
  deleted_at: string | null;
  created_at: string;
  updated_at: string;
}

export interface saleDataPaginationI {
  current_page: number;
  data: saleDataI[];
  first_page_url: string | null;
  from: number;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
}

export interface salePaginationI {
  current_page: number;
  data: saleI[];
  first_page_url: string | null;
  from: number;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
}

/**
 * Paginacion de credito
 */
export interface creditPaginationI {
  current_page: number;
  data: creditNotesSaleI[];
  first_page_url: string | null;
  from: number;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
}

export interface creditNotesSaleI extends saleI {
  ncf: string;
  n_available: number;
}
