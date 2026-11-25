import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ReceivingController::index
 * @see app/Http/Controllers/ReceivingController.php:28
 * @route '/purchase/receiving/{supplier}'
 */
export const index = (args: { supplier: string | { uuid: string } } | [supplier: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/purchase/receiving/{supplier}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReceivingController::index
 * @see app/Http/Controllers/ReceivingController.php:28
 * @route '/purchase/receiving/{supplier}'
 */
index.url = (args: { supplier: string | { uuid: string } } | [supplier: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { supplier: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { supplier: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    supplier: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        supplier: typeof args.supplier === 'object'
                ? args.supplier.uuid
                : args.supplier,
                }

    return index.definition.url
            .replace('{supplier}', parsedArgs.supplier.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReceivingController::index
 * @see app/Http/Controllers/ReceivingController.php:28
 * @route '/purchase/receiving/{supplier}'
 */
index.get = (args: { supplier: string | { uuid: string } } | [supplier: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ReceivingController::index
 * @see app/Http/Controllers/ReceivingController.php:28
 * @route '/purchase/receiving/{supplier}'
 */
index.head = (args: { supplier: string | { uuid: string } } | [supplier: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ReceivingController::store
 * @see app/Http/Controllers/ReceivingController.php:67
 * @route '/purchase/receiving'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/purchase/receiving',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ReceivingController::store
 * @see app/Http/Controllers/ReceivingController.php:67
 * @route '/purchase/receiving'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReceivingController::store
 * @see app/Http/Controllers/ReceivingController.php:67
 * @route '/purchase/receiving'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})
const receiving = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
}

export default receiving