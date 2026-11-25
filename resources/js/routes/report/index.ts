import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../wayfinder'
import dayB024b4 from './day'
import product from './product'
/**
* @see \App\Http\Controllers\ReportController::index
 * @see app/Http/Controllers/ReportController.php:20
 * @route '/report'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/report',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::index
 * @see app/Http/Controllers/ReportController.php:20
 * @route '/report'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::index
 * @see app/Http/Controllers/ReportController.php:20
 * @route '/report'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ReportController::index
 * @see app/Http/Controllers/ReportController.php:20
 * @route '/report'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ReportController::day
 * @see app/Http/Controllers/ReportController.php:56
 * @route '/report/day'
 */
export const day = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: day.url(options),
    method: 'get',
})

day.definition = {
    methods: ["get","head"],
    url: '/report/day',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::day
 * @see app/Http/Controllers/ReportController.php:56
 * @route '/report/day'
 */
day.url = (options?: RouteQueryOptions) => {
    return day.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::day
 * @see app/Http/Controllers/ReportController.php:56
 * @route '/report/day'
 */
day.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: day.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ReportController::day
 * @see app/Http/Controllers/ReportController.php:56
 * @route '/report/day'
 */
day.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: day.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
 * @see app/Http/Controllers/ReportController.php:34
 * @route '/report/daily'
 */
export const getDailyByDate = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: getDailyByDate.url(options),
    method: 'post',
})

getDailyByDate.definition = {
    methods: ["post"],
    url: '/report/daily',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
 * @see app/Http/Controllers/ReportController.php:34
 * @route '/report/daily'
 */
getDailyByDate.url = (options?: RouteQueryOptions) => {
    return getDailyByDate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
 * @see app/Http/Controllers/ReportController.php:34
 * @route '/report/daily'
 */
getDailyByDate.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: getDailyByDate.url(options),
    method: 'post',
})
const report = {
    index: Object.assign(index, index),
day: Object.assign(day, dayB024b4),
product: Object.assign(product, product),
getDailyByDate: Object.assign(getDailyByDate, getDailyByDate),
}

export default report