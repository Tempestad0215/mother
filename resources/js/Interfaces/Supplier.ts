


export interface supplierI {
    code: string;
    company_name: string;
    contact: string | null;
    created_at: string;
    email: string| null;
    id: number
    phone: string | null;
    status: number;
    updated_at: string
}


export interface supplierPaginationI {
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
