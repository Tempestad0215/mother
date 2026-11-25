import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\PriceListController::show
 * @see app/Http/Controllers/PriceListController.php:38
 * @route '/price-list/product/{product}'
 */
export const show = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/price-list/product/{product}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PriceListController::show
 * @see app/Http/Controllers/PriceListController.php:38
 * @route '/price-list/product/{product}'
 */
show.url = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PriceListController::show
 * @see app/Http/Controllers/PriceListController.php:38
 * @route '/price-list/product/{product}'
 */
show.get = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PriceListController::show
 * @see app/Http/Controllers/PriceListController.php:38
 * @route '/price-list/product/{product}'
 */
show.head = (args: { product: string | { uuid: string } } | [product: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})
const product = {
    show: Object.assign(show, show),
}

export default product