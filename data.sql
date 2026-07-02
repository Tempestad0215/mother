create type cash_movement_type as ENUM('income','expense','vault_deposit','initial_fund');
alter TYPE cash_movement_type add value 'vault_deposit';
alter type cash_movement_type add value 'initial_fund';

alter table cash_movements
alter column type TYPE cash_movement_type
using (
    case
        when lower(type::text) = 'income' then 'income'::cash_movement_type
        when lower(type::text) = 'expense' then 'expense'::cash_movement_type
        when lower(type::text) = 'vault_deposit' then 'expense'::cash_movement_type
        when lower(type::text) = 'initial_fund' then 'initial_fund'::cash_movement_type
        else 'income'::cash_movement_type
    end
);


create type sale_invoice_type as enum('B01','B02','B14','B15', 'E31','E32','E44','E45');

alter table sales
alter column invoice_type type sale_invoice_type
using (
    case
        when lower(invoice_type::text) = 'B01' then 'B01'::sale_invoice_type
        when lower(invoice_type::text) = 'B02' then 'B02'::sale_invoice_type
        when lower(invoice_type::text) = 'B14' then 'B14'::sale_invoice_type
        when lower(invoice_type::text) = 'B15' then 'B15'::sale_invoice_type
        when lower(invoice_type::text) = 'E31' then 'E31'::sale_invoice_type
        when lower(invoice_type::text) = 'E32' then 'E32'::sale_invoice_type
        when lower(invoice_type::text) = 'E44' then 'E44'::sale_invoice_type
        when lower(invoice_type::text) = 'E45' then 'E45'::sale_invoice_type
        else 'B02'::sale_invoice_type
    end
    );


create type sale_type_payment as enum('CONTADO','CREDITO','TRANSFERENCIA','ANTICIPO','CHEQUE');

alter table sales alter column type_payment drop default;

alter table sales
alter column type_payment type sale_type_payment
using (
    case
        when lower(type_payment::text) = 'CONTADO' then 'CONTADO'::sale_type_payment
        when lower(type_payment::text) = 'CREDITO' then 'CREDITO'::sale_type_payment
        when lower(type_payment::text) = 'TRANSFERENCIA' then 'TRANSFERENCIA'::sale_type_payment
        when lower(type_payment::text) = 'ANTICIPO' then 'ANTICIPO'::sale_type_payment
        when lower(type_payment::text) = 'CHEQUE' then 'CHEQUE'::sale_type_payment
        else 'CONTADO'::sale_type_payment
    end
    );



-- alter table suppliers drop constraint suppliers_type_payment_check;

alter table suppliers
alter column type_payment type sale_type_payment
    using (
    case
        when lower(type_payment::text) = 'CONTADO' then 'CONTADO'::sale_type_payment
        when lower(type_payment::text) = 'CREDITO' then 'CREDITO'::sale_type_payment
        when lower(type_payment::text) = 'TRANSFERENCIA' then 'TRANSFERENCIA'::sale_type_payment
        when lower(type_payment::text) = 'ANTICIPO' then 'ANTICIPO'::sale_type_payment
        when lower(type_payment::text) = 'CHEQUE' then 'CHEQUE'::sale_type_payment
        else 'CONTADO'::sale_type_payment
        end
    );


-- alter table clients drop constraint clients_type_check;
alter table clients alter column type drop default;

