import {productBaseI} from "@/Interfaces/ProductInterface";

export  interface entryBaseI {
    id: number;
    quantity: number;
    cost: number;
    description?: string;
    type?: string;
    status?: boolean;
    was_updated?: boolean;
    created_at?: string;
}

export interface entryProductI extends entryBaseI {
    product: productBaseI
}
