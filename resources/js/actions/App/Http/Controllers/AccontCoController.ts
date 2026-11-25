import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/aco'
 */
const index75c9fd73fbeb89b284668fc4d6553a01 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index75c9fd73fbeb89b284668fc4d6553a01.url(options),
    method: 'get',
})

index75c9fd73fbeb89b284668fc4d6553a01.definition = {
    methods: ["get","head"],
    url: '/aco',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/aco'
 */
index75c9fd73fbeb89b284668fc4d6553a01.url = (options?: RouteQueryOptions) => {
    return index75c9fd73fbeb89b284668fc4d6553a01.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/aco'
 */
index75c9fd73fbeb89b284668fc4d6553a01.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index75c9fd73fbeb89b284668fc4d6553a01.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/aco'
 */
index75c9fd73fbeb89b284668fc4d6553a01.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index75c9fd73fbeb89b284668fc4d6553a01.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/setting/currency'
 */
const index4e300d9c7ca412757e6323826cf9bc2f = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index4e300d9c7ca412757e6323826cf9bc2f.url(options),
    method: 'get',
})

index4e300d9c7ca412757e6323826cf9bc2f.definition = {
    methods: ["get","head"],
    url: '/setting/currency',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/setting/currency'
 */
index4e300d9c7ca412757e6323826cf9bc2f.url = (options?: RouteQueryOptions) => {
    return index4e300d9c7ca412757e6323826cf9bc2f.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/setting/currency'
 */
index4e300d9c7ca412757e6323826cf9bc2f.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index4e300d9c7ca412757e6323826cf9bc2f.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AccontCoController::index
 * @see app/Http/Controllers/AccontCoController.php:35
 * @route '/setting/currency'
 */
index4e300d9c7ca412757e6323826cf9bc2f.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index4e300d9c7ca412757e6323826cf9bc2f.url(options),
    method: 'head',
})

/**
* Multiple routes resolve to \App\Http\Controllers\AccontCoController::index, so this export is a
* dictionary keyed by URI rather than a callable, with the verbs prefixed where two routes share a URI.
* Call a specific route with `index['<key>'](...)`, or import the route by name from your
* generated `routes/` directory.
*/
export const index = {
    '/aco': index75c9fd73fbeb89b284668fc4d6553a01,
    '/setting/currency': index4e300d9c7ca412757e6323826cf9bc2f,
}

/**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:49
 * @route '/aco'
 */
const store75c9fd73fbeb89b284668fc4d6553a01 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store75c9fd73fbeb89b284668fc4d6553a01.url(options),
    method: 'post',
})

store75c9fd73fbeb89b284668fc4d6553a01.definition = {
    methods: ["post"],
    url: '/aco',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:49
 * @route '/aco'
 */
store75c9fd73fbeb89b284668fc4d6553a01.url = (options?: RouteQueryOptions) => {
    return store75c9fd73fbeb89b284668fc4d6553a01.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:49
 * @route '/aco'
 */
store75c9fd73fbeb89b284668fc4d6553a01.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store75c9fd73fbeb89b284668fc4d6553a01.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:49
 * @route '/setting/currency'
 */
const store4e300d9c7ca412757e6323826cf9bc2f = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store4e300d9c7ca412757e6323826cf9bc2f.url(options),
    method: 'post',
})

store4e300d9c7ca412757e6323826cf9bc2f.definition = {
    methods: ["post"],
    url: '/setting/currency',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:49
 * @route '/setting/currency'
 */
store4e300d9c7ca412757e6323826cf9bc2f.url = (options?: RouteQueryOptions) => {
    return store4e300d9c7ca412757e6323826cf9bc2f.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:49
 * @route '/setting/currency'
 */
store4e300d9c7ca412757e6323826cf9bc2f.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store4e300d9c7ca412757e6323826cf9bc2f.url(options),
    method: 'post',
})

/**
* Multiple routes resolve to \App\Http\Controllers\AccontCoController::store, so this export is a
* dictionary keyed by URI rather than a callable, with the verbs prefixed where two routes share a URI.
* Call a specific route with `store['<key>'](...)`, or import the route by name from your
* generated `routes/` directory.
*/
export const store = {
    '/aco': store75c9fd73fbeb89b284668fc4d6553a01,
    '/setting/currency': store4e300d9c7ca412757e6323826cf9bc2f,
}

/**
* @see \App\Http\Controllers\AccontCoController::show
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/aco/{aco}'
 */
