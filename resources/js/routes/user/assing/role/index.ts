import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\UserController::index
 * @see app/Http/Controllers/UserController.php:110
 * @route '/user/role'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/user/role',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\UserController::index
 * @see app/Http/Controllers/UserController.php:110
 * @route '/user/role'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UserController::index
 * @see app/Http/Controllers/UserController.php:110
 * @route '/user/role'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\UserController::index
 * @see app/Http/Controllers/UserController.php:110
 * @route '/user/role'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\UserController::post
 * @see app/Http/Controllers/UserController.php:120
 * @route '/user/role/{user}'
 */
export const post = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: post.url(args, options),
    method: 'patch',
})

post.definition = {
    methods: ["patch"],
    url: '/user/role/{user}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\UserController::post
 * @see app/Http/Controllers/UserController.php:120
 * @route '/user/role/{user}'
 */
post.url = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { user: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { user: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    user: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        user: typeof args.user === 'object'
                ? args.user.uuid
                : args.user,
                }

    return post.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\UserController::post
 * @see app/Http/Controllers/UserController.php:120
 * @route '/user/role/{user}'
 */
post.patch = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: post.url(args, options),
    method: 'patch',
})
const role = {
    index: Object.assign(index, index),
post: Object.assign(post, post),
}

export default role