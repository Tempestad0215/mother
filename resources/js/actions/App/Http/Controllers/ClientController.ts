import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\ClientController::getJson
 * @see app/Http/Controllers/ClientController.php:171
 * @route '/client/get-json'
 */
export const getJson = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getJson.url(options),
    method: 'get',
})

getJson.definition = {
    methods: ["get","head"],
    url: '/client/get-json',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ClientController::getJson
 * @see app/Http/Controllers/ClientController.php:171
 * @route '/client/get-json'
 */
getJson.url = (options?: RouteQueryOptions) => {
    return getJson.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClientController::getJson
 * @see app/Http/Controllers/ClientController.php:171
 * @route '/client/get-json'
 */
getJson.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getJson.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ClientController::getJson
 * @see app/Http/Controllers/ClientController.php:171
 * @route '/client/get-json'
 */
getJson.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getJson.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ClientController::index
 * @see app/Http/Controllers/ClientController.php:53
 * @route '/client'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/client',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ClientController::index
 * @see app/Http/Controllers/ClientController.php:53
 * @route '/client'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClientController::index
 * @see app/Http/Controllers/ClientController.php:53
 * @route '/client'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ClientController::index
 * @see app/Http/Controllers/ClientController.php:53
 * @route '/client'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ClientController::store
 * @see app/Http/Controllers/ClientController.php:84
 * @route '/client'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/client',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ClientController::store
 * @see app/Http/Controllers/ClientController.php:84
 * @route '/client'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClientController::store
 * @see app/Http/Controllers/ClientController.php:84
 * @route '/client'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\ClientController::show
 * @see app/Http/Controllers/ClientController.php:99
 * @route '/client/{client}'
 */
export const show = (args: { client: string | number } | [client: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/client/{client}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ClientController::show
 * @see app/Http/Controllers/ClientController.php:99
 * @route '/client/{client}'
 */
show.url = (args: { client: string | number } | [client: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { client: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    client: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        client: args.client,
                }

    return show.definition.url
            .replace('{client}', parsedArgs.client.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClientController::show
 * @see app/Http/Controllers/ClientController.php:99
 * @route '/client/{client}'
 */
show.get = (args: { client: string | number } | [client: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ClientController::show
 * @see app/Http/Controllers/ClientController.php:99
 * @route '/client/{client}'
 */
show.head = (args: { client: string | number } | [client: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ClientController::update
 * @see app/Http/Controllers/ClientController.php:139
 * @route '/client/{client}'
 */
export const update = (args: { client: string | { uuid: string } } | [client: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/client/{client}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\ClientController::update
 * @see app/Http/Controllers/ClientController.php:139
 * @route '/client/{client}'
 */
update.url = (args: { client: string | { uuid: string } } | [client: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { client: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { client: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    client: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        client: typeof args.client === 'object'
                ? args.client.uuid
                : args.client,
                }

    return update.definition.url
            .replace('{client}', parsedArgs.client.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClientController::update
 * @see app/Http/Controllers/ClientController.php:139
 * @route '/client/{client}'
 */
update.put = (args: { client: string | { uuid: string } } | [client: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\ClientController::update
 * @see app/Http/Controllers/ClientController.php:139
 * @route '/client/{client}'
 */
update.patch = (args: { client: string | { uuid: string } } | [client: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\ClientController::destroy
 * @see app/Http/Controllers/ClientController.php:153
 * @route '/client/{client}'
 */
export const destroy = (args: { client: string | { uuid: string } } | [client: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/client/{client}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\ClientController::destroy
 * @see app/Http/Controllers/ClientController.php:153
 * @route '/client/{client}'
 */
destroy.url = (args: { client: string | { uuid: string } } | [client: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { client: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { client: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    client: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        client: typeof args.client === 'object'
                ? args.client.uuid
                : args.client,
                }

    return destroy.definition.url
            .replace('{client}', parsedArgs.client.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClientController::destroy
 * @see app/Http/Controllers/ClientController.php:153
 * @route '/client/{client}'
 */
destroy.delete = (args: { client: string | { uuid: string } } | [client: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})
const ClientController = { getJson, index, store, show, update, destroy }

export default ClientController