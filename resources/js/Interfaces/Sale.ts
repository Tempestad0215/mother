

interface saleInfoI {
    id: number;
    amount: number;
    name: string;
    price: number;
    quantity: number;
    tax: number;
    total_tax: number;
    tax_rate: number;
}

interface saleI{
    id: number;
    client_name: string;
    tax: number;
    sub_total: number;
    amount: number;
    close_table: boolean;
}

export interface saleDataI {
    client_id: number;
    client_name: string;
    close_table: boolean;
    code: string;
    comment: string;
    created_at: string;
    discount: number;
    id: number;
    info: saleInfoI[];
    status: boolean;
    sub_total: number;
    tax: number;
    amount: number;
    updated_at: string;
}


export interface saleDataPaginationI {
    current_page: number
    data: saleDataI[]
    first_page_url: (string | null)
    from: number
    next_page_url: (string|null)
    path: string
    per_page: number
    prev_page_url: (string|null)
    to: number
}

export interface salePaginationI {
    current_page: number
    data: saleI[]
    first_page_url: (string | null)
    from: number
    next_page_url: (string|null)
    path: string
    per_page: number
    prev_page_url: (string|null)
    to: number
}
