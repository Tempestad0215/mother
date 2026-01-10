import {creditI} from "@/Interfaces/AccountInterface";




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
    updated_at: string,
    comment: string;
}
