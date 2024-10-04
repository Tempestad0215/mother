

export interface taxI{
    amount: number
    name: string
}


export interface appSettingI {
    id: number;
    name: string;
    email: string;
    phone: string | null;
    address: string | null;
    logo: string | null;
    website: string | null;
    company_id: string | null;
    tax: taxI[] | [];
    unit: string[] | [];
    fiscal_year: string | null;
    company_type: string | null;
    status: boolean;
    save_cost: boolean;
    sequence: boolean;
    created_at: string;
    updated_at: string;

}

export interface userAuthI {
    id: number
    name: string
    email: string
    email_verified_at: string
    two_factor_confirmed_at: null | string
    status: boolean
    role: string
    current_team_id: null | string
    profile_photo_path: null | string
    created_at: string
    updated_at: string
    profile_photo_url: string
    two_factor_enabled: boolean
}



/**
 * Links de navegacion
 */

export interface linksI {
    first: string;
    last: null | string;
    prev: string | null;
    next: string | null;
}
// Meta
export interface metaI{
    current_page: number;
    from: number;
    path: string;
    per_page: number;
    to: number;
}
