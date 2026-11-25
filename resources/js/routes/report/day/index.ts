import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ReportController::date
 * @see app/Http/Controllers/ReportController.php:34
 * @route '/report/day/date'
 */
export const date = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: date.url(options),
    method: 'get',
})

date.definition = {
    methods: ["get","head"],
    url: '/report/day/date',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::date
 * @see app/Http/Controllers/ReportController.php:34
 * @route '/report/day/date'
 */
date.url = (options?: RouteQueryOptions) => {
    return date.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::date
 * @see app/Http/Controllers/ReportController.php:34
 * @route '/report/day/date'
 */
date.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: date.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ReportController::date
 * @see app/Http/Controllers/ReportController.php:34
 * @route '/report/day/date'
 */
date.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: date.url(options),
    method: 'head',
})
const day = {
    date: Object.assign(date, date),
}

export default day