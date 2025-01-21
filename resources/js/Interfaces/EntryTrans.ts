import {linksI, metaI} from "@/Interfaces/Global";
import {productBaseI} from "@/Interfaces/Product";

export  interface entryBaseI {
    id: number;
    quantity: number;
    cost: number;
    description?: string;
    status?: boolean;
    created_at?: string;
}

export interface entryProductI extends entryBaseI {
    product: productBaseI
}

export interface entryTableI {
    data: entryProductI[];
    link: linksI,
    meta: metaI
}
