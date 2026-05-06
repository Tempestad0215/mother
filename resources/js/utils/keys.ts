import { InjectionKey } from 'vue';
import { InertiaForm } from '@inertiajs/vue3';
import { CreateSaleI } from '@/Interfaces/SaleInterface';

export const saleKey = Symbol() as InjectionKey<InertiaForm<CreateSaleI>>;
