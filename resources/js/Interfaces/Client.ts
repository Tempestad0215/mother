
// Cliente para la tabla
import {commentI} from "@/Interfaces/Comment";

export interface baseClient {
    address: string | null
    created_at: string
    email: string | null
    personal_id: string | null
    uuid: string
    name: string
    phone: string | null
    status: boolean
    updated_at: string
}


export interface clientDataI {
    current_page: number
    data: baseClient[]
    first_page_url: (string | null)
    from: number
    next_page_url: (string|null)
    path: string
    per_page: number
    prev_page_url: (string|null)
    to: number
}

// Editar
export interface clientEditI extends baseClient {
    document: string;
    type: string,
    comment: commentI
}


