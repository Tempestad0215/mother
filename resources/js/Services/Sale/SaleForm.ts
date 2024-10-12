import {infoSaleI} from "@/Interfaces/Sale";
import {productDataI} from "@/Interfaces/Product";
import {SaleService} from "@/Services/SaleServices";


export class SaleForm extends SaleService{
    id: number;
    code: string;
    client_name: string;
    client_id: number;
    info_sale: infoSaleI[];
    tax: number;
    discount_amount: number;
    amount: number;
    sub_total: number;
    comment: string;
    comment_id: string;
    close_table: boolean;
    received: number;
    returned: number;
    general: string;
    type: string;
    update: boolean;
    sequence_type: string;
    sequence: string;
    invoice_type: string;

    //Contrcutor
    constructor() {
        super();
        this.setDefault();
    }

    // Metodo para actualizar los totales
    updateTotal(): void {
        this.sub_total = this.info_sale.reduce((total:number, item:infoSaleI) => total + item, 0);
        this.discount_amount = this.info_sale.reduce((total:number, item:infoSaleI) => total + item.discount_amount, 0);
        this.tax = this.info_sale.reduce((total:number, item:infoSaleI) => total + item.tax, 0);
        this.amount = this.sub_total - this.discount_amount;
    }


    setInfoForm(item:productDataI):void {
        this.setInfo(item);
        //Colocar los datos separado
        this.info_sale.push(this.getInfo());

    }

    // Metodo para agregar un nuevo producto a la info_sale
    addProduct(producto: info_saleSaleI): void {
        this.info_sale.push(producto);
        this.actualizarTotales();
    }

    //Poner los valores por defecto
    setDefault():void{
        this.id = 0;
        this.code_product = "";
        this.client_name = "";
        this.client_id = 0;
        this.info_sale = [];
        this.tax = 0.00;
        this.discount_amount = 0.00;
        this.amount = 0.00;
        this.sub_total = 0.00;
        this.comment = "";
        this.comment_id = "";
        this.close_table = false;
        this.received = 0;
        this.returned = 0;
        this.general = "";
        this.type = "ventas";
        this.update = false;
        this.sequence_type = "";
        this.sequence = "";
        this.invoice_type = "B02";
    }
    //limpiar formulario
    clearForm(): void {
        this.setDefault();
    }
}
