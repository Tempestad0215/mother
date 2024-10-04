
// Cliente para la tabla
import {commentI} from "@/Interfaces/Comment";

export interface clientI {
    current_page: number
    data: clientDataI[]
    first_page_url: (string | null)
    from: number
    next_page_url: (string|null)
    path: string
    per_page: number
    prev_page_url: (string|null)
    to: number
}

// Editar
export interface clienteEditI
{
    id: number;
    code: string;
    name: string;
    document: string;
    personal_id: string | null;
    phone: string | null;
    email:null | string;
    address:null | string;
    type: string,
    comment: commentI
    status: boolean;
    created_at:string;
}


export interface clientDataI {
    address: string | null
    code: string
    created_at: string
    email: string | null
    personal_id: string | null
    id: number
    name: string
    phone: string | null
    status: boolean
    updated_at: string
}
