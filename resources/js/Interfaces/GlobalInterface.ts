import {MenuItem} from "primevue/menuitem";

export interface TaxI {
    amount: number
    name: string
    rate: string
}

export interface MenuItemI extends MenuItem{
    activePath?: string
}

export type MoveDirectionEdit = "UP" | "DOWN"

export interface ValidationErrors {
    [fielName:string]: string;
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
    id: number
    name: string
    email: string
    email_verified_at: string
    two_factor_confirmed_at: null | string
    status: boolean
    role: string
    current_team_id: null | string
    profile_photo_path: null | string
    created_at: string
    updated_at: string
    profile_photo_url: string
    two_factor_enabled: boolean
}

interface LinksI {
    active: boolean
    label: string
    page: number | null;
    url: string | null;
}

// Pagination
export interface PaginationI<T> {
    current_page: number
    first_page_url: string
    from: number
    links: LinksI[]
    last_page: number
    last_page_url: string
    next_page_url: null | string
    path: string
    per_page: number
    prev_page_url: null | string
    to: number
    total: number
    data: T[]
}

export interface PaymentTypeEnumI {
    contado: string
    tarjeta: string
    credito: string
    transferencia: string
    anticipo: string
    cheque: string
}
