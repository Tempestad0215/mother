

export interface infoSaleI {
    transID?: number;
    amount: number;
    code: string;
    deleted_at?: string | null;
    discount: number;
    discount_amount: number;
    min_price: number;
    special_price: number;
    price: number;
    product_id: number;
    product_name: string;
    sale_id?: number;
    status?: boolean;
    stock: number;
    reserved: number;
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
    client_rnc: string;
    close_table: boolean;
    invoice_type:string;
    ncf: string;
    ncf_m: string;
    code: string;
    comment:  {
        id: number,
        content: string,
    } | null;
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


export interface saleFullI{
    id: number;
    code: string;
    ncf: string;
    invoice_type: string;
    client_id: number | null;
    client_name: string | null;
    client_rnc: string | null;
    discount_amount: number;
    close_table: boolean;
    tax: number;
    sub_total: number;
    amount: number;
    type: string;
    type_payment: string;
    received: number;
    returned: number;
    status: boolean;
    credit_notes: creditNotesSaleI[],
    deleted_at: string | null;
    created_at: string;
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

export interface creditNotesSaleI{
    id: number;
    ncf: string;
    n_available: number;
    code: string;
}
