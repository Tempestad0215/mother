

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
    stock: string;
    cost: string;
    price: string;
    supplier_id: number;
    updated_at: string
    created_at: string
}

export interface productDataFullI {
    id: number
    name: string
    description: string | null,
    bar_code: string | null,
    sku: string | null,
    brand: string | null,
    dimensions: string | null
    unit: string
    stock: number
    cost: number
    tax_rate: number
    weight: string
    created_at: string
    updated_at: string
}

export interface proSupResI{
    data: productSupllierI
}

export interface productSupllierI {
    id: number
    name: string
    description: string | null,
    bar_code: string | null,
    sku: string | null,
    brand: string | null,
    dimensions: string | null
    unit: string
    stock: number
    cost: number
    supplier: {
        id: number
        name: string
    },
    category: {
        id: number,
        name: string
    },
    tax_rate: number
    weight: string
    created_at: string
    updated_at: string
}

export  interface  productSaleI{
    id: number;
    name: string;
    price: string;
    stock: string;
    amount: string;
    tax: string;
}
