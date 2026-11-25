import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\WarehouseController::warehouseProduct
 * @see app/Http/Controllers/WarehouseController.php:100
 * @route '/warehouse/product/{product}'
 */
export const warehouseProduct = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: warehouseProduct.url(args, options),
    method: 'get',
})

warehouseProduct.definition = {
    methods: ["get","head"],
    url: '/warehouse/product/{product}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\WarehouseController::warehouseProduct
 * @see app/Http/Controllers/WarehouseController.php:100
 * @route '/warehouse/product/{product}'
 */
warehouseProduct.url = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return warehouseProduct.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\WarehouseController::warehouseProduct
 * @see app/Http/Controllers/WarehouseController.php:100
 * @route '/warehouse/product/{product}'
 */
warehouseProduct.get = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: warehouseProduct.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\WarehouseController::warehouseProduct
 * @see app/Http/Controllers/WarehouseController.php:100
 * @route '/warehouse/product/{product}'
 */
warehouseProduct.head = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: warehouseProduct.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\WarehouseController::index
 * @see app/Http/Controllers/WarehouseController.php:38
 * @route '/setting/warehouse'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/setting/warehouse',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\WarehouseController::index
 * @see app/Http/Controllers/WarehouseController.php:38
 * @route '/setting/warehouse'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\WarehouseController::index
 * @see app/Http/Controllers/WarehouseController.php:38
 * @route '/setting/warehouse'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\WarehouseController::index
 * @see app/Http/Controllers/WarehouseController.php:38
 * @route '/setting/warehouse'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\WarehouseController::store
 * @see app/Http/Controllers/WarehouseController.php:52
 * @route '/setting/warehouse'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/setting/warehouse',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\WarehouseController::store
 * @see app/Http/Controllers/WarehouseController.php:52
 * @route '/setting/warehouse'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\WarehouseController::store
 * @see app/Http/Controllers/WarehouseController.php:52
 * @route '/setting/warehouse'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\WarehouseController::show
 * @see app/Http/Controllers/WarehouseController.php:70
 * @route '/setting/warehouse/{warehouse}'
 */
export const show = (args: { warehouse: string | { uuid: string } } | [warehouse: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/setting/warehouse/{warehouse}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\WarehouseController::show
 * @see app/Http/Controllers/WarehouseController.php:70
 * @route '/setting/warehouse/{warehouse}'
 */
show.url = (args: { warehouse: string | { uuid: string } } | [warehouse: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { warehouse: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { warehouse: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    warehouse: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        warehouse: typeof args.warehouse === 'object'
                ? args.warehouse.uuid
                : args.warehouse,
                }

    return show.definition.url
            .replace('{warehouse}', parsedArgs.warehouse.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\WarehouseController::show
 * @see app/Http/Controllers/WarehouseController.php:70
 * @route '/setting/warehouse/{warehouse}'
 */
show.get = (args: { warehouse: string | { uuid: string } } | [warehouse: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\WarehouseController::show
 * @see app/Http/Controllers/WarehouseController.php:70
 * @route '/setting/warehouse/{warehouse}'
 */
show.head = (args: { warehouse: string | { uuid: string } } | [warehouse: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\WarehouseController::update
 * @see app/Http/Controllers/WarehouseController.php:82
 * @route '/setting/warehouse/{warehouse}'
 */
export const update = (args: { warehouse: string | number } | [warehouse: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/setting/warehouse/{warehouse}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\WarehouseController::update
 * @see app/Http/Controllers/WarehouseController.php:82
 * @route '/setting/warehouse/{warehouse}'
 */
update.url = (args: { warehouse: string | number } | [warehouse: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { warehouse: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    warehouse: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        warehouse: args.warehouse,
                }

    return update.definition.url
            .replace('{warehouse}', parsedArgs.warehouse.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\WarehouseController::update
 * @see app/Http/Controllers/WarehouseController.php:82
 * @route '/setting/warehouse/{warehouse}'
 */
update.put = (args: { warehouse: string | number } | [warehouse: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\WarehouseController::update
 * @see app/Http/Controllers/WarehouseController.php:82
 * @route '/setting/warehouse/{warehouse}'
 */
update.patch = (args: { warehouse: string | number } | [warehouse: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\WarehouseController::destroy
 * @see app/Http/Controllers/WarehouseController.php:112
 * @route '/setting/warehouse/{warehouse}'
 */
export const destroy = (args: { warehouse: string | number } | [warehouse: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/setting/warehouse/{warehouse}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\WarehouseController::destroy
 * @see app/Http/Controllers/WarehouseController.php:112
 * @route '/setting/warehouse/{warehouse}'
 */
destroy.url = (args: { warehouse: string | number } | [warehouse: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { warehouse: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    warehouse: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        warehouse: args.warehouse,
                }

    return destroy.definition.url
            .replace('{warehouse}', parsedArgs.warehouse.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\WarehouseController::destroy
 * @see app/Http/Controllers/WarehouseController.php:112
 * @route '/setting/warehouse/{warehouse}'
 */
destroy.delete = (args: { warehouse: string | number } | [warehouse: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})
const WarehouseController = { warehouseProduct, index, store, show, update, destroy }

export default WarehouseController