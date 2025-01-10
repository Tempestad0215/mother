import {linksI, metaI} from "@/Interfaces/Global";


export interface productI {
    current_page: number;
    data: productFullI[];
    first_page_url: string | null;
    from: number;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number
}


/**
 *
 */
export interface productBaseI {
    id: number;
    code: string;
    name: string;
    description: string | null;
    bar_code: string | null;
    sku: string | null;
    brand: string | null;
    dimensions: string | null;
    process: number;
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

/**
 *
 */
export interface productFullI extends productBaseI {
    reserved: number;
    min_price: number;
    special_price: number;
    price: number;
    discount: number;
    tax: number;
    product_no_tax: number;
}


/**
 * Producto y trans
 */
export interface productTransI extends productBaseI {
    amount: number;
    discount: number;
    discount_amount: number;
    price: number;
    min_price: number;
    special_price: number;
    product_code: string;
    product_id: number;
    product_name: string;
    sale_id: number;
    status: boolean;
    tax: number;
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
