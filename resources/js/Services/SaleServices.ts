import {productDataI} from "@/Interfaces/Product";
import {infoSaleI} from "@/Interfaces/Sale";


/**
 * @property id
 * @property code
 * @property name
 * @property stock
 * @property price
 * @property amount
 * @property type
 * @property discount
 * @property discount_amount
 * @property tax_rate
 * @property tax
 *
 */
export class SaleService {
    id: number;
    code: string;
    name: string;
    stock: number;
    price: number;
    amount: number;
    type: string;
    discount: number;
    discount_amount: number;
    tax_rate: number;
    tax: number;

    /**
     * Constructor
     */
    constructor() {
        this.stock = 1;
        this.amount = 0;
        this.discount = 0;
        this.discount_amount = 0;
        this.tax_rate = 0;
        this.tax = 0;
    }


    /**
     * Colocar la informacion de venta
     * @param item
     */
    setInfo(item:productDataI): void{
        this.id = item.id;
        this.code = item.code;
        this.name = item.name;
        this.stock = 1;
        this.price = item.price;
        this.amount =  0.00;
        this.type = item.type;
        this.discount = item.discount;
        this.discount_amount = item.discount_amount;
        this.tax_rate = item.tax_rate;
        this.tax = item.tax;
    }

    /**
     * Obtener los datos del info
     */
    getInfo():infoSaleI {
        return {
            'id': this.id,
            'code': this.code,
            'name': this.name,
            'stock': this.stock,
            'price': this.price,
            'amount': this.amount,
            'type': this.type,
            'discount': this.discount,
            'discount_amount': this.discount_amount,
            'tax_rate': this.tax_rate,
            'tax': this.tax
        }
    }






}
