import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\PriceListController::index
 * @see app/Http/Controllers/PriceListController.php:14
 * @route '/price-list'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/price-list',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PriceListController::index
 * @see app/Http/Controllers/PriceListController.php:14
 * @route '/price-list'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PriceListController::index
 * @see app/Http/Controllers/PriceListController.php:14
 * @route '/price-list'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PriceListController::index
 * @see app/Http/Controllers/PriceListController.php:14
 * @route '/price-list'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PriceListController::store
 * @see app/Http/Controllers/PriceListController.php:21
 * @route '/price-list'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/price-list',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PriceListController::store
 * @see app/Http/Controllers/PriceListController.php:21
 * @route '/price-list'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PriceListController::store
 * @see app/Http/Controllers/PriceListController.php:21
 * @route '/price-list'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PriceListController::show
 * @see app/Http/Controllers/PriceListController.php:28
 * @route '/price-list/{price_list}'
 */
export const show = (args: { price_list: string | { uuid: string } } | [price_list: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/price-list/{price_list}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PriceListController::show
 * @see app/Http/Controllers/PriceListController.php:28
 * @route '/price-list/{price_list}'
 */
show.url = (args: { price_list: string | { uuid: string } } | [price_list: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { price_list: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { price_list: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    price_list: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        price_list: typeof args.price_list === 'object'
                ? args.price_list.uuid
                : args.price_list,
                }

    return show.definition.url
            .replace('{price_list}', parsedArgs.price_list.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PriceListController::show
 * @see app/Http/Controllers/PriceListController.php:28
 * @route '/price-list/{price_list}'
 */
show.get = (args: { price_list: string | { uuid: string } } | [price_list: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PriceListController::show
 * @see app/Http/Controllers/PriceListController.php:28
 * @route '/price-list/{price_list}'
 */
show.head = (args: { price_list: string | { uuid: string } } | [price_list: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PriceListController::update
 * @see app/Http/Controllers/PriceListController.php:43
 * @route '/price-list/{price_list}'
 */
export const update = (args: { price_list: string | { uuid: string } } | [price_list: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/price-list/{price_list}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\PriceListController::update
 * @see app/Http/Controllers/PriceListController.php:43
 * @route '/price-list/{price_list}'
 */
update.url = (args: { price_list: string | { uuid: string } } | [price_list: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { price_list: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { price_list: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    price_list: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        price_list: typeof args.price_list === 'object'
                ? args.price_list.uuid
                : args.price_list,
                }

    return update.definition.url
            .replace('{price_list}', parsedArgs.price_list.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PriceListController::update
 * @see app/Http/Controllers/PriceListController.php:43
 * @route '/price-list/{price_list}'
 */
update.put = (args: { price_list: string | { uuid: string } } | [price_list: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\PriceListController::update
 * @see app/Http/Controllers/PriceListController.php:43
 * @route '/price-list/{price_list}'
 */
update.patch = (args: { price_list: string | { uuid: string } } | [price_list: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\PriceListController::destroy
 * @see app/Http/Controllers/PriceListController.php:52
 * @route '/price-list/{price_list}'
 */
export const destroy = (args: { price_list: string | { uuid: string } } | [price_list: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/price-list/{price_list}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\PriceListController::destroy
 * @see app/Http/Controllers/PriceListController.php:52
 * @route '/price-list/{price_list}'
 */
destroy.url = (args: { price_list: string | { uuid: string } } | [price_list: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { price_list: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { price_list: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    price_list: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        price_list: typeof args.price_list === 'object'
                ? args.price_list.uuid
                : args.price_list,
                }

    return destroy.definition.url
            .replace('{price_list}', parsedArgs.price_list.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PriceListController::destroy
 * @see app/Http/Controllers/PriceListController.php:52
 * @route '/price-list/{price_list}'
 */
destroy.delete = (args: { price_list: string | { uuid: string } } | [price_list: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\PriceListController::productShow
 * @see app/Http/Controllers/PriceListController.php:38
 * @route '/price-list/product/{product}'
 */
export const productShow = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: productShow.url(args, options),
    method: 'get',
})

productShow.definition = {
    methods: ["get","head"],
    url: '/price-list/product/{product}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PriceListController::productShow
 * @see app/Http/Controllers/PriceListController.php:38
 * @route '/price-list/product/{product}'
 */
productShow.url = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return productShow.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PriceListController::productShow
 * @see app/Http/Controllers/PriceListController.php:38
 * @route '/price-list/product/{product}'
 */
productShow.get = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: productShow.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PriceListController::productShow
 * @see app/Http/Controllers/PriceListController.php:38
 * @route '/price-list/product/{product}'
 */
productShow.head = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: productShow.url(args, options),
    method: 'head',
})
const PriceListController = { index, store, show, update, destroy, productShow }

export default PriceListController