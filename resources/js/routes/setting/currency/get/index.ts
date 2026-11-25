import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AccontCoController::exchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/getExchange/{month}/{year}'
 */
export const exchange = (args: { month: string | number, year: string | number } | [month: string | number, year: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exchange.url(args, options),
    method: 'get',
})

exchange.definition = {
    methods: ["get","head"],
    url: '/setting/currency/getExchange/{month}/{year}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AccontCoController::exchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/getExchange/{month}/{year}'
 */
exchange.url = (args: { month: string | number, year: string | number } | [month: string | number, year: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    month: args[0],
                    year: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        month: args.month,
                                year: args.year,
                }

    return exchange.definition.url
            .replace('{month}', parsedArgs.month.toString())
            .replace('{year}', parsedArgs.year.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::exchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/getExchange/{month}/{year}'
 */
exchange.get = (args: { month: string | number, year: string | number } | [month: string | number, year: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exchange.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AccontCoController::exchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/getExchange/{month}/{year}'
 */
exchange.head = (args: { month: string | number, year: string | number } | [month: string | number, year: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exchange.url(args, options),
    method: 'head',
})
const get = {
    exchange: Object.assign(exchange, exchange),
}

export default get