export interface PriceListBaseI {
  name: string;
  currency: string;
  status?: boolean;
}

export interface PriceListWTI extends PriceListBaseI {
  created_at: string;
  updated_at: string;
}
