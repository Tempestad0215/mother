import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\InvoiceController::close
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
export const close = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: close.url(args, options),
    method: 'get',
})

close.definition = {
    methods: ["get","head"],
    url: '/invoice/cashregister/close/{cashRegister}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InvoiceController::close
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
close.url = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { cashRegister: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
        args = { cashRegister: args.uuid }
    }

    if (Array.isArray(args)) {
        args = {
            cashRegister: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        cashRegister: typeof args.cashRegister === 'object'
        ? args.cashRegister.uuid
        : args.cashRegister,
    }

    return close.definition.url
            .replace('{cashRegister}', parsedArgs.cashRegister.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InvoiceController::close
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
close.get = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: close.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::close
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
close.head = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: close.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InvoiceController::close
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
const closeForm = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: close.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::close
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
closeForm.get = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: close.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InvoiceController::close
* @see app/Http/Controllers/InvoiceController.php:119
* @route '/invoice/cashregister/close/{cashRegister}'
*/
closeForm.head = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: close.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

close.form = closeForm

const cashregister = {
    close: Object.assign(close, close),
}

export default cashregister