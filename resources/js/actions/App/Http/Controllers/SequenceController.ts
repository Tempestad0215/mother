import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\SequenceController::create
 * @see app/Http/Controllers/SequenceController.php:23
 * @route '/setting/sequence'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/setting/sequence',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SequenceController::create
 * @see app/Http/Controllers/SequenceController.php:23
 * @route '/setting/sequence'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SequenceController::create
 * @see app/Http/Controllers/SequenceController.php:23
 * @route '/setting/sequence'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SequenceController::create
 * @see app/Http/Controllers/SequenceController.php:23
 * @route '/setting/sequence'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SequenceController::get
 * @see app/Http/Controllers/SequenceController.php:105
 * @route '/setting/sequence/get/{type}'
 */
export const get = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: get.url(args, options),
    method: 'get',
})

get.definition = {
    methods: ["get","head"],
    url: '/setting/sequence/get/{type}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SequenceController::get
 * @see app/Http/Controllers/SequenceController.php:105
 * @route '/setting/sequence/get/{type}'
 */
get.url = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { type: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    type: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        type: args.type,
                }

    return get.definition.url
            .replace('{type}', parsedArgs.type.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SequenceController::get
 * @see app/Http/Controllers/SequenceController.php:105
 * @route '/setting/sequence/get/{type}'
 */
get.get = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: get.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SequenceController::get
 * @see app/Http/Controllers/SequenceController.php:105
 * @route '/setting/sequence/get/{type}'
 */
get.head = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: get.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SequenceController::getRnc
 * @see app/Http/Controllers/SequenceController.php:120
 * @route '/setting/sequence/get/rnc/{rnc}'
 */
export const getRnc = (args: { rnc: string | number } | [rnc: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getRnc.url(args, options),
    method: 'get',
})

getRnc.definition = {
    methods: ["get","head"],
    url: '/setting/sequence/get/rnc/{rnc}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SequenceController::getRnc
 * @see app/Http/Controllers/SequenceController.php:120
 * @route '/setting/sequence/get/rnc/{rnc}'
 */
getRnc.url = (args: { rnc: string | number } | [rnc: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { rnc: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    rnc: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        rnc: args.rnc,
                }

    return getRnc.definition.url
            .replace('{rnc}', parsedArgs.rnc.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SequenceController::getRnc
 * @see app/Http/Controllers/SequenceController.php:120
 * @route '/setting/sequence/get/rnc/{rnc}'
 */
getRnc.get = (args: { rnc: string | number } | [rnc: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: getRnc.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SequenceController::getRnc
 * @see app/Http/Controllers/SequenceController.php:120
 * @route '/setting/sequence/get/rnc/{rnc}'
 */
getRnc.head = (args: { rnc: string | number } | [rnc: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: getRnc.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SequenceController::store
 * @see app/Http/Controllers/SequenceController.php:53
 * @route '/setting/sequence'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/setting/sequence',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\SequenceController::store
 * @see app/Http/Controllers/SequenceController.php:53
 * @route '/setting/sequence'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SequenceController::store
 * @see app/Http/Controllers/SequenceController.php:53
 * @route '/setting/sequence'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SequenceController::edit
 * @see app/Http/Controllers/SequenceController.php:71
 * @route '/setting/sequence/{sequence}'
 */
export const edit = (args: { sequence: string | { uuid: string } } | [sequence: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/setting/sequence/{sequence}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SequenceController::edit
 * @see app/Http/Controllers/SequenceController.php:71
 * @route '/setting/sequence/{sequence}'
 */
edit.url = (args: { sequence: string | { uuid: string } } | [sequence: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { sequence: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { sequence: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    sequence: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        sequence: typeof args.sequence === 'object'
                ? args.sequence.uuid
                : args.sequence,
                }

    return edit.definition.url
            .replace('{sequence}', parsedArgs.sequence.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SequenceController::edit
 * @see app/Http/Controllers/SequenceController.php:71
 * @route '/setting/sequence/{sequence}'
 */
edit.get = (args: { sequence: string | { uuid: string } } | [sequence: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SequenceController::edit
 * @see app/Http/Controllers/SequenceController.php:71
 * @route '/setting/sequence/{sequence}'
 */
edit.head = (args: { sequence: string | { uuid: string } } | [sequence: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SequenceController::destroy
 * @see app/Http/Controllers/SequenceController.php:91
 * @route '/setting/sequence/{sequence}'
 */
export const destroy = (args: { sequence: string | { uuid: string } } | [sequence: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/setting/sequence/{sequence}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\SequenceController::destroy
 * @see app/Http/Controllers/SequenceController.php:91
 * @route '/setting/sequence/{sequence}'
 */
destroy.url = (args: { sequence: string | { uuid: string } } | [sequence: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { sequence: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { sequence: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    sequence: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        sequence: typeof args.sequence === 'object'
                ? args.sequence.uuid
                : args.sequence,
                }

    return destroy.definition.url
            .replace('{sequence}', parsedArgs.sequence.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SequenceController::destroy
 * @see app/Http/Controllers/SequenceController.php:91
 * @route '/setting/sequence/{sequence}'
 */
destroy.delete = (args: { sequence: string | { uuid: string } } | [sequence: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})
const SequenceController = { create, get, getRnc, store, edit, destroy }

export default SequenceController