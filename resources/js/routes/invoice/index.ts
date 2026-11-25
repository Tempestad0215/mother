import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
import belt from './belt'
import cashregister from './cashregister'
/**
* @see \App\Http\Controllers\InvoiceController::sale
 * @see app/Http/Controllers/InvoiceController.php:75
 * @route '/invoice/sale/{sale}'
 */
export const sale = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: sale.url(args, options),
    method: 'get',
})

sale.definition = {
    methods: ["get","head"],
    url: '/invoice/sale/{sale}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::sale
 * @see app/Http/Controllers/InvoiceController.php:75
 * @route '/invoice/sale/{sale}'
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
 * @see app/Http/Controllers/InvoiceController.php:75
 * @route '/invoice/sale/{sale}'
 */
sale.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: sale.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\InvoiceController::sale
 * @see app/Http/Controllers/InvoiceController.php:75
 * @route '/invoice/sale/{sale}'
 */
sale.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: sale.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::quote
 * @see app/Http/Controllers/InvoiceController.php:56
 * @route '/invoice/quote/{sale}'
 */
export const quote = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: quote.url(args, options),
    method: 'get',
})

quote.definition = {
    methods: ["get","head"],
    url: '/invoice/quote/{sale}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::quote
 * @see app/Http/Controllers/InvoiceController.php:56
 * @route '/invoice/quote/{sale}'
 */
quote.url = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return quote.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::quote
 * @see app/Http/Controllers/InvoiceController.php:56
 * @route '/invoice/quote/{sale}'
 */
quote.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: quote.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\InvoiceController::quote
 * @see app/Http/Controllers/InvoiceController.php:56
 * @route '/invoice/quote/{sale}'
 */
quote.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: quote.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::creditNote
 * @see app/Http/Controllers/InvoiceController.php:95
 * @route '/invoice/credit-note/{creditNote}'
 */
export const creditNote = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: creditNote.url(args, options),
    method: 'get',
})

creditNote.definition = {
    methods: ["get","head"],
    url: '/invoice/credit-note/{creditNote}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::creditNote
 * @see app/Http/Controllers/InvoiceController.php:95
 * @route '/invoice/credit-note/{creditNote}'
 */
creditNote.url = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return creditNote.definition.url
            .replace('{creditNote}', parsedArgs.creditNote.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::creditNote
 * @see app/Http/Controllers/InvoiceController.php:95
 * @route '/invoice/credit-note/{creditNote}'
 */
creditNote.get = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: creditNote.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\InvoiceController::creditNote
 * @see app/Http/Controllers/InvoiceController.php:95
 * @route '/invoice/credit-note/{creditNote}'
 */
creditNote.head = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: creditNote.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::getA
 * @see app/Http/Controllers/InvoiceController.php:43
 * @route '/invoice/getA/{sale}'
 */
export const getA = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getA.url(args, options),
    method: 'get',
})

getA.definition = {
    methods: ["get","head"],
    url: '/invoice/getA/{sale}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::getA
 * @see app/Http/Controllers/InvoiceController.php:43
 * @route '/invoice/getA/{sale}'
 */
getA.url = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return getA.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::getA
 * @see app/Http/Controllers/InvoiceController.php:43
 * @route '/invoice/getA/{sale}'
 */
getA.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getA.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\InvoiceController::getA
 * @see app/Http/Controllers/InvoiceController.php:43
 * @route '/invoice/getA/{sale}'
 */
getA.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getA.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::label
 * @see app/Http/Controllers/InvoiceController.php:157
 * @route '/invoice/label/{product}'
 */
export const label = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: label.url(args, options),
    method: 'get',
})

label.definition = {
    methods: ["get","head"],
    url: '/invoice/label/{product}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::label
 * @see app/Http/Controllers/InvoiceController.php:157
 * @route '/invoice/label/{product}'
 */
label.url = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { product: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { product: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    product: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        product: typeof args.product === 'object'
                ? args.product.uuid
                : args.product,
                }

    return label.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::label
 * @see app/Http/Controllers/InvoiceController.php:157
 * @route '/invoice/label/{product}'
 */
label.get = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: label.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\InvoiceController::label
 * @see app/Http/Controllers/InvoiceController.php:157
 * @route '/invoice/label/{product}'
 */
label.head = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: label.url(args, options),
    method: 'head',
})
const invoice = {
    sale: Object.assign(sale, sale),
quote: Object.assign(quote, quote),
creditNote: Object.assign(creditNote, creditNote),
belt: Object.assign(belt, belt),
getA: Object.assign(getA, getA),
label: Object.assign(label, label),
cashregister: Object.assign(cashregister, cashregister),
}

export default invoice