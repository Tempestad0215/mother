import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
import close08088e from './close'
/**
* @see \App\Http\Controllers\CashRegisterController::close
 * @see app/Http/Controllers/CashRegisterController.php:54
 * @route '/cash-register/close'
 */
export const close = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: close.url(options),
    method: 'get',
})

close.definition = {
    methods: ["get","head"],
    url: '/cash-register/close',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CashRegisterController::close
 * @see app/Http/Controllers/CashRegisterController.php:54
 * @route '/cash-register/close'
 */
close.url = (options?: RouteQueryOptions) => {
    return close.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CashRegisterController::close
 * @see app/Http/Controllers/CashRegisterController.php:54
 * @route '/cash-register/close'
 */
close.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: close.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CashRegisterController::close
 * @see app/Http/Controllers/CashRegisterController.php:54
 * @route '/cash-register/close'
 */
close.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: close.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\CashRegisterController::index
 * @see app/Http/Controllers/CashRegisterController.php:17
 * @route '/cash-register'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/cash-register',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CashRegisterController::index
 * @see app/Http/Controllers/CashRegisterController.php:17
 * @route '/cash-register'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CashRegisterController::index
 * @see app/Http/Controllers/CashRegisterController.php:17
 * @route '/cash-register'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CashRegisterController::index
 * @see app/Http/Controllers/CashRegisterController.php:17
 * @route '/cash-register'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\CashRegisterController::store
 * @see app/Http/Controllers/CashRegisterController.php:27
 * @route '/cash-register'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/cash-register',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\CashRegisterController::store
 * @see app/Http/Controllers/CashRegisterController.php:27
 * @route '/cash-register'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CashRegisterController::store
 * @see app/Http/Controllers/CashRegisterController.php:27
 * @route '/cash-register'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\CashRegisterController::show
 * @see app/Http/Controllers/CashRegisterController.php:119
 * @route '/cash-register/{cash_register}'
 */
export const show = (args: { cash_register: string | { uuid: string } } | [cash_register: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/cash-register/{cash_register}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CashRegisterController::show
 * @see app/Http/Controllers/CashRegisterController.php:119
 * @route '/cash-register/{cash_register}'
 */
show.url = (args: { cash_register: string | { uuid: string } } | [cash_register: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { cash_register: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { cash_register: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    cash_register: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        cash_register: typeof args.cash_register === 'object'
                ? args.cash_register.uuid
                : args.cash_register,
                }

    return show.definition.url
            .replace('{cash_register}', parsedArgs.cash_register.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\CashRegisterController::show
 * @see app/Http/Controllers/CashRegisterController.php:119
 * @route '/cash-register/{cash_register}'
 */
show.get = (args: { cash_register: string | { uuid: string } } | [cash_register: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CashRegisterController::show
 * @see app/Http/Controllers/CashRegisterController.php:119
 * @route '/cash-register/{cash_register}'
 */
show.head = (args: { cash_register: string | { uuid: string } } | [cash_register: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\CashRegisterController::update
 * @see app/Http/Controllers/CashRegisterController.php:124
 * @route '/cash-register/{cash_register}'
 */
export const update = (args: { cash_register: string | { uuid: string } } | [cash_register: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/cash-register/{cash_register}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\CashRegisterController::update
 * @see app/Http/Controllers/CashRegisterController.php:124
 * @route '/cash-register/{cash_register}'
 */
update.url = (args: { cash_register: string | { uuid: string } } | [cash_register: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { cash_register: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { cash_register: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    cash_register: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        cash_register: typeof args.cash_register === 'object'
                ? args.cash_register.uuid
                : args.cash_register,
                }

    return update.definition.url
            .replace('{cash_register}', parsedArgs.cash_register.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\CashRegisterController::update
 * @see app/Http/Controllers/CashRegisterController.php:124
 * @route '/cash-register/{cash_register}'
 */
update.put = (args: { cash_register: string | { uuid: string } } | [cash_register: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\CashRegisterController::update
 * @see app/Http/Controllers/CashRegisterController.php:124
 * @route '/cash-register/{cash_register}'
 */
update.patch = (args: { cash_register: string | { uuid: string } } | [cash_register: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\CashRegisterController::destroy
 * @see app/Http/Controllers/CashRegisterController.php:141
 * @route '/cash-register/{cash_register}'
 */
export const destroy = (args: { cash_register: string | { uuid: string } } | [cash_register: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/cash-register/{cash_register}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\CashRegisterController::destroy
 * @see app/Http/Controllers/CashRegisterController.php:141
 * @route '/cash-register/{cash_register}'
 */
destroy.url = (args: { cash_register: string | { uuid: string } } | [cash_register: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { cash_register: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { cash_register: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    cash_register: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        cash_register: typeof args.cash_register === 'object'
                ? args.cash_register.uuid
                : args.cash_register,
                }

    return destroy.definition.url
            .replace('{cash_register}', parsedArgs.cash_register.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\CashRegisterController::destroy
 * @see app/Http/Controllers/CashRegisterController.php:141
 * @route '/cash-register/{cash_register}'
 */
destroy.delete = (args: { cash_register: string | { uuid: string } } | [cash_register: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})
const cashRegister = {
    close: Object.assign(close, close08088e),
index: Object.assign(index, index),
store: Object.assign(store, store),
show: Object.assign(show, show),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default cashRegister