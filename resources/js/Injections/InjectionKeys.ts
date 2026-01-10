import {InjectionKey} from "vue";
import {ProductBaseI, ProductFormI} from "@/Interfaces/ProductInterface";


export const formProductKey = Symbol() as InjectionKey<ProductFormI>
export const productDataKey = Symbol() as InjectionKey<ProductBaseI[]>
