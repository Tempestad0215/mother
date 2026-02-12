

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


export interface ProductTypeEnumI {
    producto: string;
    servicio: string;
}

/**
 *
 */
export interface ProductBaseI {
    id: number;
    code: string;
    name: string;
    description: string | null;
    bar_code: string | null;
    sku: string | null;
    brand_id: number | null;
    dimensions: string | null;
    process: number;
    unit_id: number;
    stock: number;
    cost: number;
    price: number;
    min_price?: number;
    special_price?: number;
    is_service: 1 | 0;
    supplier_id: number;
    category_id: number;
    tax_id: number;
    tax_rate?: number;
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
export interface productFullI extends ProductBaseI {
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
export interface productTransI extends ProductBaseI {
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
    unit_id: number | null
    price: number
    cost: number
    min_price: number
    special_price: number
    product_no_tax: number
    benefits: number
    benefits_rate: number
    is_service: boolean
    category_id: number
    supplier_id: number
    warehouse_id: number
    search: string
    tax_id: number
    weight: number
    bar_code: string
    sku: string
    brand_id: number | null
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
