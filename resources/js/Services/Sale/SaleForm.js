
import {SaleService} from "@/Services/SaleServices.js";


export class SaleForm extends SaleService{
    id ;
    code ;
    client_name ;
    client_id ;
    info_sale;
    tax ;
    discount_amount ;
    amount ;
    sub_total ;
    comment ;
    comment_id ;
    close_table;
    received ;
    returned ;
    general ;
    type ;
    update;
    sequence_type ;
    sequence ;
    invoice_type ;

    //Contributor
    constructor() {
        super();
        this.setDefault();
    }

    // para actualizar los totales
    updateTotal() {
        this.sub_total = this.info_sale.reduce((total) => total, 0);
        this.discount_amount = this.info_sale.reduce((total) => total , 0);
        this.tax = this.info_sale.reduce((total) => total , 0);
        this.amount = this.sub_total - this.discount_amount;
    }


    setInfoForm(item) {
        this.setInfo(item);
        //Colocar los datos separados
        this.info_sale.push(this.getInfo());

    }

    // Metodo para agregar un nuevo producto a la info_sale
    addProduct(producto){
        this.info_sale.push(producto);
    }

    //Poner los valores por defecto
    setDefault(){
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
    clearForm()  {
        this.setDefault();
    }

}
