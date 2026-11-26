import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\UnitController::index
* @see app/Http/Controllers/UnitController.php:12
* @route '/unit'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/unit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\UnitController::index
* @see app/Http/Controllers/UnitController.php:12
* @route '/unit'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UnitController::index
* @see app/Http/Controllers/UnitController.php:12
* @route '/unit'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UnitController::index
* @see app/Http/Controllers/UnitController.php:12
* @route '/unit'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\UnitController::index
* @see app/Http/Controllers/UnitController.php:12
* @route '/unit'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UnitController::index
* @see app/Http/Controllers/UnitController.php:12
* @route '/unit'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UnitController::index
* @see app/Http/Controllers/UnitController.php:12
* @route '/unit'
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
* @see \App\Http\Controllers\UnitController::store
* @see app/Http/Controllers/UnitController.php:22
* @route '/unit'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/unit',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\UnitController::store
* @see app/Http/Controllers/UnitController.php:22
* @route '/unit'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UnitController::store
* @see app/Http/Controllers/UnitController.php:22
* @route '/unit'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UnitController::store
* @see app/Http/Controllers/UnitController.php:22
* @route '/unit'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UnitController::store
* @see app/Http/Controllers/UnitController.php:22
* @route '/unit'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\UnitController::show
* @see app/Http/Controllers/UnitController.php:0
* @route '/unit/{unit}'
*/
export const show = (args: { unit: string | number } | [unit: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/unit/{unit}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\UnitController::show
* @see app/Http/Controllers/UnitController.php:0
* @route '/unit/{unit}'
*/
show.url = (args: { unit: string | number } | [unit: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { unit: args }
    }

    if (Array.isArray(args)) {
        args = {
            unit: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        unit: args.unit,
    }

    return show.definition.url
            .replace('{unit}', parsedArgs.unit.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\UnitController::show
* @see app/Http/Controllers/UnitController.php:0
* @route '/unit/{unit}'
*/
show.get = (args: { unit: string | number } | [unit: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UnitController::show
* @see app/Http/Controllers/UnitController.php:0
* @route '/unit/{unit}'
*/
show.head = (args: { unit: string | number } | [unit: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\UnitController::show
* @see app/Http/Controllers/UnitController.php:0
* @route '/unit/{unit}'
*/
const showForm = (args: { unit: string | number } | [unit: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UnitController::show
* @see app/Http/Controllers/UnitController.php:0
* @route '/unit/{unit}'
*/
showForm.get = (args: { unit: string | number } | [unit: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\UnitController::show
* @see app/Http/Controllers/UnitController.php:0
* @route '/unit/{unit}'
*/
showForm.head = (args: { unit: string | number } | [unit: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\UnitController::update
* @see app/Http/Controllers/UnitController.php:34
* @route '/unit/{unit}'
*/
export const update = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/unit/{unit}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\UnitController::update
* @see app/Http/Controllers/UnitController.php:34
* @route '/unit/{unit}'
*/
update.url = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { unit: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
        args = { unit: args.uuid }
    }

    if (Array.isArray(args)) {
        args = {
            unit: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        unit: typeof args.unit === 'object'
        ? args.unit.uuid
        : args.unit,
    }

    return update.definition.url
            .replace('{unit}', parsedArgs.unit.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\UnitController::update
* @see app/Http/Controllers/UnitController.php:34
* @route '/unit/{unit}'
*/
update.put = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\UnitController::update
* @see app/Http/Controllers/UnitController.php:34
* @route '/unit/{unit}'
*/
update.patch = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\UnitController::update
* @see app/Http/Controllers/UnitController.php:34
* @route '/unit/{unit}'
*/
const updateForm = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UnitController::update
* @see app/Http/Controllers/UnitController.php:34
* @route '/unit/{unit}'
*/
updateForm.put = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UnitController::update
* @see app/Http/Controllers/UnitController.php:34
* @route '/unit/{unit}'
*/
updateForm.patch = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\UnitController::destroy
* @see app/Http/Controllers/UnitController.php:46
* @route '/unit/{unit}'
*/
export const destroy = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/unit/{unit}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\UnitController::destroy
* @see app/Http/Controllers/UnitController.php:46
* @route '/unit/{unit}'
*/
destroy.url = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { unit: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
        args = { unit: args.uuid }
    }

    if (Array.isArray(args)) {
        args = {
            unit: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        unit: typeof args.unit === 'object'
        ? args.unit.uuid
        : args.unit,
    }

    return destroy.definition.url
            .replace('{unit}', parsedArgs.unit.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\UnitController::destroy
* @see app/Http/Controllers/UnitController.php:46
* @route '/unit/{unit}'
*/
destroy.delete = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\UnitController::destroy
* @see app/Http/Controllers/UnitController.php:46
* @route '/unit/{unit}'
*/
const destroyForm = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UnitController::destroy
* @see app/Http/Controllers/UnitController.php:46
* @route '/unit/{unit}'
*/
destroyForm.delete = (args: { unit: string | { uuid: string } } | [unit: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const UnitController = { index, store, show, update, destroy }

export default UnitController