import {paginationI} from "@/Interfaces/Global";


export interface acoBaseI {
    code: string;
    created_at : string;
    deleted_at: string | null;
    name: string;
    type: string;
    updated_at: string;
    uuid: string;

}

export interface acoTableI extends paginationI {
    data: acoBaseI[];
}
