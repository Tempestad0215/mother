

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


export interface ProductOptionsI {
    name: string;
    value: string;
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
    price: number;
    min_price?: number;
    special_price?: number;
    type: string;
    supplier_id: number;
    category_id: number;
    tax_rate: number;
    weight: string;
    created_at: string;
    updated_at: string;
    inventoried: boolean;
    warehouse_id: number;
    has_fraction: boolean;
    has_special: boolean;
    has_discount: boolean;
    has_tax: boolean;
    status: boolean;
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

export interface ProductFormI {
    id: number
    name: string
    description: string
    unit: string
    price: number
    cost: number
    min_price: number
    special_price: number
    product_no_tax: number
    benefits: number
    benefits_rate: number
    type: string
    category_id: number
    supplier_id: number
    warehouse_id: number
    search: string
    tax: number
    tax_rate: number
    tax_tex: string
    weight: number
    bar_code: string
    sku: string
    brand: string
    dimensions: string
    inventoried: boolean
    has_fraction: boolean
    status: boolean
    has_tax: boolean
    has_special: boolean
    has_promotion: boolean
    update: boolean
}


/**
 * Paginacion de product trans
 *
 */
// export interface productTransPI {
//     data: productTransI[],
//     links: linksI,
//     meta: metaI
// }
