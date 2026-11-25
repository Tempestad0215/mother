import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\UserController::store
 * @see app/Http/Controllers/UserController.php:38
 * @route '/user'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/user',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\UserController::store
 * @see app/Http/Controllers/UserController.php:38
 * @route '/user'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UserController::store
 * @see app/Http/Controllers/UserController.php:38
 * @route '/user'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\UserController::update
 * @see app/Http/Controllers/UserController.php:64
 * @route '/user/{user}'
 */
export const update = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/user/{user}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\UserController::update
 * @see app/Http/Controllers/UserController.php:64
 * @route '/user/{user}'
 */
update.url = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\UserController::update
 * @see app/Http/Controllers/UserController.php:64
 * @route '/user/{user}'
 */
update.patch = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\UserController::destroy
 * @see app/Http/Controllers/UserController.php:96
 * @route '/user/destroy/{user}'
 */
export const destroy = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: destroy.url(args, options),
    method: 'patch',
})

destroy.definition = {
    methods: ["patch"],
    url: '/user/destroy/{user}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\UserController::destroy
 * @see app/Http/Controllers/UserController.php:96
 * @route '/user/destroy/{user}'
 */
destroy.url = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\UserController::destroy
 * @see app/Http/Controllers/UserController.php:96
 * @route '/user/destroy/{user}'
 */
destroy.patch = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: destroy.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\UserController::assingRoleIndex
 * @see app/Http/Controllers/UserController.php:110
 * @route '/user/role'
 */
export const assingRoleIndex = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assingRoleIndex.url(options),
    method: 'get',
})

assingRoleIndex.definition = {
    methods: ["get","head"],
    url: '/user/role',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\UserController::assingRoleIndex
 * @see app/Http/Controllers/UserController.php:110
 * @route '/user/role'
 */
assingRoleIndex.url = (options?: RouteQueryOptions) => {
    return assingRoleIndex.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\UserController::assingRoleIndex
 * @see app/Http/Controllers/UserController.php:110
 * @route '/user/role'
 */
assingRoleIndex.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: assingRoleIndex.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\UserController::assingRoleIndex
 * @see app/Http/Controllers/UserController.php:110
 * @route '/user/role'
 */
assingRoleIndex.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: assingRoleIndex.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\UserController::assingRole
 * @see app/Http/Controllers/UserController.php:120
 * @route '/user/role/{user}'
 */
export const assingRole = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: assingRole.url(args, options),
    method: 'patch',
})

assingRole.definition = {
    methods: ["patch"],
    url: '/user/role/{user}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\UserController::assingRole
 * @see app/Http/Controllers/UserController.php:120
 * @route '/user/role/{user}'
 */
assingRole.url = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
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

    return assingRole.definition.url
            .replace('{user}', parsedArgs.user.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\UserController::assingRole
 * @see app/Http/Controllers/UserController.php:120
 * @route '/user/role/{user}'
 */
assingRole.patch = (args: { user: string | { uuid: string } } | [user: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: assingRole.url(args, options),
    method: 'patch',
})
const UserController = { store, update, destroy, assingRoleIndex, assingRole }

export default UserController