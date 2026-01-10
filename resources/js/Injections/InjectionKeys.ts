import {InjectionKey} from "vue";
import {ProductFormI} from "@/Interfaces/ProductInterface";


export const formProductKey = Symbol() as InjectionKey<ProductFormI>
