
/**
 *
 */
export interface clientBaseI {
    address: string | null
    created_at: string
    email: string | null
    personal_id: string | null
    type_price: number
    id: number
    name: string
    type: string
    phone: string | null
    status: boolean
    updated_at: string
}


/**
 *
 */
export interface clientEditI extends clientBaseI {
    document: string;
    comment: string;
    amount: number;
    due_date: number;
    late_fee: number;
    balance: number;
}


