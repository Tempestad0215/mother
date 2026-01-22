import {MenuItem} from "primevue/menuitem";


export const purchaseBreadCrumb:MenuItem[] = [
    {
        label: "Compra",
        icon: "pi pi-shopping-card",
        url: route('purchase.index')
    },
    {
        label: "Estado Compra",
        icon: "pi pi-shopping-card",
        url: route('purchase.show')
    },
    {
        label: "Entrada de Mercancias",
        icon: "pi pi-shopping-card",
        url: route('purchase.show')
    },
    {
        label: "Salida de Mercancia",
        icon: "pi pi-shopping-card",
        url: route('purchase.show')
    },

]
