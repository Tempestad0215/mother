export interface KPI {
  total_sales: number;
  transactions_count: number;
  total_refunds: number;
}

export interface WarehousePivot {
  pivot: {
    stock_quantity: number;
  };
  name: string;
  uuid: string;
}

export interface ProductLowStock {
  warehouse_uuid: string;
  warehouse_name: string;
  critical_products: CriticalProduct[];
}

export interface CriticalProduct {
  product_uuid: string;
  product_code: string;
  product_name: string;
  stock_quantity: number;
  min_stock: number;
}

interface WarehouseI {
  warehouse_uuid: string;
  warehouse_code: string;
  warehouse_name: string;
  stock_quantity: number;
  min_stock: number;
}
export interface TopProduct {
  uuid: string;
  code: string;
  name: string;
  cost: number;
  total_qty: number; // 👈 Ya llega como un float/int limpio desde Laravel
}
