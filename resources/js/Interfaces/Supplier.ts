import {creditI} from "@/Interfaces/Account";


export interface supplierI {
    company_name: string;
    contact: string | null;
    created_at: string;
    email: string| null;
    id: number;
    phone: string | null;
    status: number;
    payment: {
        name: string;
        value: string;
    } | null;
    account?: creditI
    updated_at: string
}


export interface supplierDataI {
    current_page: number
    data: supplierI[]
    first_page_url: (string|null)
    from: number
    next_page_url: (string|null)
    path: string
    per_page: number
    prev_page_url: (string|null)
    to: number
}

export interface supplierAccountI extends supplierI{
    amount: number;
    due_date: number;
    balance: number;
    consumed: number;
    late_fee: number;
}
