export interface currencyI {
    uuid: string;
    code: string;
    name: string;
    symbol: string;
    is_base: boolean;
    status: boolean;
    deleted_at: string;
}


export interface currencyDayI {
    day: number;
    us: number;
    eur: number;
}

export interface monthDayI {
    name:string;
    day:number;
}
