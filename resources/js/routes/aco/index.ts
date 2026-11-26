import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\AccontCoController::index
* @see app/Http/Controllers/AccontCoController.php:35
* @route '/aco'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/aco',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AccontCoController::index
* @see app/Http/Controllers/AccontCoController.php:35
* @route '/aco'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::index
* @see app/Http/Controllers/AccontCoController.php:35
* @route '/aco'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AccontCoController::index
* @see app/Http/Controllers/AccontCoController.php:35
* @route '/aco'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\AccontCoController::index
* @see app/Http/Controllers/AccontCoController.php:35
* @route '/aco'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AccontCoController::index
* @see app/Http/Controllers/AccontCoController.php:35
* @route '/aco'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AccontCoController::index
* @see app/Http/Controllers/AccontCoController.php:35
* @route '/aco'
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
* @see \App\Http\Controllers\AccontCoController::store
* @see app/Http/Controllers/AccontCoController.php:49
* @route '/aco'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/aco',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AccontCoController::store
* @see app/Http/Controllers/AccontCoController.php:49
* @route '/aco'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::store
* @see app/Http/Controllers/AccontCoController.php:49
* @route '/aco'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AccontCoController::store
* @see app/Http/Controllers/AccontCoController.php:49
* @route '/aco'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AccontCoController::store
* @see app/Http/Controllers/AccontCoController.php:49
* @route '/aco'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

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
* @see \App\Http\Controllers\AccontCoController::show
* @see app/Http/Controllers/AccontCoController.php:0
* @route '/aco/{aco}'
*/
const showForm = (args: { aco: string | number } | [aco: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AccontCoController::show
* @see app/Http/Controllers/AccontCoController.php:0
* @route '/aco/{aco}'
*/
showForm.get = (args: { aco: string | number } | [aco: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\AccontCoController::show
* @see app/Http/Controllers/AccontCoController.php:0
* @route '/aco/{aco}'
*/
showForm.head = (args: { aco: string | number } | [aco: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\AccontCoController::update
* @see app/Http/Controllers/AccontCoController.php:70
* @route '/aco/{aco}'
*/
const updateForm = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AccontCoController::update
* @see app/Http/Controllers/AccontCoController.php:70
* @route '/aco/{aco}'
*/
updateForm.put = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AccontCoController::update
* @see app/Http/Controllers/AccontCoController.php:70
* @route '/aco/{aco}'
*/
updateForm.patch = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\AccontCoController::destroy
* @see app/Http/Controllers/AccontCoController.php:92
* @route '/aco/{aco}'
*/
export const destroy = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/aco/{aco}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\AccontCoController::destroy
* @see app/Http/Controllers/AccontCoController.php:92
* @route '/aco/{aco}'
*/
destroy.url = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{aco}', parsedArgs.aco.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::destroy
* @see app/Http/Controllers/AccontCoController.php:92
* @route '/aco/{aco}'
*/
destroy.delete = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\AccontCoController::destroy
* @see app/Http/Controllers/AccontCoController.php:92
* @route '/aco/{aco}'
*/
const destroyForm = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AccontCoController::destroy
* @see app/Http/Controllers/AccontCoController.php:92
* @route '/aco/{aco}'
*/
destroyForm.delete = (args: { aco: string | number | { id: string | number } } | [aco: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const aco = {
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default aco