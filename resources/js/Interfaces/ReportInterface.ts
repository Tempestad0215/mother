/**
 * Ventas del dia
 */
export interface reportDayI {
    tax: number;
    sub_total: number;
    amount: number;
    discount: number;

}

/**
 * Para mostar los datos productos vendido
 */
export interface mostSoldI {
    id: number;
    code: string;
    name: string;
    totalSaled:  number;
}



/*
total de ventas
 */
export interface totalSoldAmountI {
    cash: number;
    credit: number;
    check: number;
    card: number;
    deposit: number;
    transfer: number;
    tax:number;
    discount:number;
    amount: number;
}


/**
 * Reporte de beneficios para crear el cierre
 */
export interface totalSaleAmountI {
    tax: number;
    sub_total: number;
    discount_amount: number;
    amount: number;
    benefits: number;
}
