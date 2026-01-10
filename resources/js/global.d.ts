import type { PageProps as InertiaPageProps } from '@inertiajs/core'
import { AxiosInstance } from 'axios';
import ziggyRoute, { Config as ZiggyConfig } from 'ziggy-js';
import { PageProps as AppPageProps } from './';
import {AppSettingI, UserAuthI} from "@/Interfaces/GlobalInterface";

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    var route: typeof ziggyRoute;
    var Ziggy: ZiggyConfig;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
    }
}

export interface userAuthI {
    id: number
    name: string
    email: string
    email_verified_at: string | null
    two_factor_confirmed_at: string | null
    status: boolean
    role: string
    current_team_id: string | null
    profile_photo_path: string | null
    created_at: string
    updated_at: string
    profile_photo_url: string
    two_factor_enabled: boolean
}

export interface taxI {
    amount: number
    name: string
}

export interface appSettingI {
    id: number
    name: string
    email: string
    phone: string | null
    address: string | null
    logo: string | null
    website: string | null
    company_id: string | null
    tax: taxI[]
    unit: string[]
    fiscal_year: string | null
    company_type: string | null
    status: boolean
    save_cost: boolean
    sequence: boolean
    created_at: string
    updated_at: string
}

export interface AppPageProps {
    auth?: { user?: UserAuthI }
    setting?: AppSettingI
}

// 🔹 Extiende la PageProps de Inertia con tus props.
// No declares "props" dentro; PageProps ya es el "props" del `page`.
declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}



