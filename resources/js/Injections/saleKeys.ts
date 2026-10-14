import { InjectionKey } from 'vue';
import { InertiaForm } from '@inertiajs/vue3';
import { CreateSaleI, SaleUIActionsI, SaleUIStateI } from '@/Interfaces/SaleInterface';

export const saleKey = Symbol() as InjectionKey<InertiaForm<CreateSaleI>>;

export const saleUIKey = Symbol() as InjectionKey<SaleUIStateI>;
export const saleUIActionsKey = Symbol() as InjectionKey<SaleUIActionsI>;
