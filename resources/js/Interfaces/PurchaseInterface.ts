import {SupplierI} from "@/Interfaces/SupplierInterface";
import {PurchaseStatusEnum} from "@/Enums/PurchaseEnum";


export interface purchaseInfoI {
    id: number
    code: string
    name: string;
    quantity: number;
    warehouse_id: number;
    cost: number;
    tax_id: number;
    discount_rate: number;
    discount_amount: number;
    amount: number;
    tax: number;
}


export interface PurchaseSupplierI {
    id: number
    code: string
    supplier_id: number
    user_id: number
    doc_date: string
    amount: number
    tax:number
    sub_total: number
    discount: number
    status: PurchaseStatusEnum
    comment: string | null
    created_at: string
    updated_at: string
    deleted_at: string | null
    supplier: SupplierI
    items: PurchaseItemI[]
}


export interface PurchaseItemI {
    amount: number
    cost: number
    created_at: string
    deleted_at: string | null
    discount: number
    id: number
    product_id: number
    product_name: string
    tax_rate: number
    purchase_id: number
    quantity: number
    tax_id: number
    updated_at: string
    warehouse_id: number
    warehouse_name: string

}
