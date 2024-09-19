

//solo nombnre
export interface nameI {
    name: String
}

// Solio el id
export interface idI {
    id: Number
}

export interface taxeI{
    amount: number
    name: string
}


interface appSetting {
    id: number;
    name: string;
    email: string;
    phone: string;
    address: string;
    logo: string;
    website: string;
    company_id: string;
    tax: number[];
    unit: string[];
    fiscal_year: string;
    status: boolean;
    save_cost: boolean;
    created_at: string;
    updated_at: string;

}

export interface pageI {

    component: string
    // scrollRegion: any[]
    url: string
    version: string
    // rememberState: any
    props: {
        appSetting: appSetting,
        auth: {
            user: {
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
        }
        canLogin: boolean
        errorBags:any[]
        errors: any
        jetstream: {
            canCreateTeams: boolean
            canManageTwoFactorAuthentication: boolean
            canUpdatePassword: boolean
            canUpdateProfileInformation: boolean
            hasEmailVerification: boolean
            flash: any[]
            hasAccountDeletionFeatures: boolean
            hasApiFeatures: boolean
            hasTeamFeatures: boolean
            hasTermsAndPrivacyPolicyFeature: boolean
            managesProfilePhotos:boolean
        }
        laravelVersion: string
        phpVersion: string
    }



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
