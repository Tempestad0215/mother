import {PaginationI} from "@/Interfaces/GlobalInterface";


export interface acoBaseI {
    code: string;
    created_at : string;
    deleted_at: string | null;
    name: string;
    type: string;
    updated_at: string;
    id: number;

}

// export interface acoTableI extends paginationI {
//     data: acoBaseI[];
// }
