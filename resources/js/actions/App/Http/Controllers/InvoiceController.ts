import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\InvoiceController::getSaleInvoice
* @see app/Http/Controllers/InvoiceController.php:75
* @route '/invoice/sale/{sale}'
*/
export const getSaleInvoice = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getSaleInvoice.url(args, options),
    method: 'get',
})

getSaleInvoice.definition = {
    methods: ["get","head"],
    url: '/invoice/sale/{sale}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::getSaleInvoice
* @see app/Http/Controllers/InvoiceController.php:75
* @route '/invoice/sale/{sale}'
*/
getSaleInvoice.url = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return getSaleInvoice.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::getSaleInvoice
* @see app/Http/Controllers/InvoiceController.php:75
* @route '/invoice/sale/{sale}'
*/
getSaleInvoice.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getSaleInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getSaleInvoice
* @see app/Http/Controllers/InvoiceController.php:75
* @route '/invoice/sale/{sale}'
*/
getSaleInvoice.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getSaleInvoice.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::getSaleInvoice
* @see app/Http/Controllers/InvoiceController.php:75
* @route '/invoice/sale/{sale}'
*/
const getSaleInvoiceForm = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getSaleInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getSaleInvoice
* @see app/Http/Controllers/InvoiceController.php:75
* @route '/invoice/sale/{sale}'
*/
getSaleInvoiceForm.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getSaleInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getSaleInvoice
* @see app/Http/Controllers/InvoiceController.php:75
* @route '/invoice/sale/{sale}'
*/
getSaleInvoiceForm.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getSaleInvoice.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

getSaleInvoice.form = getSaleInvoiceForm

/**
* @see \App\Http\Controllers\InvoiceController::getQuoteInvoice
* @see app/Http/Controllers/InvoiceController.php:56
* @route '/invoice/quote/{sale}'
*/
export const getQuoteInvoice = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getQuoteInvoice.url(args, options),
    method: 'get',
})

getQuoteInvoice.definition = {
    methods: ["get","head"],
    url: '/invoice/quote/{sale}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::getQuoteInvoice
* @see app/Http/Controllers/InvoiceController.php:56
* @route '/invoice/quote/{sale}'
*/
getQuoteInvoice.url = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return getQuoteInvoice.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::getQuoteInvoice
* @see app/Http/Controllers/InvoiceController.php:56
* @route '/invoice/quote/{sale}'
*/
getQuoteInvoice.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getQuoteInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getQuoteInvoice
* @see app/Http/Controllers/InvoiceController.php:56
* @route '/invoice/quote/{sale}'
*/
getQuoteInvoice.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getQuoteInvoice.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::getQuoteInvoice
* @see app/Http/Controllers/InvoiceController.php:56
* @route '/invoice/quote/{sale}'
*/
const getQuoteInvoiceForm = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getQuoteInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getQuoteInvoice
* @see app/Http/Controllers/InvoiceController.php:56
* @route '/invoice/quote/{sale}'
*/
getQuoteInvoiceForm.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getQuoteInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getQuoteInvoice
* @see app/Http/Controllers/InvoiceController.php:56
* @route '/invoice/quote/{sale}'
*/
getQuoteInvoiceForm.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getQuoteInvoice.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

getQuoteInvoice.form = getQuoteInvoiceForm

/**
* @see \App\Http\Controllers\InvoiceController::getCreditNoteInvoice
* @see app/Http/Controllers/InvoiceController.php:95
* @route '/invoice/credit-note/{creditNote}'
*/
export const getCreditNoteInvoice = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getCreditNoteInvoice.url(args, options),
    method: 'get',
})

getCreditNoteInvoice.definition = {
    methods: ["get","head"],
    url: '/invoice/credit-note/{creditNote}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::getCreditNoteInvoice
* @see app/Http/Controllers/InvoiceController.php:95
* @route '/invoice/credit-note/{creditNote}'
*/
getCreditNoteInvoice.url = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return getCreditNoteInvoice.definition.url
            .replace('{creditNote}', parsedArgs.creditNote.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::getCreditNoteInvoice
* @see app/Http/Controllers/InvoiceController.php:95
* @route '/invoice/credit-note/{creditNote}'
*/
getCreditNoteInvoice.get = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getCreditNoteInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getCreditNoteInvoice
* @see app/Http/Controllers/InvoiceController.php:95
* @route '/invoice/credit-note/{creditNote}'
*/
getCreditNoteInvoice.head = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getCreditNoteInvoice.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::getCreditNoteInvoice
* @see app/Http/Controllers/InvoiceController.php:95
* @route '/invoice/credit-note/{creditNote}'
*/
const getCreditNoteInvoiceForm = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getCreditNoteInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getCreditNoteInvoice
* @see app/Http/Controllers/InvoiceController.php:95
* @route '/invoice/credit-note/{creditNote}'
*/
getCreditNoteInvoiceForm.get = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getCreditNoteInvoice.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getCreditNoteInvoice
* @see app/Http/Controllers/InvoiceController.php:95
* @route '/invoice/credit-note/{creditNote}'
*/
getCreditNoteInvoiceForm.head = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getCreditNoteInvoice.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

getCreditNoteInvoice.form = getCreditNoteInvoiceForm

/**
* @see \App\Http\Controllers\InvoiceController::beltSale
* @see app/Http/Controllers/InvoiceController.php:137
* @route '/invoice/belt/sale/{sale}'
*/
export const beltSale = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: beltSale.url(args, options),
    method: 'get',
})

