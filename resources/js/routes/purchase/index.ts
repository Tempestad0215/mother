import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import receiving from './receiving'
/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/show'
*/
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/purchase/show',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/show'
*/
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/show'
*/
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/show'
*/
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/show'
*/
const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/show'
*/
showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/show'
*/
showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/{purchase}'
*/
export const show = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/purchase/{purchase}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/{purchase}'
*/
show.url = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{purchase}', parsedArgs.purchase.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/{purchase}'
*/
show.get = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/{purchase}'
*/
show.head = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/{purchase}'
*/
const showForm = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/{purchase}'
*/
showForm.get = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::show
* @see app/Http/Controllers/PurchaseController.php:147
* @route '/purchase/{purchase}'
*/
showForm.head = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

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
* @see \App\Http\Controllers\PurchaseController::receive
* @see app/Http/Controllers/PurchaseController.php:189
* @route '/purchase/receive'
*/
const receiveForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: receive.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::receive
* @see app/Http/Controllers/PurchaseController.php:189
* @route '/purchase/receive'
*/
receiveForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: receive.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::receive
* @see app/Http/Controllers/PurchaseController.php:189
* @route '/purchase/receive'
*/
receiveForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: receive.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

receive.form = receiveForm

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
* @see \App\Http\Controllers\PurchaseController::receiveStore
* @see app/Http/Controllers/PurchaseController.php:202
* @route '/purchase/receive'
*/
const receiveStoreForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: receiveStore.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PurchaseController::receiveStore
* @see app/Http/Controllers/PurchaseController.php:202
* @route '/purchase/receive'
*/
receiveStoreForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: receiveStore.url(options),
    method: 'post',
})

receiveStore.form = receiveStoreForm

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
* @see \App\Http\Controllers\PurchaseController::output
* @see app/Http/Controllers/PurchaseController.php:214
* @route '/purchase/output'
*/
const outputForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: output.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::output
* @see app/Http/Controllers/PurchaseController.php:214
* @route '/purchase/output'
*/
outputForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: output.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::output
* @see app/Http/Controllers/PurchaseController.php:214
* @route '/purchase/output'
*/
outputForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: output.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

output.form = outputForm

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
* @see \App\Http\Controllers\PurchaseController::cancel
* @see app/Http/Controllers/PurchaseController.php:178
* @route '/purchase/{purchase}/cancel'
*/
const cancelForm = (args: { purchase: string | { uuid: string } } | [purchase: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: cancel.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PurchaseController::cancel
* @see app/Http/Controllers/PurchaseController.php:178
* @route '/purchase/{purchase}/cancel'
*/
cancelForm.patch = (args: { purchase: string | { uuid: string } } | [purchase: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: cancel.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

cancel.form = cancelForm

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
* @see \App\Http\Controllers\PurchaseController::approve
* @see app/Http/Controllers/PurchaseController.php:168
* @route '/purchase/{purchase}/approve'
*/
const approveForm = (args: { purchase: string | { uuid: string } } | [purchase: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: approve.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PurchaseController::approve
* @see app/Http/Controllers/PurchaseController.php:168
* @route '/purchase/{purchase}/approve'
*/
approveForm.patch = (args: { purchase: string | { uuid: string } } | [purchase: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: approve.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

approve.form = approveForm

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
* @see \App\Http\Controllers\PurchaseController::index
* @see app/Http/Controllers/PurchaseController.php:34
* @route '/purchase'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::index
* @see app/Http/Controllers/PurchaseController.php:34
* @route '/purchase'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\PurchaseController::index
* @see app/Http/Controllers/PurchaseController.php:34
* @route '/purchase'
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
* @see \App\Http\Controllers\PurchaseController::store
* @see app/Http/Controllers/PurchaseController.php:71
* @route '/purchase'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PurchaseController::store
* @see app/Http/Controllers/PurchaseController.php:71
* @route '/purchase'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

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
* @see \App\Http\Controllers\PurchaseController::update
* @see app/Http/Controllers/PurchaseController.php:0
* @route '/purchase/{purchase}'
*/
const updateForm = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PurchaseController::update
* @see app/Http/Controllers/PurchaseController.php:0
* @route '/purchase/{purchase}'
*/
updateForm.put = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PurchaseController::update
* @see app/Http/Controllers/PurchaseController.php:0
* @route '/purchase/{purchase}'
*/
updateForm.patch = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

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

/**
* @see \App\Http\Controllers\PurchaseController::destroy
* @see app/Http/Controllers/PurchaseController.php:0
* @route '/purchase/{purchase}'
*/
const destroyForm = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\PurchaseController::destroy
* @see app/Http/Controllers/PurchaseController.php:0
* @route '/purchase/{purchase}'
*/
destroyForm.delete = (args: { purchase: string | number } | [purchase: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const purchase = {
    show: Object.assign(show, show),
    receive: Object.assign(receive, receive),
    receiveStore: Object.assign(receiveStore, receiveStore),
    output: Object.assign(output, output),
    cancel: Object.assign(cancel, cancel),
    approve: Object.assign(approve, approve),
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
    receiving: Object.assign(receiving, receiving),
}

export default purchase