

export interface infoSaleI {
    amount: number;
    code: string;
    cost: number;
    deleted_at: string | null;
    discount: number;
    discount_amount: number;
    id: number;
    price: number;
    product_id: number;
    sale_id: number;
    status: boolean;
    stock: number;
    tax: number;
    tax_rate: number;
    type?: string;
    updated_at?: string;
    created_at?: string;
}

interface saleI{
    id: number;
    code: string;
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
    comment:  {
        id: number,
        content: string,
    };
    created_at: string;
    discount: number;
    id: number;
    info_sale: infoSaleI[];
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
