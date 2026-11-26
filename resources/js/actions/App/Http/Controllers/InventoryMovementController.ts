import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\InventoryMovementController::index
* @see app/Http/Controllers/InventoryMovementController.php:38
* @route '/inventory-movement'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/inventory-movement',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InventoryMovementController::index
* @see app/Http/Controllers/InventoryMovementController.php:38
* @route '/inventory-movement'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\InventoryMovementController::index
* @see app/Http/Controllers/InventoryMovementController.php:38
* @route '/inventory-movement'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::index
* @see app/Http/Controllers/InventoryMovementController.php:38
* @route '/inventory-movement'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::index
* @see app/Http/Controllers/InventoryMovementController.php:38
* @route '/inventory-movement'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::index
* @see app/Http/Controllers/InventoryMovementController.php:38
* @route '/inventory-movement'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::index
* @see app/Http/Controllers/InventoryMovementController.php:38
* @route '/inventory-movement'
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
* @see \App\Http\Controllers\InventoryMovementController::store
* @see app/Http/Controllers/InventoryMovementController.php:0
* @route '/inventory-movement'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/inventory-movement',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InventoryMovementController::store
* @see app/Http/Controllers/InventoryMovementController.php:0
* @route '/inventory-movement'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\InventoryMovementController::store
* @see app/Http/Controllers/InventoryMovementController.php:0
* @route '/inventory-movement'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::store
* @see app/Http/Controllers/InventoryMovementController.php:0
* @route '/inventory-movement'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::store
* @see app/Http/Controllers/InventoryMovementController.php:0
* @route '/inventory-movement'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\InventoryMovementController::show
* @see app/Http/Controllers/InventoryMovementController.php:96
* @route '/inventory-movement/{entry}'
*/
export const show = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/inventory-movement/{entry}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InventoryMovementController::show
* @see app/Http/Controllers/InventoryMovementController.php:96
* @route '/inventory-movement/{entry}'
*/
show.url = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { entry: args }
    }

    if (Array.isArray(args)) {
        args = {
            entry: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        entry: args.entry,
    }

    return show.definition.url
            .replace('{entry}', parsedArgs.entry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InventoryMovementController::show
* @see app/Http/Controllers/InventoryMovementController.php:96
* @route '/inventory-movement/{entry}'
*/
show.get = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::show
* @see app/Http/Controllers/InventoryMovementController.php:96
* @route '/inventory-movement/{entry}'
*/
show.head = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::show
* @see app/Http/Controllers/InventoryMovementController.php:96
* @route '/inventory-movement/{entry}'
*/
const showForm = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::show
* @see app/Http/Controllers/InventoryMovementController.php:96
* @route '/inventory-movement/{entry}'
*/
showForm.get = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::show
* @see app/Http/Controllers/InventoryMovementController.php:96
* @route '/inventory-movement/{entry}'
*/
showForm.head = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\InventoryMovementController::update
* @see app/Http/Controllers/InventoryMovementController.php:124
* @route '/inventory-movement/{entry}'
*/
export const update = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/inventory-movement/{entry}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\InventoryMovementController::update
* @see app/Http/Controllers/InventoryMovementController.php:124
* @route '/inventory-movement/{entry}'
*/
update.url = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { entry: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
        args = { entry: args.uuid }
    }

    if (Array.isArray(args)) {
        args = {
            entry: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        entry: typeof args.entry === 'object'
        ? args.entry.uuid
        : args.entry,
    }

    return update.definition.url
            .replace('{entry}', parsedArgs.entry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InventoryMovementController::update
* @see app/Http/Controllers/InventoryMovementController.php:124
* @route '/inventory-movement/{entry}'
*/
update.put = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::update
* @see app/Http/Controllers/InventoryMovementController.php:124
* @route '/inventory-movement/{entry}'
*/
update.patch = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::update
* @see app/Http/Controllers/InventoryMovementController.php:124
* @route '/inventory-movement/{entry}'
*/
const updateForm = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::update
* @see app/Http/Controllers/InventoryMovementController.php:124
* @route '/inventory-movement/{entry}'
*/
updateForm.put = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::update
* @see app/Http/Controllers/InventoryMovementController.php:124
* @route '/inventory-movement/{entry}'
*/
updateForm.patch = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\InventoryMovementController::destroy
* @see app/Http/Controllers/InventoryMovementController.php:170
* @route '/inventory-movement/{entry}'
*/
export const destroy = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/inventory-movement/{entry}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\InventoryMovementController::destroy
* @see app/Http/Controllers/InventoryMovementController.php:170
* @route '/inventory-movement/{entry}'
*/
destroy.url = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { entry: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
        args = { entry: args.uuid }
    }

    if (Array.isArray(args)) {
        args = {
            entry: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        entry: typeof args.entry === 'object'
        ? args.entry.uuid
        : args.entry,
    }

    return destroy.definition.url
            .replace('{entry}', parsedArgs.entry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InventoryMovementController::destroy
* @see app/Http/Controllers/InventoryMovementController.php:170
* @route '/inventory-movement/{entry}'
*/
destroy.delete = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::destroy
* @see app/Http/Controllers/InventoryMovementController.php:170
* @route '/inventory-movement/{entry}'
*/
const destroyForm = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InventoryMovementController::destroy
* @see app/Http/Controllers/InventoryMovementController.php:170
* @route '/inventory-movement/{entry}'
*/
destroyForm.delete = (args: { entry: string | { uuid: string } } | [entry: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const InventoryMovementController = { index, store, show, update, destroy }

export default InventoryMovementController