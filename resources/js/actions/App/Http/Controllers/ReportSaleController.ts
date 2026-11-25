import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ReportSaleController::index
 * @see app/Http/Controllers/ReportSaleController.php:16
 * @route '/report/sale'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/report/sale',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportSaleController::index
 * @see app/Http/Controllers/ReportSaleController.php:16
 * @route '/report/sale'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportSaleController::index
 * @see app/Http/Controllers/ReportSaleController.php:16
 * @route '/report/sale'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ReportSaleController::index
 * @see app/Http/Controllers/ReportSaleController.php:16
 * @route '/report/sale'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})
const ReportSaleController = { index }

export default ReportSaleController