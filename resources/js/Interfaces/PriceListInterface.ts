export interface PriceListBaseI {
  name: string;
  currency: string;
  status?: boolean;
}

export interface PriceListWTI extends PriceListBaseI {
  created_at: string;
  updated_at: string;
}

export interface PriceListProducts {
  currency: string;
  min_price: number;
  name: string;
  price: number;
  promotional_price: number;
  tax_amount_min_price: string;
  tax_amount_price: string;
  tax_amount_promotional: string;
  tax_percent: number;
  tax_rate: number;
  total_with_tax_min_price: string;
  total_with_tax_price: string;
  total_with_tax_promotional: string;
  uuid: string;
}
