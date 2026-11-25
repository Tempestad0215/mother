import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\InvoiceController::sale
 * @see app/Http/Controllers/InvoiceController.php:137
 * @route '/invoice/belt/sale/{sale}'
 */
export const sale = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: sale.url(args, options),
    method: 'get',
})

sale.definition = {
    methods: ["get","head"],
    url: '/invoice/belt/sale/{sale}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::sale
 * @see app/Http/Controllers/InvoiceController.php:137
 * @route '/invoice/belt/sale/{sale}'
 */
sale.url = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { sale: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { sale: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    sale: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        sale: typeof args.sale === 'object'
                ? args.sale.uuid
                : args.sale,
                }

    return sale.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::sale
 * @see app/Http/Controllers/InvoiceController.php:137
 * @route '/invoice/belt/sale/{sale}'
 */
sale.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: sale.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\InvoiceController::sale
 * @see app/Http/Controllers/InvoiceController.php:137
 * @route '/invoice/belt/sale/{sale}'
 */
sale.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: sale.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::note
 * @see app/Http/Controllers/InvoiceController.php:145
 * @route '/invoice/belt/note/{creditNote}'
 */
export const note = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: note.url(args, options),
    method: 'get',
})

note.definition = {
    methods: ["get","head"],
    url: '/invoice/belt/note/{creditNote}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::note
 * @see app/Http/Controllers/InvoiceController.php:145
 * @route '/invoice/belt/note/{creditNote}'
 */
note.url = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { creditNote: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { creditNote: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    creditNote: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        creditNote: typeof args.creditNote === 'object'
                ? args.creditNote.uuid
                : args.creditNote,
                }

    return note.definition.url
            .replace('{creditNote}', parsedArgs.creditNote.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::note
 * @see app/Http/Controllers/InvoiceController.php:145
 * @route '/invoice/belt/note/{creditNote}'
 */
note.get = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: note.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\InvoiceController::note
 * @see app/Http/Controllers/InvoiceController.php:145
 * @route '/invoice/belt/note/{creditNote}'
 */
note.head = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: note.url(args, options),
    method: 'head',
})
const belt = {
    sale: Object.assign(sale, sale),
note: Object.assign(note, note),
}

export default belt