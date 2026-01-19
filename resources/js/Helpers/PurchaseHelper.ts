import {MenuItem} from "primevue/menuitem";


export const purchaseBreadCrumb:MenuItem[] = [
    {
        label: "Compra",
        icon: "pi pi-shopping-card",
        url: route('purchase.index')
    },
    {
        label: "Mostrar",
        icon: "pi pi-shopping-card",
        url: route('purchase.show')
    },

]
