import { TaxI, TimeStampI } from '@/Interfaces/GlobalInterface';

// export interface infoBranchesI {
//     name: string;
//     code: string;
//     address: string;
//     phone: string;
// }

export interface settingsDataI extends TimeStampI {
    uuid: string;
    name: string;
    email: string;
    phone: string;
    address: string;
    logo: string;
    website: string;
    company_id: string | null;
    save_cost: boolean;
    sequence: boolean;
    fiscal_year: string | null;
    status: boolean;
}

export interface sequenceDataI extends TimeStampI {
    uuid: string;
    code: string;
    type: string;
    from: number;
    next: number;
    to: number;
    advise: number;
    num_request: string;
    num_authorization: string;
    date_request: Date;
    date_expire: Date;
    status: boolean;
}

/**
 *
 */
export interface rncUserI {
    razon_social: string;
    rnc: string;
    status: string;
    type: string;
}

/**
 * Tipo de factura para facturar
 */
export interface invoiceTypeI {
    type: string;
    name: string;
}
