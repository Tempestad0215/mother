export interface productI {
  current_page: number;
  data: productFullI[];
  first_page_url: string | null;
  from: number;
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
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
  uuid: string;
  code: string;
  name: string;
  description: string | null;
  bar_code: string | null;
  sku: string | null;
  brand_uuid: string | null;
  dimensions: string | null;
  process: number;
  unit_uuid: string;
  stock: number;
  cost: number;
  price: number;
  min_price?: number;
  special_price?: number;
  is_service: 1 | 0;
  supplier_uuid: string;
  category_uuid: string;
  tax_uuid: string;
  tax_rate?: number;
  weight: string;
  created_at: string;
  updated_at: string;
  inventoried: boolean;
  warehouse_uuid: string;
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
  uuid: string;
  name: string;
  description: string;
  unit_uuid: string | null;
  price: number;
  cost: number;
  min_price: number;
  special_price: number;
  product_no_tax: number;
  benefits: number;
  benefits_rate: number;
  is_service: boolean;
  category_uuid: string;
  supplier_uuid: string;
  warehouse_uuid: string;
  search: string;
  tax_uuid: string;
  weight: number;
  bar_code: string;
  sku: string;
  brand_uuid: string | null;
  dimensions: string;
  inventoried: boolean;
  has_fraction: boolean;
  status: boolean;
  has_tax: boolean;
  has_special: boolean;
  has_promotion: boolean;
  update: boolean;
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
