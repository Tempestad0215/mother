import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
import get6a10bd from './get'
/**
* @see \App\Http\Controllers\ProductController::index
 * @see app/Http/Controllers/ProductController.php:58
 * @route '/product'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/product',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProductController::index
 * @see app/Http/Controllers/ProductController.php:58
 * @route '/product'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProductController::index
 * @see app/Http/Controllers/ProductController.php:58
 * @route '/product'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ProductController::index
 * @see app/Http/Controllers/ProductController.php:58
 * @route '/product'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProductController::store
 * @see app/Http/Controllers/ProductController.php:118
 * @route '/product'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/product',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ProductController::store
 * @see app/Http/Controllers/ProductController.php:118
 * @route '/product'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProductController::store
 * @see app/Http/Controllers/ProductController.php:118
 * @route '/product'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ProductController::show
 * @see app/Http/Controllers/ProductController.php:155
 * @route '/product/{product}'
 */
export const show = (args: { product: string | number } | [product: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/product/{product}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProductController::show
 * @see app/Http/Controllers/ProductController.php:155
 * @route '/product/{product}'
 */
show.url = (args: { product: string | number } | [product: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { product: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    product: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        product: args.product,
                }

    return show.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProductController::show
 * @see app/Http/Controllers/ProductController.php:155
 * @route '/product/{product}'
 */
show.get = (args: { product: string | number } | [product: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ProductController::show
 * @see app/Http/Controllers/ProductController.php:155
 * @route '/product/{product}'
 */
show.head = (args: { product: string | number } | [product: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProductController::update
 * @see app/Http/Controllers/ProductController.php:196
 * @route '/product/{product}'
 */
export const update = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/product/{product}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\ProductController::update
 * @see app/Http/Controllers/ProductController.php:196
 * @route '/product/{product}'
 */
update.url = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProductController::update
 * @see app/Http/Controllers/ProductController.php:196
 * @route '/product/{product}'
 */
update.put = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\ProductController::update
 * @see app/Http/Controllers/ProductController.php:196
 * @route '/product/{product}'
 */
update.patch = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\ProductController::destroy
 * @see app/Http/Controllers/ProductController.php:233
 * @route '/product/{product}'
 */
export const destroy = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/product/{product}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ProductController::destroy
 * @see app/Http/Controllers/ProductController.php:233
 * @route '/product/{product}'
 */
destroy.url = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProductController::destroy
 * @see app/Http/Controllers/ProductController.php:233
 * @route '/product/{product}'
 */
destroy.delete = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\ProductController::destroy
 * @see app/Http/Controllers/ProductController.php:233
 * @route '/product/delete/{product}'
 */
export const destroy = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: destroy.url(args, options),
    method: 'patch',
})

destroy.definition = {
    methods: ["patch"],
    url: '/product/delete/{product}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\ProductController::destroy
 * @see app/Http/Controllers/ProductController.php:233
 * @route '/product/delete/{product}'
 */
destroy.url = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProductController::destroy
 * @see app/Http/Controllers/ProductController.php:233
 * @route '/product/delete/{product}'
 */
destroy.patch = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: destroy.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\ProductController::getLabel
 * @see app/Http/Controllers/ProductController.php:342
 * @route '/product/get-label/{code}'
 */
export const getLabel = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getLabel.url(args, options),
    method: 'get',
})

getLabel.definition = {
    methods: ["get","head"],
    url: '/product/get-label/{code}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProductController::getLabel
 * @see app/Http/Controllers/ProductController.php:342
 * @route '/product/get-label/{code}'
 */
getLabel.url = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return getLabel.definition.url
            .replace('{code}', parsedArgs.code.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProductController::getLabel
 * @see app/Http/Controllers/ProductController.php:342
 * @route '/product/get-label/{code}'
 */
getLabel.get = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getLabel.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ProductController::getLabel
 * @see app/Http/Controllers/ProductController.php:342
 * @route '/product/get-label/{code}'
 */
getLabel.head = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getLabel.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProductController::get
 * @see app/Http/Controllers/ProductController.php:314
 * @route '/product/get'
 */
export const get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: get.url(options),
    method: 'get',
})

get.definition = {
    methods: ["get","head"],
    url: '/product/get',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProductController::get
 * @see app/Http/Controllers/ProductController.php:314
 * @route '/product/get'
 */
get.url = (options?: RouteQueryOptions) => {
    return get.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProductController::get
 * @see app/Http/Controllers/ProductController.php:314
 * @route '/product/get'
 */
get.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: get.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ProductController::get
 * @see app/Http/Controllers/ProductController.php:314
 * @route '/product/get'
 */
get.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: get.url(options),
    method: 'head',
})
const product = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
show: Object.assign(show, show),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
getLabel: Object.assign(getLabel, getLabel),
get: Object.assign(get, get6a10bd),
}

export default product