import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\BrandController::index
 * @see app/Http/Controllers/BrandController.php:12
 * @route '/branch'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/branch',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BrandController::index
 * @see app/Http/Controllers/BrandController.php:12
 * @route '/branch'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BrandController::index
 * @see app/Http/Controllers/BrandController.php:12
 * @route '/branch'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BrandController::index
 * @see app/Http/Controllers/BrandController.php:12
 * @route '/branch'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\BrandController::store
 * @see app/Http/Controllers/BrandController.php:22
 * @route '/branch'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/branch',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\BrandController::store
 * @see app/Http/Controllers/BrandController.php:22
 * @route '/branch'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\BrandController::store
 * @see app/Http/Controllers/BrandController.php:22
 * @route '/branch'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\BrandController::show
 * @see app/Http/Controllers/BrandController.php:0
 * @route '/branch/{branch}'
 */
export const show = (args: { branch: string | number } | [branch: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/branch/{branch}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\BrandController::show
 * @see app/Http/Controllers/BrandController.php:0
 * @route '/branch/{branch}'
 */
show.url = (args: { branch: string | number } | [branch: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { branch: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    branch: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        branch: args.branch,
                }

    return show.definition.url
            .replace('{branch}', parsedArgs.branch.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\BrandController::show
 * @see app/Http/Controllers/BrandController.php:0
 * @route '/branch/{branch}'
 */
show.get = (args: { branch: string | number } | [branch: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\BrandController::show
 * @see app/Http/Controllers/BrandController.php:0
 * @route '/branch/{branch}'
 */
show.head = (args: { branch: string | number } | [branch: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\BrandController::update
 * @see app/Http/Controllers/BrandController.php:35
 * @route '/branch/{branch}'
 */
export const update = (args: { branch: string | { uuid: string } } | [branch: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/branch/{branch}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\BrandController::update
 * @see app/Http/Controllers/BrandController.php:35
 * @route '/branch/{branch}'
 */
update.url = (args: { branch: string | { uuid: string } } | [branch: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { branch: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { branch: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    branch: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        branch: typeof args.branch === 'object'
                ? args.branch.uuid
                : args.branch,
                }

    return update.definition.url
            .replace('{branch}', parsedArgs.branch.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\BrandController::update
 * @see app/Http/Controllers/BrandController.php:35
 * @route '/branch/{branch}'
 */
update.put = (args: { branch: string | { uuid: string } } | [branch: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\BrandController::update
 * @see app/Http/Controllers/BrandController.php:35
 * @route '/branch/{branch}'
 */
update.patch = (args: { branch: string | { uuid: string } } | [branch: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\BrandController::destroy
 * @see app/Http/Controllers/BrandController.php:48
 * @route '/branch/{branch}'
 */
export const destroy = (args: { branch: string | { uuid: string } } | [branch: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/branch/{branch}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\BrandController::destroy
 * @see app/Http/Controllers/BrandController.php:48
 * @route '/branch/{branch}'
 */
destroy.url = (args: { branch: string | { uuid: string } } | [branch: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { branch: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { branch: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    branch: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        branch: typeof args.branch === 'object'
                ? args.branch.uuid
                : args.branch,
                }

    return destroy.definition.url
            .replace('{branch}', parsedArgs.branch.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\BrandController::destroy
 * @see app/Http/Controllers/BrandController.php:48
 * @route '/branch/{branch}'
 */
destroy.delete = (args: { branch: string | { uuid: string } } | [branch: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})
const branch = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
show: Object.assign(show, show),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default branch