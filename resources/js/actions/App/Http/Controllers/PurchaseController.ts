import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\PurchaseController::show
 * @see app/Http/Controllers/PurchaseController.php:147
 * @route '/purchase/show'
 */
const show32b15fc2ab05ef39eb565ff0503dd43e = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show32b15fc2ab05ef39eb565ff0503dd43e.url(options),
    method: 'get',
})

show32b15fc2ab05ef39eb565ff0503dd43e.definition = {
    methods: ["get","head"],
    url: '/purchase/show',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PurchaseController::show
 * @see app/Http/Controllers/PurchaseController.php:147
 * @route '/purchase/show'
 */
show32b15fc2ab05ef39eb565ff0503dd43e.url = (options?: RouteQueryOptions) => {
    return show32b15fc2ab05ef39eb565ff0503dd43e.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::show
 * @see app/Http/Controllers/PurchaseController.php:147
 * @route '/purchase/show'
 */
show32b15fc2ab05ef39eb565ff0503dd43e.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show32b15fc2ab05ef39eb565ff0503dd43e.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PurchaseController::show
 * @see app/Http/Controllers/PurchaseController.php:147
 * @route '/purchase/show'
 */
show32b15fc2ab05ef39eb565ff0503dd43e.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show32b15fc2ab05ef39eb565ff0503dd43e.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\PurchaseController::show
 * @see app/Http/Controllers/PurchaseController.php:147
 * @route '/purchase/{purchase}'
 */
const showade08fef521290c154abfea77ad21669 = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showade08fef521290c154abfea77ad21669.url(args, options),
    method: 'get',
})

showade08fef521290c154abfea77ad21669.definition = {
    methods: ["get","head"],
    url: '/purchase/{purchase}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PurchaseController::show
 * @see app/Http/Controllers/PurchaseController.php:147
 * @route '/purchase/{purchase}'
 */
showade08fef521290c154abfea77ad21669.url = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { purchase: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    purchase: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        purchase: args.purchase,
                }

    return showade08fef521290c154abfea77ad21669.definition.url
            .replace('{purchase}', parsedArgs.purchase.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::show
 * @see app/Http/Controllers/PurchaseController.php:147
 * @route '/purchase/{purchase}'
 */
showade08fef521290c154abfea77ad21669.get = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showade08fef521290c154abfea77ad21669.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PurchaseController::show
 * @see app/Http/Controllers/PurchaseController.php:147
 * @route '/purchase/{purchase}'
 */
showade08fef521290c154abfea77ad21669.head = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: showade08fef521290c154abfea77ad21669.url(args, options),
    method: 'head',
})

/**
* Multiple routes resolve to \App\Http\Controllers\PurchaseController::show, so this export is a
* dictionary keyed by URI rather than a callable, with the verbs prefixed where two routes share a URI.
* Call a specific route with `show['<key>'](...)`, or import the route by name from your
* generated `routes/` directory.
*/
export const show = {
    '/purchase/show': show32b15fc2ab05ef39eb565ff0503dd43e,
    '/purchase/{purchase}': showade08fef521290c154abfea77ad21669,
}

/**
* @see \App\Http\Controllers\PurchaseController::receive
 * @see app/Http/Controllers/PurchaseController.php:189
 * @route '/purchase/receive'
 */
export const receive = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: receive.url(options),
    method: 'get',
})

receive.definition = {
    methods: ["get","head"],
    url: '/purchase/receive',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PurchaseController::receive
 * @see app/Http/Controllers/PurchaseController.php:189
 * @route '/purchase/receive'
 */
receive.url = (options?: RouteQueryOptions) => {
    return receive.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::receive
 * @see app/Http/Controllers/PurchaseController.php:189
 * @route '/purchase/receive'
 */
receive.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: receive.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PurchaseController::receive
 * @see app/Http/Controllers/PurchaseController.php:189
 * @route '/purchase/receive'
 */
receive.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: receive.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PurchaseController::receiveStore
 * @see app/Http/Controllers/PurchaseController.php:202
 * @route '/purchase/receive'
 */
export const receiveStore = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: receiveStore.url(options),
    method: 'post',
})

receiveStore.definition = {
    methods: ["post"],
    url: '/purchase/receive',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PurchaseController::receiveStore
 * @see app/Http/Controllers/PurchaseController.php:202
 * @route '/purchase/receive'
 */
receiveStore.url = (options?: RouteQueryOptions) => {
    return receiveStore.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::receiveStore
 * @see app/Http/Controllers/PurchaseController.php:202
 * @route '/purchase/receive'
 */
receiveStore.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: receiveStore.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PurchaseController::output
 * @see app/Http/Controllers/PurchaseController.php:214
 * @route '/purchase/output'
 */
export const output = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: output.url(options),
    method: 'get',
})

