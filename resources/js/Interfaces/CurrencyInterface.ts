export interface currencyI {
  uuid: string;
  code: string;
  name: string;
  symbol: string;
  is_base: boolean;
  status: boolean;
  deleted_at: string;
}

export interface currencyDayI {
  day: number;
  usd: number;
  eur: number;
  dop: number;
}

export interface monthDayI {
  name: string;
  day: number;
}
