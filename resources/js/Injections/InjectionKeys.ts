import { InjectionKey, Ref } from 'vue';
import { ProductFormI, ProductTableI } from '@/Interfaces/ProductInterface';

export const formProductKey = Symbol() as InjectionKey<ProductFormI>;
export const taxCurrentValueKey = Symbol('Valor de impuesto seleccionado') as InjectionKey<
  Ref<number>
>;
export const productDataKey = Symbol() as InjectionKey<ProductTableI[]>;

export const productEditKey = Symbol(
  'Para editar los datos de productos seleccionado'
) as InjectionKey<Ref<ProductTableI | null>>;

export const createProductKey = Symbol(
  'Abrir la ventana emergente de crear productos'
) as InjectionKey<Ref<boolean>>;
