import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\CashRegisterController::store
* @see app/Http/Controllers/CashRegisterController.php:71
* @route '/cash-register/close/store/{cashRegister}'
*/
export const store = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: store.url(args, options),
    method: 'patch',
})

store.definition = {
    methods: ["patch"],
    url: '/cash-register/close/store/{cashRegister}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\CashRegisterController::store
* @see app/Http/Controllers/CashRegisterController.php:71
* @route '/cash-register/close/store/{cashRegister}'
*/
store.url = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{cashRegister}', parsedArgs.cashRegister.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\CashRegisterController::store
* @see app/Http/Controllers/CashRegisterController.php:71
* @route '/cash-register/close/store/{cashRegister}'
*/
store.patch = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: store.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\CashRegisterController::store
* @see app/Http/Controllers/CashRegisterController.php:71
* @route '/cash-register/close/store/{cashRegister}'
*/
const storeForm = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\CashRegisterController::store
* @see app/Http/Controllers/CashRegisterController.php:71
* @route '/cash-register/close/store/{cashRegister}'
*/
storeForm.patch = (args: { cashRegister: string | { uuid: string } } | [cashRegister: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

store.form = storeForm

const close = {
    store: Object.assign(store, store),
}

export default close