export const show = (args: { aco: string | number } | [aco: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/aco/{aco}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AccontCoController::show
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/aco/{aco}'
 */
show.url = (args: { aco: string | number } | [aco: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { aco: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    aco: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        aco: args.aco,
                }

    return show.definition.url
            .replace('{aco}', parsedArgs.aco.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::show
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/aco/{aco}'
 */
show.get = (args: { aco: string | number } | [aco: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AccontCoController::show
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/aco/{aco}'
 */
show.head = (args: { aco: string | number } | [aco: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\AccontCoController::update
 * @see app/Http/Controllers/AccontCoController.php:70
 * @route '/aco/{aco}'
 */
export const update = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/aco/{aco}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\AccontCoController::update
 * @see app/Http/Controllers/AccontCoController.php:70
 * @route '/aco/{aco}'
 */
update.url = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { aco: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { aco: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    aco: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        aco: typeof args.aco === 'object'
                ? args.aco.id
                : args.aco,
                }

    return update.definition.url
            .replace('{aco}', parsedArgs.aco.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::update
 * @see app/Http/Controllers/AccontCoController.php:70
 * @route '/aco/{aco}'
 */
update.put = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\AccontCoController::update
 * @see app/Http/Controllers/AccontCoController.php:70
 * @route '/aco/{aco}'
 */
update.patch = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\AccontCoController::destroy
 * @see app/Http/Controllers/AccontCoController.php:92
 * @route '/aco/{aco}'
 */
const destroy24de0827ff8bf2cad0fe1509aee1b08c = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy24de0827ff8bf2cad0fe1509aee1b08c.url(args, options),
    method: 'delete',
})

destroy24de0827ff8bf2cad0fe1509aee1b08c.definition = {
    methods: ["delete"],
    url: '/aco/{aco}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\AccontCoController::destroy
 * @see app/Http/Controllers/AccontCoController.php:92
 * @route '/aco/{aco}'
 */
destroy24de0827ff8bf2cad0fe1509aee1b08c.url = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { aco: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { aco: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    aco: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        aco: typeof args.aco === 'object'
                ? args.aco.id
                : args.aco,
                }

    return destroy24de0827ff8bf2cad0fe1509aee1b08c.definition.url
            .replace('{aco}', parsedArgs.aco.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::destroy
 * @see app/Http/Controllers/AccontCoController.php:92
 * @route '/aco/{aco}'
 */
destroy24de0827ff8bf2cad0fe1509aee1b08c.delete = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy24de0827ff8bf2cad0fe1509aee1b08c.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\AccontCoController::destroy
 * @see app/Http/Controllers/AccontCoController.php:92
 * @route '/setting/currency/{currency}'
 */
const destroy80432f2f7f8e810604b67b2ffb3e339a = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy80432f2f7f8e810604b67b2ffb3e339a.url(args, options),
    method: 'delete',
})

destroy80432f2f7f8e810604b67b2ffb3e339a.definition = {
    methods: ["delete"],
    url: '/setting/currency/{currency}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\AccontCoController::destroy
 * @see app/Http/Controllers/AccontCoController.php:92
 * @route '/setting/currency/{currency}'
 */
destroy80432f2f7f8e810604b67b2ffb3e339a.url = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { currency: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    currency: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        currency: args.currency,
                }

    return destroy80432f2f7f8e810604b67b2ffb3e339a.definition.url
            .replace('{currency}', parsedArgs.currency.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::destroy
 * @see app/Http/Controllers/AccontCoController.php:92
 * @route '/setting/currency/{currency}'
 */
destroy80432f2f7f8e810604b67b2ffb3e339a.delete = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy80432f2f7f8e810604b67b2ffb3e339a.url(args, options),
    method: 'delete',
})

/**
* Multiple routes resolve to \App\Http\Controllers\AccontCoController::destroy, so this export is a
* dictionary keyed by URI rather than a callable, with the verbs prefixed where two routes share a URI.
* Call a specific route with `destroy['<key>'](...)`, or import the route by name from your
* generated `routes/` directory.
*/
export const destroy = {
    '/aco/{aco}': destroy24de0827ff8bf2cad0fe1509aee1b08c,
    '/setting/currency/{currency}': destroy80432f2f7f8e810604b67b2ffb3e339a,
}

/**
* @see \App\Http\Controllers\AccontCoController::checkExchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/check-exchange'
 */
export const checkExchange = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: checkExchange.url(options),
    method: 'get',
})

checkExchange.definition = {
    methods: ["get","head"],
    url: '/setting/currency/check-exchange',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AccontCoController::checkExchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/check-exchange'
 */
checkExchange.url = (options?: RouteQueryOptions) => {
    return checkExchange.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::checkExchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/check-exchange'
 */
checkExchange.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: checkExchange.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AccontCoController::checkExchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/check-exchange'
 */
checkExchange.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: checkExchange.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\AccontCoController::getExchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/getExchange/{month}/{year}'
 */
export const getExchange = (args: { month: string | number, year: string | number } | [month: string | number, year: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getExchange.url(args, options),
    method: 'get',
})

getExchange.definition = {
    methods: ["get","head"],
    url: '/setting/currency/getExchange/{month}/{year}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AccontCoController::getExchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/getExchange/{month}/{year}'
 */
getExchange.url = (args: { month: string | number, year: string | number } | [month: string | number, year: string | number ], options?: RouteQueryOptions) => {
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

    return getExchange.definition.url
            .replace('{month}', parsedArgs.month.toString())
            .replace('{year}', parsedArgs.year.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::getExchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/getExchange/{month}/{year}'
 */
getExchange.get = (args: { month: string | number, year: string | number } | [month: string | number, year: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getExchange.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AccontCoController::getExchange
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/getExchange/{month}/{year}'
 */
getExchange.head = (args: { month: string | number, year: string | number } | [month: string | number, year: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getExchange.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\AccontCoController::exchangeStore
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/exchange'
 */
export const exchangeStore = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: exchangeStore.url(options),
    method: 'post',
})

exchangeStore.definition = {
    methods: ["post"],
    url: '/setting/currency/exchange',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AccontCoController::exchangeStore
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/exchange'
 */
exchangeStore.url = (options?: RouteQueryOptions) => {
    return exchangeStore.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::exchangeStore
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/exchange'
 */
exchangeStore.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: exchangeStore.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AccontCoController::restore
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/restore/{currency}'
 */
export const restore = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: restore.url(args, options),
    method: 'put',
})

restore.definition = {
    methods: ["put"],
    url: '/setting/currency/restore/{currency}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\AccontCoController::restore
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/restore/{currency}'
 */
restore.url = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { currency: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    currency: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        currency: args.currency,
                }

    return restore.definition.url
            .replace('{currency}', parsedArgs.currency.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::restore
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/restore/{currency}'
 */
restore.put = (args: { currency: string | number } | [currency: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: restore.url(args, options),
    method: 'put',
})
const AccontCoController = { index, store, show, update, destroy, checkExchange, getExchange, exchangeStore, restore }

export default AccontCoController