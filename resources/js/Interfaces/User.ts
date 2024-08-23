import {clientDataI} from "@/Interfaces/ClientInterface";

export interface userI {
    email: string
    id: number;
    name: string;
    profile_photo_url: string;
    role: string
    status: boolean;
}

export interface  userPaginationI {
    links: {
        first: string;
        last?: string;
        next?: string;
        prev?: string;
    },
    meta: {
        current_page: number;
        from: number;
        to: number;
        per_page: number;
    }
    data: userI[]
}
