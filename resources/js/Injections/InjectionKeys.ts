import { InjectionKey, Ref } from 'vue';
import { ProductFormI, ProductTableI } from '@/Interfaces/ProductInterface';

export const formProductKey = Symbol() as InjectionKey<ProductFormI>;
export const taxCurrentValueKey = Symbol('Valor de impuesto seleccionado') as InjectionKey<
  Ref<number>
>;
export const productDataKey = Symbol() as InjectionKey<ProductTableI[]>;
