import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\SaleController::close
 * @see app/Http/Controllers/SaleController.php:301
 * @route '/sale/close/get'
 */
export const close = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: close.url(options),
    method: 'post',
})

close.definition = {
    methods: ["post"],
    url: '/sale/close/get',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\SaleController::close
 * @see app/Http/Controllers/SaleController.php:301
 * @route '/sale/close/get'
 */
close.url = (options?: RouteQueryOptions) => {
    return close.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::close
 * @see app/Http/Controllers/SaleController.php:301
 * @route '/sale/close/get'
 */
close.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: close.url(options),
    method: 'post',
})
const get = {
    close: Object.assign(close, close),
}

export default get