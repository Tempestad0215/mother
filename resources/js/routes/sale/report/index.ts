import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
import method from './'
/**
* @see \App\Http\Controllers\ExchangeController::index
 * @see app/Http/Controllers/ExchangeController.php:17
 * @route '/sale/report'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/sale/report',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ExchangeController::index
 * @see app/Http/Controllers/ExchangeController.php:17
 * @route '/sale/report'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ExchangeController::index
 * @see app/Http/Controllers/ExchangeController.php:17
 * @route '/sale/report'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ExchangeController::index
 * @see app/Http/Controllers/ExchangeController.php:17
 * @route '/sale/report'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ExchangeController::store
 * @see app/Http/Controllers/ExchangeController.php:27
 * @route '/sale/report'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/sale/report',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ExchangeController::store
 * @see app/Http/Controllers/ExchangeController.php:27
 * @route '/sale/report'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ExchangeController::store
 * @see app/Http/Controllers/ExchangeController.php:27
 * @route '/sale/report'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ExchangeController::show
 * @see app/Http/Controllers/ExchangeController.php:0
 * @route '/sale/report/{report}'
 */
export const show = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/sale/report/{report}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ExchangeController::show
 * @see app/Http/Controllers/ExchangeController.php:0
 * @route '/sale/report/{report}'
 */
show.url = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    report: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report: args.report,
                }

    return show.definition.url
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ExchangeController::show
 * @see app/Http/Controllers/ExchangeController.php:0
 * @route '/sale/report/{report}'
 */
show.get = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ExchangeController::show
 * @see app/Http/Controllers/ExchangeController.php:0
 * @route '/sale/report/{report}'
 */
show.head = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ExchangeController::update
 * @see app/Http/Controllers/ExchangeController.php:0
 * @route '/sale/report/{report}'
 */
export const update = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/sale/report/{report}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\ExchangeController::update
 * @see app/Http/Controllers/ExchangeController.php:0
 * @route '/sale/report/{report}'
 */
update.url = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    report: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report: args.report,
                }

    return update.definition.url
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ExchangeController::update
 * @see app/Http/Controllers/ExchangeController.php:0
 * @route '/sale/report/{report}'
 */
update.put = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\ExchangeController::update
 * @see app/Http/Controllers/ExchangeController.php:0
 * @route '/sale/report/{report}'
 */
update.patch = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\ExchangeController::destroy
 * @see app/Http/Controllers/ExchangeController.php:0
 * @route '/sale/report/{report}'
 */
export const destroy = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/sale/report/{report}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ExchangeController::destroy
 * @see app/Http/Controllers/ExchangeController.php:0
 * @route '/sale/report/{report}'
 */
destroy.url = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { report: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    report: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        report: args.report,
                }

    return destroy.definition.url
            .replace('{report}', parsedArgs.report.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ExchangeController::destroy
 * @see app/Http/Controllers/ExchangeController.php:0
 * @route '/sale/report/{report}'
 */
destroy.delete = (args: { report: string | number } | [report: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})
const report = {
    : Object.assign(method, method),
}

export default report
const report = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
show: Object.assign(show, show),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default report