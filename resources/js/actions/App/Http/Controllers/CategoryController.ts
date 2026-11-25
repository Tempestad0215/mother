import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\CategoryController::index
 * @see app/Http/Controllers/CategoryController.php:35
 * @route '/category'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/category',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CategoryController::index
 * @see app/Http/Controllers/CategoryController.php:35
 * @route '/category'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CategoryController::index
 * @see app/Http/Controllers/CategoryController.php:35
 * @route '/category'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CategoryController::index
 * @see app/Http/Controllers/CategoryController.php:35
 * @route '/category'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\CategoryController::store
 * @see app/Http/Controllers/CategoryController.php:89
 * @route '/category'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/category',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\CategoryController::store
 * @see app/Http/Controllers/CategoryController.php:89
 * @route '/category'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\CategoryController::store
 * @see app/Http/Controllers/CategoryController.php:89
 * @route '/category'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\CategoryController::show
 * @see app/Http/Controllers/CategoryController.php:0
 * @route '/category/{category}'
 */
export const show = (args: { category: string | number } | [category: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/category/{category}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\CategoryController::show
 * @see app/Http/Controllers/CategoryController.php:0
 * @route '/category/{category}'
 */
show.url = (args: { category: string | number } | [category: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { category: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    category: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        category: args.category,
                }

    return show.definition.url
            .replace('{category}', parsedArgs.category.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\CategoryController::show
 * @see app/Http/Controllers/CategoryController.php:0
 * @route '/category/{category}'
 */
show.get = (args: { category: string | number } | [category: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\CategoryController::show
 * @see app/Http/Controllers/CategoryController.php:0
 * @route '/category/{category}'
 */
show.head = (args: { category: string | number } | [category: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\CategoryController::update
 * @see app/Http/Controllers/CategoryController.php:135
 * @route '/category/{category}'
 */
export const update = (args: { category: string | { uuid: string } } | [category: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/category/{category}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\CategoryController::update
 * @see app/Http/Controllers/CategoryController.php:135
 * @route '/category/{category}'
 */
update.url = (args: { category: string | { uuid: string } } | [category: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { category: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { category: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    category: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        category: typeof args.category === 'object'
                ? args.category.uuid
                : args.category,
                }

    return update.definition.url
            .replace('{category}', parsedArgs.category.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\CategoryController::update
 * @see app/Http/Controllers/CategoryController.php:135
 * @route '/category/{category}'
 */
update.put = (args: { category: string | { uuid: string } } | [category: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\CategoryController::update
 * @see app/Http/Controllers/CategoryController.php:135
 * @route '/category/{category}'
 */
update.patch = (args: { category: string | { uuid: string } } | [category: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\CategoryController::destroy
 * @see app/Http/Controllers/CategoryController.php:155
 * @route '/category/{category}'
 */
export const destroy = (args: { category: string | { uuid: string } } | [category: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/category/{category}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\CategoryController::destroy
 * @see app/Http/Controllers/CategoryController.php:155
 * @route '/category/{category}'
 */
destroy.url = (args: { category: string | { uuid: string } } | [category: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { category: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { category: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    category: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        category: typeof args.category === 'object'
                ? args.category.uuid
                : args.category,
                }

    return destroy.definition.url
            .replace('{category}', parsedArgs.category.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\CategoryController::destroy
 * @see app/Http/Controllers/CategoryController.php:155
 * @route '/category/{category}'
 */
destroy.delete = (args: { category: string | { uuid: string } } | [category: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})
const CategoryController = { index, store, show, update, destroy }

export default CategoryController