beltSale.definition = {
    methods: ["get","head"],
    url: '/invoice/belt/sale/{sale}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::beltSale
* @see app/Http/Controllers/InvoiceController.php:137
* @route '/invoice/belt/sale/{sale}'
*/
beltSale.url = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return beltSale.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::beltSale
* @see app/Http/Controllers/InvoiceController.php:137
* @route '/invoice/belt/sale/{sale}'
*/
beltSale.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: beltSale.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::beltSale
* @see app/Http/Controllers/InvoiceController.php:137
* @route '/invoice/belt/sale/{sale}'
*/
beltSale.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: beltSale.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::beltSale
* @see app/Http/Controllers/InvoiceController.php:137
* @route '/invoice/belt/sale/{sale}'
*/
const beltSaleForm = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: beltSale.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::beltSale
* @see app/Http/Controllers/InvoiceController.php:137
* @route '/invoice/belt/sale/{sale}'
*/
beltSaleForm.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: beltSale.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::beltSale
* @see app/Http/Controllers/InvoiceController.php:137
* @route '/invoice/belt/sale/{sale}'
*/
beltSaleForm.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: beltSale.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

beltSale.form = beltSaleForm

/**
* @see \App\Http\Controllers\InvoiceController::beltNote
* @see app/Http/Controllers/InvoiceController.php:145
* @route '/invoice/belt/note/{creditNote}'
*/
export const beltNote = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: beltNote.url(args, options),
    method: 'get',
})

beltNote.definition = {
    methods: ["get","head"],
    url: '/invoice/belt/note/{creditNote}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::beltNote
* @see app/Http/Controllers/InvoiceController.php:145
* @route '/invoice/belt/note/{creditNote}'
*/
beltNote.url = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return beltNote.definition.url
            .replace('{creditNote}', parsedArgs.creditNote.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::beltNote
* @see app/Http/Controllers/InvoiceController.php:145
* @route '/invoice/belt/note/{creditNote}'
*/
beltNote.get = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: beltNote.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::beltNote
* @see app/Http/Controllers/InvoiceController.php:145
* @route '/invoice/belt/note/{creditNote}'
*/
beltNote.head = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: beltNote.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::beltNote
* @see app/Http/Controllers/InvoiceController.php:145
* @route '/invoice/belt/note/{creditNote}'
*/
const beltNoteForm = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: beltNote.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::beltNote
* @see app/Http/Controllers/InvoiceController.php:145
* @route '/invoice/belt/note/{creditNote}'
*/
beltNoteForm.get = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: beltNote.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::beltNote
* @see app/Http/Controllers/InvoiceController.php:145
* @route '/invoice/belt/note/{creditNote}'
*/
beltNoteForm.head = (args: { creditNote: string | { uuid: string } } | [creditNote: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: beltNote.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

beltNote.form = beltNoteForm

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
* @see \App\Http\Controllers\InvoiceController::getA
* @see app/Http/Controllers/InvoiceController.php:43
* @route '/invoice/getA/{sale}'
*/
const getAForm = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getA.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getA
* @see app/Http/Controllers/InvoiceController.php:43
* @route '/invoice/getA/{sale}'
*/
getAForm.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getA.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getA
* @see app/Http/Controllers/InvoiceController.php:43
* @route '/invoice/getA/{sale}'
*/
getAForm.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getA.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

getA.form = getAForm

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

/**
* @see \App\Http\Controllers\InvoiceController::label
* @see app/Http/Controllers/InvoiceController.php:157
* @route '/invoice/label/{product}'
*/
const labelForm = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: label.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::label
* @see app/Http/Controllers/InvoiceController.php:157
* @route '/invoice/label/{product}'
*/
labelForm.get = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: label.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::label
* @see app/Http/Controllers/InvoiceController.php:157
* @route '/invoice/label/{product}'
*/
labelForm.head = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: label.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

label.form = labelForm

/**
* @see \App\Http\Controllers\InvoiceController::getCashRegisterClose
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
export const getCashRegisterClose = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getCashRegisterClose.url(args, options),
    method: 'get',
})

getCashRegisterClose.definition = {
    methods: ["get","head"],
    url: '/invoice/cashregister/close/{cashRegister}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::getCashRegisterClose
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
getCashRegisterClose.url = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { cashRegister: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
        args = { cashRegister: args.uuid }
    }

    if (Array.isArray(args)) {
        args = {
            cashRegister: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        cashRegister: typeof args.cashRegister === 'object'
        ? args.cashRegister.uuid
        : args.cashRegister,
    }

    return getCashRegisterClose.definition.url
            .replace('{cashRegister}', parsedArgs.cashRegister.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::getCashRegisterClose
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
getCashRegisterClose.get = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getCashRegisterClose.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getCashRegisterClose
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
getCashRegisterClose.head = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getCashRegisterClose.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::getCashRegisterClose
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
const getCashRegisterCloseForm = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getCashRegisterClose.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getCashRegisterClose
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
getCashRegisterCloseForm.get = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getCashRegisterClose.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::getCashRegisterClose
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
getCashRegisterCloseForm.head = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getCashRegisterClose.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

getCashRegisterClose.form = getCashRegisterCloseForm

const InvoiceController = { getSaleInvoice, getQuoteInvoice, getCreditNoteInvoice, beltSale, beltNote, getA, label, getCashRegisterClose }

export default InvoiceController