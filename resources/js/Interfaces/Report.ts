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
