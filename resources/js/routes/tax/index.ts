import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\TaxController::index
 * @see app/Http/Controllers/TaxController.php:12
 * @route '/tax'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/tax',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TaxController::index
 * @see app/Http/Controllers/TaxController.php:12
 * @route '/tax'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaxController::index
 * @see app/Http/Controllers/TaxController.php:12
 * @route '/tax'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\TaxController::index
 * @see app/Http/Controllers/TaxController.php:12
 * @route '/tax'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\TaxController::store
 * @see app/Http/Controllers/TaxController.php:20
 * @route '/tax'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/tax',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\TaxController::store
 * @see app/Http/Controllers/TaxController.php:20
 * @route '/tax'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaxController::store
 * @see app/Http/Controllers/TaxController.php:20
 * @route '/tax'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\TaxController::show
 * @see app/Http/Controllers/TaxController.php:0
 * @route '/tax/{tax}'
 */
export const show = (args: { tax: string | number } | [tax: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/tax/{tax}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\TaxController::show
 * @see app/Http/Controllers/TaxController.php:0
 * @route '/tax/{tax}'
 */
show.url = (args: { tax: string | number } | [tax: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tax: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    tax: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tax: args.tax,
                }

    return show.definition.url
            .replace('{tax}', parsedArgs.tax.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaxController::show
 * @see app/Http/Controllers/TaxController.php:0
 * @route '/tax/{tax}'
 */
show.get = (args: { tax: string | number } | [tax: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\TaxController::show
 * @see app/Http/Controllers/TaxController.php:0
 * @route '/tax/{tax}'
 */
show.head = (args: { tax: string | number } | [tax: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\TaxController::update
 * @see app/Http/Controllers/TaxController.php:33
 * @route '/tax/{tax}'
 */
export const update = (args: { tax: string | { uuid: string } } | [tax: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/tax/{tax}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\TaxController::update
 * @see app/Http/Controllers/TaxController.php:33
 * @route '/tax/{tax}'
 */
update.url = (args: { tax: string | { uuid: string } } | [tax: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tax: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { tax: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tax: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tax: typeof args.tax === 'object'
                ? args.tax.uuid
                : args.tax,
                }

    return update.definition.url
            .replace('{tax}', parsedArgs.tax.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaxController::update
 * @see app/Http/Controllers/TaxController.php:33
 * @route '/tax/{tax}'
 */
update.put = (args: { tax: string | { uuid: string } } | [tax: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\TaxController::update
 * @see app/Http/Controllers/TaxController.php:33
 * @route '/tax/{tax}'
 */
update.patch = (args: { tax: string | { uuid: string } } | [tax: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\TaxController::destroy
 * @see app/Http/Controllers/TaxController.php:46
 * @route '/tax/{tax}'
 */
export const destroy = (args: { tax: string | { uuid: string } } | [tax: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/tax/{tax}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\TaxController::destroy
 * @see app/Http/Controllers/TaxController.php:46
 * @route '/tax/{tax}'
 */
destroy.url = (args: { tax: string | { uuid: string } } | [tax: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { tax: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { tax: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    tax: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        tax: typeof args.tax === 'object'
                ? args.tax.uuid
                : args.tax,
                }

    return destroy.definition.url
            .replace('{tax}', parsedArgs.tax.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\TaxController::destroy
 * @see app/Http/Controllers/TaxController.php:46
 * @route '/tax/{tax}'
 */
destroy.delete = (args: { tax: string | { uuid: string } } | [tax: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})
const tax = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
show: Object.assign(show, show),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default tax