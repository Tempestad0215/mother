import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\OutController::index
 * @see app/Http/Controllers/OutController.php:21
 * @route '/out'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/out',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OutController::index
 * @see app/Http/Controllers/OutController.php:21
 * @route '/out'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OutController::index
 * @see app/Http/Controllers/OutController.php:21
 * @route '/out'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\OutController::index
 * @see app/Http/Controllers/OutController.php:21
 * @route '/out'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OutController::store
 * @see app/Http/Controllers/OutController.php:49
 * @route '/out'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/out',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\OutController::store
 * @see app/Http/Controllers/OutController.php:49
 * @route '/out'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\OutController::store
 * @see app/Http/Controllers/OutController.php:49
 * @route '/out'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\OutController::show
 * @see app/Http/Controllers/OutController.php:115
 * @route '/out/{out}'
 */
export const show = (args: { out: string | number } | [out: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/out/{out}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\OutController::show
 * @see app/Http/Controllers/OutController.php:115
 * @route '/out/{out}'
 */
show.url = (args: { out: string | number } | [out: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { out: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    out: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        out: args.out,
                }

    return show.definition.url
            .replace('{out}', parsedArgs.out.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OutController::show
 * @see app/Http/Controllers/OutController.php:115
 * @route '/out/{out}'
 */
show.get = (args: { out: string | number } | [out: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\OutController::show
 * @see app/Http/Controllers/OutController.php:115
 * @route '/out/{out}'
 */
show.head = (args: { out: string | number } | [out: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\OutController::update
 * @see app/Http/Controllers/OutController.php:131
 * @route '/out/{out}'
 */
export const update = (args: { out: string | number } | [out: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/out/{out}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\OutController::update
 * @see app/Http/Controllers/OutController.php:131
 * @route '/out/{out}'
 */
update.url = (args: { out: string | number } | [out: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { out: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    out: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        out: args.out,
                }

    return update.definition.url
            .replace('{out}', parsedArgs.out.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OutController::update
 * @see app/Http/Controllers/OutController.php:131
 * @route '/out/{out}'
 */
update.put = (args: { out: string | number } | [out: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\OutController::update
 * @see app/Http/Controllers/OutController.php:131
 * @route '/out/{out}'
 */
update.patch = (args: { out: string | number } | [out: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\OutController::destroy
 * @see app/Http/Controllers/OutController.php:139
 * @route '/out/{out}'
 */
export const destroy = (args: { out: string | number } | [out: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/out/{out}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\OutController::destroy
 * @see app/Http/Controllers/OutController.php:139
 * @route '/out/{out}'
 */
destroy.url = (args: { out: string | number } | [out: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { out: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    out: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        out: args.out,
                }

    return destroy.definition.url
            .replace('{out}', parsedArgs.out.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\OutController::destroy
 * @see app/Http/Controllers/OutController.php:139
 * @route '/out/{out}'
 */
destroy.delete = (args: { out: string | number } | [out: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})
const out = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
show: Object.assign(show, show),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default out