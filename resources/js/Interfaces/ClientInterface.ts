
// Cliente para la tabla
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
    name: string;
    phone: string;
    email:(null | string);
    address:(null | string);
    status: Boolean;
    created_at:string;
    updated_at:string;

}


export interface clientDataI {
    address: (string | null)
    createed_at: string
    email: (string|null)
    id: number
    name: string
    phone: string
    status: boolean
    updated_at: string
}
