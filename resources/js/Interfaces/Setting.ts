import {taxI} from "@/Interfaces/Global";


// export interface infoBranchesI {
//     name: string;
//     code: string;
//     address: string;
//     phone: string;
// }

export  interface settingsDataI {
    id: number;
    name: string;
    email: string;
    phone: string;
    address: string;
    logo: string;
    website: string;
    company_id: string | null;
    tax: taxI[];
    unit: string[];
    fiscal_year: string | null;
    status: boolean;
    created_at: string;
    updated_at: string;

}

export interface sequenceDataI {
    id: number;
    code: string;
    type: string;
    from: number;
    next: number;
    to: number;
    advise: number;
    num_request: string;
    num_authorization: string;
    date_request: string;
    date_expire: string;
    status: boolean;
    deleted_at: string;
    created_at: string;
    updated_at: string;
}

/**
 *
 */
export interface rncUserI {
    razon_social: string
    rnc: string;
    status: string;
    type : string;
}

/**
 * Tipo de factura para facturar
 */
export interface invoiceTypeI {
    type: string;
    name: string;
}


