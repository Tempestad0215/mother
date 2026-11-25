import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\SaleController::item
 * @see app/Http/Controllers/SaleController.php:217
 * @route '/sale/item/destroy/{product}/{sale}'
 */
export const item = (args: { product: string | { uuid: string }, sale: string | { uuid: string } } | [product: string | { uuid: string }, sale: string | { uuid: string } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: item.url(args, options),
    method: 'patch',
})

item.definition = {
    methods: ["patch"],
    url: '/sale/item/destroy/{product}/{sale}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\SaleController::item
 * @see app/Http/Controllers/SaleController.php:217
 * @route '/sale/item/destroy/{product}/{sale}'
 */
item.url = (args: { product: string | { uuid: string }, sale: string | { uuid: string } } | [product: string | { uuid: string }, sale: string | { uuid: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    product: args[0],
                    sale: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        product: typeof args.product === 'object'
                ? args.product.uuid
                : args.product,
                                sale: typeof args.sale === 'object'
                ? args.sale.uuid
                : args.sale,
                }

    return item.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::item
 * @see app/Http/Controllers/SaleController.php:217
 * @route '/sale/item/destroy/{product}/{sale}'
 */
item.patch = (args: { product: string | { uuid: string }, sale: string | { uuid: string } } | [product: string | { uuid: string }, sale: string | { uuid: string } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: item.url(args, options),
    method: 'patch',
})