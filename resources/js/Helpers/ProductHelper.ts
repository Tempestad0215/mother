import type {MenuItem} from "primevue/menuitem";

export const productBreadCrumb:MenuItem[] = [
    {
        label: 'Producto',
        url: route('product.create'),
        icon: 'pi pi-bottle'
    },
    {
        label: 'Entrada',
        url: route('product.create'),
        icon: 'pi pi-bottle'
    },
    {
        label: 'Salida',
        url: route('product.create'),
        icon: 'pi pi-bottle'
    },
]
