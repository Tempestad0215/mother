

export interface productI {
    current_page: number;
    data: productDataI[];
    first_page_url: string | null;
    from: number;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number
}


export interface productDataI {
    id: number;
    name: string;
    description: string | null;
    unit: string;
    stock: number;
    cost: number;
    supplier_id: number;
}

export interface proSupResI{
    data: productSupllierI
}

export interface productSupllierI {
    id: number
    name: string
    description: string | null
    unit: string
    stock: number
    cost: number
    supplier: {
        id: number
        name: string
    }
    created_at: string
    updated_at: string
}
