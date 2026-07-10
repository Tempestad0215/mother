export interface WarehouseBaseI {
  uuid: string;
  prefix: string;
  name: string;
  description: string;
  location: string;
}

export interface ProductWarehouseI {}

// export interface ProductWarehouseStockI {
//   warehouse_uuid: string;
//   prefix: string;
//   name: string;
//   committed: number;
//   available: number;
//   min_stock: number;
//   max_stock: number;
//   reorder_leve: number; // Mantengo el nombre tal cual lo escribiste, si quieres corregirlo a "reorder_level" también está bien
//   is_active: boolean;
// }
