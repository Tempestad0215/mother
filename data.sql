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
