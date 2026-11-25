import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\EntryController::index
 * @see app/Http/Controllers/EntryController.php:42
 * @route '/entry'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/entry',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EntryController::index
 * @see app/Http/Controllers/EntryController.php:42
 * @route '/entry'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\EntryController::index
 * @see app/Http/Controllers/EntryController.php:42
 * @route '/entry'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\EntryController::index
 * @see app/Http/Controllers/EntryController.php:42
 * @route '/entry'
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
* @see \App\Http\Controllers\EntryController::store
 * @see app/Http/Controllers/EntryController.php:62
 * @route '/entry'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/entry',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\EntryController::store
 * @see app/Http/Controllers/EntryController.php:62
 * @route '/entry'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\EntryController::store
 * @see app/Http/Controllers/EntryController.php:62
 * @route '/entry'
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
* @see \App\Http\Controllers\EntryController::show
 * @see app/Http/Controllers/EntryController.php:170
 * @route '/entry/{entry}'
 */
export const show = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/entry/{entry}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\EntryController::show
 * @see app/Http/Controllers/EntryController.php:170
 * @route '/entry/{entry}'
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
* @see \App\Http\Controllers\EntryController::show
 * @see app/Http/Controllers/EntryController.php:170
 * @route '/entry/{entry}'
 */
show.get = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\EntryController::show
 * @see app/Http/Controllers/EntryController.php:170
 * @route '/entry/{entry}'
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
* @see \App\Http\Controllers\EntryController::update
 * @see app/Http/Controllers/EntryController.php:191
 * @route '/entry/{entry}'
 */
export const update = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/entry/{entry}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\EntryController::update
 * @see app/Http/Controllers/EntryController.php:191
 * @route '/entry/{entry}'
 */
update.url = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{entry}', parsedArgs.entry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EntryController::update
 * @see app/Http/Controllers/EntryController.php:191
 * @route '/entry/{entry}'
 */
update.put = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\EntryController::update
 * @see app/Http/Controllers/EntryController.php:191
 * @route '/entry/{entry}'
 */
update.patch = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

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
* @see \App\Http\Controllers\EntryController::destroy
 * @see app/Http/Controllers/EntryController.php:240
 * @route '/entry/{entry}'
 */
export const destroy = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/entry/{entry}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\EntryController::destroy
 * @see app/Http/Controllers/EntryController.php:240
 * @route '/entry/{entry}'
 */
destroy.url = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{entry}', parsedArgs.entry.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\EntryController::destroy
 * @see app/Http/Controllers/EntryController.php:240
 * @route '/entry/{entry}'
 */
destroy.delete = (args: { entry: string | number } | [entry: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

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
const entry = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
show: Object.assign(show, show),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default entry