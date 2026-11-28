import { TaxBaseI } from '@/Interfaces/TaxInterface';
import { DataTableFeatures } from '@/types/features';
import { Button } from '@components/ui/button';
import { FilePenLine } from '@lucide/vue';
import { createColumnHelper } from '@tanstack/vue-table';
import { Shredder } from 'lucide-vue-next';
import { h } from 'vue';

const columnHelper = createColumnHelper<DataTableFeatures, TaxBaseI>();

export const TaxColumn = columnHelper.columns([
    columnHelper.accessor('name', {
        header: 'Nombre',
        cell: ({ row }) => row.original.name,
    }),
    columnHelper.accessor('description', {
        header: 'Descripcion',
        cell: ({ row }) => row.original.description,
    }),
    columnHelper.display({
        id: 'actions',
        header: 'Acciones',
        cell: ({ row }) =>
            h('div', { class: 'flex items-center justify-end gap-2' }, [
                h(
                    Button,
                    {
                        type: 'button',
                        variant: 'outline',
                        size: 'icon',
                        class: 'h-9 w-9',
                        'aria-label': 'Editar',
                        onClick: () => {},
                    },
                    () => h(FilePenLine, { class: 'h-4 w-4 text-blue-600' })
                ),
                h(
                    Button,
                    {
                        type: 'button',
                        variant: 'outline',
                        size: 'icon',
                        class: 'h-9 w-9 border-destructive/40 hover:bg-destructive/10',
                        'aria-label': 'Eliminar',
                        onClick: () => {},
                    },
                    () => h(Shredder, { class: 'h-4 w-4 text-destructive' })
                ),
            ]),
    }),
]);
