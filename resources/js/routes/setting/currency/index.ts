import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../wayfinder'
import get from './get'
import exchange from './exchange'
/**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/setting/currency'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/setting/currency',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/setting/currency'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/setting/currency'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/setting/currency'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:49
 * @route '/setting/currency'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/setting/currency',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:49
 * @route '/setting/currency'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:49
 * @route '/setting/currency'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AccontCoController::check
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/check-exchange'
 */
export const check = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: check.url(options),
    method: 'get',
})

check.definition = {
    methods: ["get","head"],
    url: '/setting/currency/check-exchange',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AccontCoController::check
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/check-exchange'
 */
check.url = (options?: RouteQueryOptions) => {
    return check.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::check
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/check-exchange'
 */
check.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: check.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AccontCoController::check
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/check-exchange'
 */
check.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: check.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\AccontCoController::destroy
 * @see app/Http/Controllers/AccontCoController.php:92
 * @route '/setting/currency/{currency}'
 */
export const destroy = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/setting/currency/{currency}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\AccontCoController::destroy
 * @see app/Http/Controllers/AccontCoController.php:92
 * @route '/setting/currency/{currency}'
 */
destroy.url = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { currency: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    currency: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        currency: args.currency,
                }

    return destroy.definition.url
            .replace('{currency}', parsedArgs.currency.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::destroy
 * @see app/Http/Controllers/AccontCoController.php:92
 * @route '/setting/currency/{currency}'
 */
destroy.delete = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\AccontCoController::restore
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/restore/{currency}'
 */
export const restore = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: restore.url(args, options),
    method: 'put',
})

restore.definition = {
    methods: ["put"],
    url: '/setting/currency/restore/{currency}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\AccontCoController::restore
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/restore/{currency}'
 */
restore.url = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { currency: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    currency: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        currency: args.currency,
                }

    return restore.definition.url
            .replace('{currency}', parsedArgs.currency.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::restore
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/restore/{currency}'
 */
restore.put = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: restore.url(args, options),
    method: 'put',
})
const currency = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
check: Object.assign(check, check),
get: Object.assign(get, get),
exchange: Object.assign(exchange, exchange),
destroy: Object.assign(destroy, destroy),
restore: Object.assign(restore, restore),
}

export default currency