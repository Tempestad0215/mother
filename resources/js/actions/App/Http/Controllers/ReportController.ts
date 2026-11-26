import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
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
* @see \App\Http\Controllers\ReportController::index
* @see app/Http/Controllers/ReportController.php:20
* @route '/report'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::index
* @see app/Http/Controllers/ReportController.php:20
* @route '/report'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::index
* @see app/Http/Controllers/ReportController.php:20
* @route '/report'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\ReportController::getDay
* @see app/Http/Controllers/ReportController.php:56
* @route '/report/day'
*/
export const getDay = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getDay.url(options),
    method: 'get',
})

getDay.definition = {
    methods: ["get","head"],
    url: '/report/day',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::getDay
* @see app/Http/Controllers/ReportController.php:56
* @route '/report/day'
*/
getDay.url = (options?: RouteQueryOptions) => {
    return getDay.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::getDay
* @see app/Http/Controllers/ReportController.php:56
* @route '/report/day'
*/
getDay.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getDay.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::getDay
* @see app/Http/Controllers/ReportController.php:56
* @route '/report/day'
*/
getDay.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getDay.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ReportController::getDay
* @see app/Http/Controllers/ReportController.php:56
* @route '/report/day'
*/
const getDayForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getDay.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::getDay
* @see app/Http/Controllers/ReportController.php:56
* @route '/report/day'
*/
getDayForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getDay.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::getDay
* @see app/Http/Controllers/ReportController.php:56
* @route '/report/day'
*/
getDayForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getDay.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

getDay.form = getDayForm

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/day/date'
*/
const getDailyByDate818cf063162c12d865d584215fd9281c = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getDailyByDate818cf063162c12d865d584215fd9281c.url(options),
    method: 'get',
})

getDailyByDate818cf063162c12d865d584215fd9281c.definition = {
    methods: ["get","head"],
    url: '/report/day/date',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/day/date'
*/
getDailyByDate818cf063162c12d865d584215fd9281c.url = (options?: RouteQueryOptions) => {
    return getDailyByDate818cf063162c12d865d584215fd9281c.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/day/date'
*/
getDailyByDate818cf063162c12d865d584215fd9281c.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getDailyByDate818cf063162c12d865d584215fd9281c.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/day/date'
*/
getDailyByDate818cf063162c12d865d584215fd9281c.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getDailyByDate818cf063162c12d865d584215fd9281c.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/day/date'
*/
const getDailyByDate818cf063162c12d865d584215fd9281cForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getDailyByDate818cf063162c12d865d584215fd9281c.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/day/date'
*/
getDailyByDate818cf063162c12d865d584215fd9281cForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getDailyByDate818cf063162c12d865d584215fd9281c.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/day/date'
*/
getDailyByDate818cf063162c12d865d584215fd9281cForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: getDailyByDate818cf063162c12d865d584215fd9281c.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

getDailyByDate818cf063162c12d865d584215fd9281c.form = getDailyByDate818cf063162c12d865d584215fd9281cForm
/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/daily'
*/
const getDailyByDate2c6eb74dec4b4757d319d2423ad2f01d = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: getDailyByDate2c6eb74dec4b4757d319d2423ad2f01d.url(options),
    method: 'post',
})

getDailyByDate2c6eb74dec4b4757d319d2423ad2f01d.definition = {
    methods: ["post"],
    url: '/report/daily',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/daily'
*/
getDailyByDate2c6eb74dec4b4757d319d2423ad2f01d.url = (options?: RouteQueryOptions) => {
    return getDailyByDate2c6eb74dec4b4757d319d2423ad2f01d.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/daily'
*/
getDailyByDate2c6eb74dec4b4757d319d2423ad2f01d.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: getDailyByDate2c6eb74dec4b4757d319d2423ad2f01d.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/daily'
*/
const getDailyByDate2c6eb74dec4b4757d319d2423ad2f01dForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: getDailyByDate2c6eb74dec4b4757d319d2423ad2f01d.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ReportController::getDailyByDate
* @see app/Http/Controllers/ReportController.php:34
* @route '/report/daily'
*/
getDailyByDate2c6eb74dec4b4757d319d2423ad2f01dForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: getDailyByDate2c6eb74dec4b4757d319d2423ad2f01d.url(options),
    method: 'post',
})

getDailyByDate2c6eb74dec4b4757d319d2423ad2f01d.form = getDailyByDate2c6eb74dec4b4757d319d2423ad2f01dForm

/**
* Multiple routes resolve to \App\Http\Controllers\ReportController::getDailyByDate, so this export is a
* dictionary keyed by URI rather than a callable, with the verbs prefixed where two routes share a URI.
* Call a specific route with `getDailyByDate['<key>'](...)`, or import the route by name from your
* generated `routes/` directory.
*/
export const getDailyByDate = {
    '/report/day/date': getDailyByDate818cf063162c12d865d584215fd9281c,
    '/report/daily': getDailyByDate2c6eb74dec4b4757d319d2423ad2f01d,
}

/**
* @see \App\Http\Controllers\ReportController::stockLow
* @see app/Http/Controllers/ReportController.php:71
* @route '/report/product/low'
*/
export const stockLow = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: stockLow.url(options),
    method: 'get',
})

stockLow.definition = {
    methods: ["get","head"],
    url: '/report/product/low',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ReportController::stockLow
* @see app/Http/Controllers/ReportController.php:71
* @route '/report/product/low'
*/
stockLow.url = (options?: RouteQueryOptions) => {
    return stockLow.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ReportController::stockLow
* @see app/Http/Controllers/ReportController.php:71
* @route '/report/product/low'
*/
stockLow.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: stockLow.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::stockLow
* @see app/Http/Controllers/ReportController.php:71
* @route '/report/product/low'
*/
stockLow.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: stockLow.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ReportController::stockLow
* @see app/Http/Controllers/ReportController.php:71
* @route '/report/product/low'
*/
const stockLowForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: stockLow.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::stockLow
* @see app/Http/Controllers/ReportController.php:71
* @route '/report/product/low'
*/
stockLowForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: stockLow.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ReportController::stockLow
* @see app/Http/Controllers/ReportController.php:71
* @route '/report/product/low'
*/
stockLowForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: stockLow.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

stockLow.form = stockLowForm

const ReportController = { index, getDay, getDailyByDate, stockLow }

export default ReportController