output.definition = {
    methods: ["get","head"],
    url: '/purchase/output',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PurchaseController::output
 * @see app/Http/Controllers/PurchaseController.php:214
 * @route '/purchase/output'
 */
output.url = (options?: RouteQueryOptions) => {
    return output.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::output
 * @see app/Http/Controllers/PurchaseController.php:214
 * @route '/purchase/output'
 */
output.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: output.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PurchaseController::output
 * @see app/Http/Controllers/PurchaseController.php:214
 * @route '/purchase/output'
 */
output.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: output.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PurchaseController::cancel
 * @see app/Http/Controllers/PurchaseController.php:178
 * @route '/purchase/{purchase}/cancel'
 */
export const cancel = (args: { purchase: string | { uuid: string } } | [purchase: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: cancel.url(args, options),
    method: 'patch',
})

cancel.definition = {
    methods: ["patch"],
    url: '/purchase/{purchase}/cancel',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\PurchaseController::cancel
 * @see app/Http/Controllers/PurchaseController.php:178
 * @route '/purchase/{purchase}/cancel'
 */
cancel.url = (args: { purchase: string | { uuid: string } } | [purchase: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { purchase: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { purchase: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    purchase: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        purchase: typeof args.purchase === 'object'
                ? args.purchase.uuid
                : args.purchase,
                }

    return cancel.definition.url
            .replace('{purchase}', parsedArgs.purchase.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::cancel
 * @see app/Http/Controllers/PurchaseController.php:178
 * @route '/purchase/{purchase}/cancel'
 */
cancel.patch = (args: { purchase: string | { uuid: string } } | [purchase: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: cancel.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\PurchaseController::approve
 * @see app/Http/Controllers/PurchaseController.php:168
 * @route '/purchase/{purchase}/approve'
 */
export const approve = (args: { purchase: string | { uuid: string } } | [purchase: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: approve.url(args, options),
    method: 'patch',
})

approve.definition = {
    methods: ["patch"],
    url: '/purchase/{purchase}/approve',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\PurchaseController::approve
 * @see app/Http/Controllers/PurchaseController.php:168
 * @route '/purchase/{purchase}/approve'
 */
approve.url = (args: { purchase: string | { uuid: string } } | [purchase: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { purchase: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { purchase: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    purchase: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        purchase: typeof args.purchase === 'object'
                ? args.purchase.uuid
                : args.purchase,
                }

    return approve.definition.url
            .replace('{purchase}', parsedArgs.purchase.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::approve
 * @see app/Http/Controllers/PurchaseController.php:168
 * @route '/purchase/{purchase}/approve'
 */
approve.patch = (args: { purchase: string | { uuid: string } } | [purchase: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: approve.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\PurchaseController::index
 * @see app/Http/Controllers/PurchaseController.php:34
 * @route '/purchase'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/purchase',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PurchaseController::index
 * @see app/Http/Controllers/PurchaseController.php:34
 * @route '/purchase'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::index
 * @see app/Http/Controllers/PurchaseController.php:34
 * @route '/purchase'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\PurchaseController::index
 * @see app/Http/Controllers/PurchaseController.php:34
 * @route '/purchase'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PurchaseController::store
 * @see app/Http/Controllers/PurchaseController.php:71
 * @route '/purchase'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/purchase',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\PurchaseController::store
 * @see app/Http/Controllers/PurchaseController.php:71
 * @route '/purchase'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::store
 * @see app/Http/Controllers/PurchaseController.php:71
 * @route '/purchase'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PurchaseController::update
 * @see app/Http/Controllers/PurchaseController.php:0
 * @route '/purchase/{purchase}'
 */
export const update = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/purchase/{purchase}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\PurchaseController::update
 * @see app/Http/Controllers/PurchaseController.php:0
 * @route '/purchase/{purchase}'
 */
update.url = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { purchase: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    purchase: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        purchase: args.purchase,
                }

    return update.definition.url
            .replace('{purchase}', parsedArgs.purchase.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::update
 * @see app/Http/Controllers/PurchaseController.php:0
 * @route '/purchase/{purchase}'
 */
update.put = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\PurchaseController::update
 * @see app/Http/Controllers/PurchaseController.php:0
 * @route '/purchase/{purchase}'
 */
update.patch = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\PurchaseController::destroy
 * @see app/Http/Controllers/PurchaseController.php:0
 * @route '/purchase/{purchase}'
 */
export const destroy = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/purchase/{purchase}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\PurchaseController::destroy
 * @see app/Http/Controllers/PurchaseController.php:0
 * @route '/purchase/{purchase}'
 */
destroy.url = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { purchase: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    purchase: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        purchase: args.purchase,
                }

    return destroy.definition.url
            .replace('{purchase}', parsedArgs.purchase.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::destroy
 * @see app/Http/Controllers/PurchaseController.php:0
 * @route '/purchase/{purchase}'
 */
destroy.delete = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})
const PurchaseController = { show, receive, receiveStore, output, cancel, approve, index, store, update, destroy }

export default PurchaseController