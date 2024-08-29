

export interface infoBranchesI {
    name: string;
    code: string;
    address: string;
    phone: string;
}

export  interface settingsDataI {
    id: number;
    name: string;
    email: string;
    phone: string;
    address: string;
    logo: string;
    website: string;
    company_id: string | null;
    tax: Array<number>;
    fiscal_year: string | null;
    status: boolean;
    created_at: string;
    updated_at: string;

}
