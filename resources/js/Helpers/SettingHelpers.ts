import type {MenuItem} from "primevue/menuitem";

export const itemsSettings:MenuItem[] = [
    {
        label: "Configuracion",
        url: route('setting.index'),
        icon: "pi pi-cog",
    },
    {
        label: "Almacenes",
        url: route('wh.index'),
        icon: "pi pi-warehouse",
    },
    {
        label: "Ramas",
        url: route('branch.index'),
        icon: "pi pi-bookmark",
    },
    {
        label: "Unidades",
        url: route('unit.index'),
        icon: "pi pi-list-check",
    },
    {
        label: "Impuestos",
        url: route('tax.index'),
        icon: "pi pi-wallet",
    },
]
