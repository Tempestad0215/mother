import { MenuItem } from 'primevue/menuitem';

export interface TaxI {
  amount: number;
  name: string;
  rate: string;
}

export interface LaravelErrorResponse {
  message: string;
  errors?: Record<string, string[]>;
  exception?: string;
  code?: number;
}

export interface MenuItemI extends MenuItem {
  activePath?: string;
}

export type MoveDirectionEdit = 'UP' | 'DOWN';

export interface ValidationErrors {
  [fielName: string]: string;
}

export interface AppSettingI {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  address: string | null;
  logo: string | null;
  website: string | null;
  company_id: string | null;
  tax: TaxI[] | [];
  unit: string[] | [];
  fiscal_year: string | null;
  company_type: string | null;
  status: boolean;
  save_cost: boolean;
  sequence: boolean;
  created_at: string;
  updated_at: string;
}

export interface UserAuthI {
  id: number;
  name: string;
  email: string;
  email_verified_at: string;
  two_factor_confirmed_at: null | string;
  status: boolean;
  role: string;
  current_team_id: null | string;
  profile_photo_path: null | string;
  created_at: string;
  updated_at: string;
  profile_photo_url: string;
  two_factor_enabled: boolean;
}

interface LinksI {
  active: boolean;
  label: string;
  page: number | null;
  url: string | null;
}

// Pagination
export interface PaginationI<T> {
  links: LinksI;
  meta: MetaI;
  data: T[];
}

interface LinksI {
  first: string;
  last: string;
  prev: string | null;
  next: string | null;
}

interface MetaI {
  current_page: number;
  current_page_url: string;
  from: number;
  path: string;
  per_page: number;
  to: number;
  total: number;
}

export interface PaymentTypeEnumI {
  contado: string;
  tarjeta: string;
  credito: string;
  transferencia: string;
  anticipo: string;
  cheque: string;
}
