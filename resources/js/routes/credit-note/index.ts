import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\CreditNoteController::index
* @see app/Http/Controllers/CreditNoteController.php:31
* @route '/sale/credit-note'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/sale/credit-note',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CreditNoteController::index
* @see app/Http/Controllers/CreditNoteController.php:31
* @route '/sale/credit-note'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CreditNoteController::index
* @see app/Http/Controllers/CreditNoteController.php:31
* @route '/sale/credit-note'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::index
* @see app/Http/Controllers/CreditNoteController.php:31
* @route '/sale/credit-note'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\CreditNoteController::index
* @see app/Http/Controllers/CreditNoteController.php:31
* @route '/sale/credit-note'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::index
* @see app/Http/Controllers/CreditNoteController.php:31
* @route '/sale/credit-note'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::index
* @see app/Http/Controllers/CreditNoteController.php:31
* @route '/sale/credit-note'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\CreditNoteController::show
* @see app/Http/Controllers/CreditNoteController.php:148
* @route '/sale/credit-note/show'
*/
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/sale/credit-note/show',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CreditNoteController::show
* @see app/Http/Controllers/CreditNoteController.php:148
* @route '/sale/credit-note/show'
*/
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CreditNoteController::show
* @see app/Http/Controllers/CreditNoteController.php:148
* @route '/sale/credit-note/show'
*/
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::show
* @see app/Http/Controllers/CreditNoteController.php:148
* @route '/sale/credit-note/show'
*/
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\CreditNoteController::show
* @see app/Http/Controllers/CreditNoteController.php:148
* @route '/sale/credit-note/show'
*/
const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::show
* @see app/Http/Controllers/CreditNoteController.php:148
* @route '/sale/credit-note/show'
*/
showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::show
* @see app/Http/Controllers/CreditNoteController.php:148
* @route '/sale/credit-note/show'
*/
showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\CreditNoteController::balance
* @see app/Http/Controllers/CreditNoteController.php:180
* @route '/sale/credit-note/get/balance/{code}'
*/
export const balance = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: balance.url(args, options),
    method: 'get',
})

balance.definition = {
    methods: ["get","head"],
    url: '/sale/credit-note/get/balance/{code}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CreditNoteController::balance
* @see app/Http/Controllers/CreditNoteController.php:180
* @route '/sale/credit-note/get/balance/{code}'
*/
balance.url = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { code: args }
    }

    if (Array.isArray(args)) {
        args = {
            code: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        code: args.code,
    }

    return balance.definition.url
            .replace('{code}', parsedArgs.code.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\CreditNoteController::balance
* @see app/Http/Controllers/CreditNoteController.php:180
* @route '/sale/credit-note/get/balance/{code}'
*/
balance.get = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: balance.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::balance
* @see app/Http/Controllers/CreditNoteController.php:180
* @route '/sale/credit-note/get/balance/{code}'
*/
balance.head = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: balance.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\CreditNoteController::balance
* @see app/Http/Controllers/CreditNoteController.php:180
* @route '/sale/credit-note/get/balance/{code}'
*/
const balanceForm = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: balance.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::balance
* @see app/Http/Controllers/CreditNoteController.php:180
* @route '/sale/credit-note/get/balance/{code}'
*/
balanceForm.get = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: balance.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::balance
* @see app/Http/Controllers/CreditNoteController.php:180
* @route '/sale/credit-note/get/balance/{code}'
*/
balanceForm.head = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: balance.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

balance.form = balanceForm

/**
* @see \App\Http\Controllers\CreditNoteController::get
* @see app/Http/Controllers/CreditNoteController.php:125
* @route '/sale/credit-note/get/{code}'
*/
export const get = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: get.url(args, options),
    method: 'get',
})

get.definition = {
    methods: ["get","head"],
    url: '/sale/credit-note/get/{code}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CreditNoteController::get
* @see app/Http/Controllers/CreditNoteController.php:125
* @route '/sale/credit-note/get/{code}'
*/
get.url = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { code: args }
    }

    if (Array.isArray(args)) {
        args = {
            code: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        code: args.code,
    }

    return get.definition.url
            .replace('{code}', parsedArgs.code.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\CreditNoteController::get
* @see app/Http/Controllers/CreditNoteController.php:125
* @route '/sale/credit-note/get/{code}'
*/
get.get = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: get.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::get
* @see app/Http/Controllers/CreditNoteController.php:125
* @route '/sale/credit-note/get/{code}'
*/
get.head = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: get.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\CreditNoteController::get
* @see app/Http/Controllers/CreditNoteController.php:125
* @route '/sale/credit-note/get/{code}'
*/
const getForm = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: get.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::get
* @see app/Http/Controllers/CreditNoteController.php:125
* @route '/sale/credit-note/get/{code}'
*/
getForm.get = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: get.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\CreditNoteController::get
* @see app/Http/Controllers/CreditNoteController.php:125
* @route '/sale/credit-note/get/{code}'
*/
getForm.head = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: get.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

get.form = getForm

/**
* @see \App\Http\Controllers\CreditNoteController::store
* @see app/Http/Controllers/CreditNoteController.php:108
* @route '/sale/credit-note/{sale}'
*/
export const store = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/sale/credit-note/{sale}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\CreditNoteController::store
* @see app/Http/Controllers/CreditNoteController.php:108
* @route '/sale/credit-note/{sale}'
*/
store.url = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\CreditNoteController::store
* @see app/Http/Controllers/CreditNoteController.php:108
* @route '/sale/credit-note/{sale}'
*/
store.post = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\CreditNoteController::store
* @see app/Http/Controllers/CreditNoteController.php:108
* @route '/sale/credit-note/{sale}'
*/
const storeForm = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\CreditNoteController::store
* @see app/Http/Controllers/CreditNoteController.php:108
* @route '/sale/credit-note/{sale}'
*/
storeForm.post = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

const creditNote = {
    index: Object.assign(index, index),
    show: Object.assign(show, show),
    balance: Object.assign(balance, balance),
    get: Object.assign(get, get),
    store: Object.assign(store, store),
}

export default creditNote