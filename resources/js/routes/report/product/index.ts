import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ReportController::low
 * @see app/Http/Controllers/ReportController.php:71
 * @route '/report/product/low'
 */
export const low = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: low.url(options),
    method: 'get',
})

low.definition = {
    methods: ["get","head"],
    url: '/report/product/low',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::low
 * @see app/Http/Controllers/ReportController.php:71
 * @route '/report/product/low'
 */
low.url = (options?: RouteQueryOptions) => {
    return low.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::low
 * @see app/Http/Controllers/ReportController.php:71
 * @route '/report/product/low'
 */
low.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: low.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ReportController::low
 * @see app/Http/Controllers/ReportController.php:71
 * @route '/report/product/low'
 */
low.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: low.url(options),
    method: 'head',
})
const product = {
    low: Object.assign(low, low),
}

export default product