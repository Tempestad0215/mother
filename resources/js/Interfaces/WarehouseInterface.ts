export interface WarehouseBaseI {
  uuid: string;
  prefix: string;
  name: string;
  description: string;
  location: string;
}

export interface ProductWarehouseStockI {
  warehouse_uuid: string;
  stock_quantity: number;
  committed_stock: number;
  available_stock: number;
  min_stock: number;
  max_stock: number;
  reorder_leve: number; // Mantengo el nombre tal cual lo escribiste, si quieres corregirlo a "reorder_level" también está bien
  is_active: boolean;
}
