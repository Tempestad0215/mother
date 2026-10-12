import { inject } from 'vue';
import { createProductKey, productEditKey } from '@/Injections/InjectionKeys';
import { ProductTableI } from '@/Interfaces/ProductInterface';

export function useProductDialog() {
  const createProduct = inject(createProductKey);
  const selectedProduct = inject(productEditKey);

  if (!createProduct || !selectedProduct) {
    throw new Error('[useProductDialog] Falta provide. ¿Estás dentro de <Products/Register>?');
  }
  const openEdit = (product: ProductTableI) => {
    selectedProduct.value = product;
    createProduct.value = true;
  };

  const openCreate = () => {
    selectedProduct.value = null;
    createProduct.value = true;
  };

  const close = () => {
    selectedProduct.value = null;
    createProduct.value = false;
  };

  return { createProduct, selectedProduct, openEdit, openCreate, close };
}
