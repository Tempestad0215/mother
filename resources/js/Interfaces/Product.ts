import {linksI, metaI} from "@/Interfaces/Global";


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
    code:string;
    name: string;
    sku:string;
    bar_code: string | null;
    weight: string;
    dimensions: string | null;
    brand: string | null;
    description: string | null;
    unit: string;
    stock: number;
    cost: number;
    price: number;
    discount: number;
    tax_rate: number;
    tax: number;
    product_no_tax: number;
    category_id: number;
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

export interface productSupplierI {
    id: number;
    code: number;
    name: string;
    description: string | null;
    bar_code: string | null;
    sku: string | null;
    brand: string | null;
    dimensions: string | null;
    unit: string;
    stock: number;
    cost: number;
    type: string;
    supplier_id: number;
    category_id: number;
    tax_rate: number;
    weight: string;
    created_at: string;
    updated_at: string;
}

export  interface  productSaleI{
    id: number;
    name: string;
    quantity: number;
    price: number;
    stock: number;
    amount: number;
    discount: number;
    discount_amount:number;
    tax: number;
    total_tax: number;
    tax_rate: number;
    product_tax: number;
    product_no_tax: number;
    stockTotal: number;
}


/**
 * Producto y trans
 */
export interface productTransI {
    amount: number;
    code: string;
    created_at: string;
    discount: number;
    discount_amount: number;
    id: number;
    cost: number;
    tax_rate: number;
    price: number;
    product_code: string;
    product_id: number;
    product_name: string;
    sale_id: number;
    status: boolean;
    stock: number;
    tax: number;
    type: string;
    updated_at: string;
}

/**
 * Paginacion de product trans
 *
 */
export interface productTransPI {
    data: productTransI[],
    links: linksI,
    meta: metaI
}
