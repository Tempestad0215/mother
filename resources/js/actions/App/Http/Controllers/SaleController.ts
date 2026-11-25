import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\SaleController::showSold
 * @see app/Http/Controllers/SaleController.php:373
 * @route '/sale/get/sold'
 */
export const showSold = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showSold.url(options),
    method: 'get',
})

showSold.definition = {
    methods: ["get","head"],
    url: '/sale/get/sold',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SaleController::showSold
 * @see app/Http/Controllers/SaleController.php:373
 * @route '/sale/get/sold'
 */
showSold.url = (options?: RouteQueryOptions) => {
    return showSold.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::showSold
 * @see app/Http/Controllers/SaleController.php:373
 * @route '/sale/get/sold'
 */
showSold.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showSold.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SaleController::showSold
 * @see app/Http/Controllers/SaleController.php:373
 * @route '/sale/get/sold'
 */
showSold.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: showSold.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SaleController::getSold
 * @see app/Http/Controllers/SaleController.php:387
 * @route '/sale/get/sold'
 */
export const getSold = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: getSold.url(options),
    method: 'post',
})

getSold.definition = {
    methods: ["post"],
    url: '/sale/get/sold',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\SaleController::getSold
 * @see app/Http/Controllers/SaleController.php:387
 * @route '/sale/get/sold'
 */
getSold.url = (options?: RouteQueryOptions) => {
    return getSold.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::getSold
 * @see app/Http/Controllers/SaleController.php:387
 * @route '/sale/get/sold'
 */
getSold.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: getSold.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SaleController::refund
 * @see app/Http/Controllers/SaleController.php:345
 * @route '/sale/refund/{code}'
 */
export const refund = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: refund.url(args, options),
    method: 'get',
})

refund.definition = {
    methods: ["get","head"],
    url: '/sale/refund/{code}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SaleController::refund
 * @see app/Http/Controllers/SaleController.php:345
 * @route '/sale/refund/{code}'
 */
refund.url = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { code: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    code: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        code: args.code,
                }

    return refund.definition.url
            .replace('{code}', parsedArgs.code.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::refund
 * @see app/Http/Controllers/SaleController.php:345
 * @route '/sale/refund/{code}'
 */
refund.get = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: refund.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SaleController::refund
 * @see app/Http/Controllers/SaleController.php:345
 * @route '/sale/refund/{code}'
 */
refund.head = (args: { code: string | number } | [code: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: refund.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SaleController::close
 * @see app/Http/Controllers/SaleController.php:287
 * @route '/sale/close'
 */
export const close = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: close.url(options),
    method: 'get',
})

close.definition = {
    methods: ["get","head"],
    url: '/sale/close',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SaleController::close
 * @see app/Http/Controllers/SaleController.php:287
 * @route '/sale/close'
 */
close.url = (options?: RouteQueryOptions) => {
    return close.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::close
 * @see app/Http/Controllers/SaleController.php:287
 * @route '/sale/close'
 */
close.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: close.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SaleController::close
 * @see app/Http/Controllers/SaleController.php:287
 * @route '/sale/close'
 */
close.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: close.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SaleController::getClose
 * @see app/Http/Controllers/SaleController.php:301
 * @route '/sale/close/get'
 */
export const getClose = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: getClose.url(options),
    method: 'post',
})

getClose.definition = {
    methods: ["post"],
    url: '/sale/close/get',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\SaleController::getClose
 * @see app/Http/Controllers/SaleController.php:301
 * @route '/sale/close/get'
 */
getClose.url = (options?: RouteQueryOptions) => {
    return getClose.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::getClose
 * @see app/Http/Controllers/SaleController.php:301
 * @route '/sale/close/get'
 */
getClose.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: getClose.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SaleController::counter
 * @see app/Http/Controllers/SaleController.php:361
 * @route '/sale/counter'
 */
export const counter = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: counter.url(options),
    method: 'get',
})

counter.definition = {
    methods: ["get","head"],
    url: '/sale/counter',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SaleController::counter
 * @see app/Http/Controllers/SaleController.php:361
 * @route '/sale/counter'
 */
counter.url = (options?: RouteQueryOptions) => {
    return counter.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::counter
 * @see app/Http/Controllers/SaleController.php:361
 * @route '/sale/counter'
 */
counter.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: counter.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SaleController::counter
 * @see app/Http/Controllers/SaleController.php:361
 * @route '/sale/counter'
 */
counter.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: counter.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SaleController::destroyItem
 * @see app/Http/Controllers/SaleController.php:217
 * @route '/sale/item/destroy/{product}/{sale}'
 */
export const destroyItem = (args: { product: string | { uuid: string }, sale: string | { uuid: string } } | [product: string | { uuid: string }, sale: string | { uuid: string } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: destroyItem.url(args, options),
    method: 'patch',
})

destroyItem.definition = {
    methods: ["patch"],
    url: '/sale/item/destroy/{product}/{sale}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\SaleController::destroyItem
 * @see app/Http/Controllers/SaleController.php:217
 * @route '/sale/item/destroy/{product}/{sale}'
 */
destroyItem.url = (args: { product: string | { uuid: string }, sale: string | { uuid: string } } | [product: string | { uuid: string }, sale: string | { uuid: string } ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    product: args[0],
                    sale: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        product: typeof args.product === 'object'
                ? args.product.uuid
                : args.product,
                                sale: typeof args.sale === 'object'
                ? args.sale.uuid
                : args.sale,
                }

    return destroyItem.definition.url
            .replace('{product}', parsedArgs.product.toString())
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::destroyItem
 * @see app/Http/Controllers/SaleController.php:217
 * @route '/sale/item/destroy/{product}/{sale}'
 */
destroyItem.patch = (args: { product: string | { uuid: string }, sale: string | { uuid: string } } | [product: string | { uuid: string }, sale: string | { uuid: string } ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: destroyItem.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\SaleController::destroySale
 * @see app/Http/Controllers/SaleController.php:238
 * @route '/sale/destroy/{sale}/{inventoried}'
 */
export const destroySale = (args: { sale: string | { uuid: string }, inventoried: string | number } | [sale: string | { uuid: string }, inventoried: string | number ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: destroySale.url(args, options),
    method: 'patch',
})

destroySale.definition = {
    methods: ["patch"],
    url: '/sale/destroy/{sale}/{inventoried}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\SaleController::destroySale
 * @see app/Http/Controllers/SaleController.php:238
 * @route '/sale/destroy/{sale}/{inventoried}'
 */
destroySale.url = (args: { sale: string | { uuid: string }, inventoried: string | number } | [sale: string | { uuid: string }, inventoried: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
                    sale: args[0],
                    inventoried: args[1],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        sale: typeof args.sale === 'object'
                ? args.sale.uuid
                : args.sale,
                                inventoried: args.inventoried,
                }

    return destroySale.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace('{inventoried}', parsedArgs.inventoried.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::destroySale
 * @see app/Http/Controllers/SaleController.php:238
 * @route '/sale/destroy/{sale}/{inventoried}'
 */
destroySale.patch = (args: { sale: string | { uuid: string }, inventoried: string | number } | [sale: string | { uuid: string }, inventoried: string | number ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: destroySale.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\SaleController::convert
 * @see app/Http/Controllers/SaleController.php:178
 * @route '/sale/convert/{sale}'
 */
export const convert = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: convert.url(args, options),
    method: 'get',
})

convert.definition = {
    methods: ["get","head"],
    url: '/sale/convert/{sale}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SaleController::convert
 * @see app/Http/Controllers/SaleController.php:178
 * @route '/sale/convert/{sale}'
 */
convert.url = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { sale: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { sale: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    sale: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        sale: typeof args.sale === 'object'
                ? args.sale.uuid
                : args.sale,
                }

    return convert.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::convert
 * @see app/Http/Controllers/SaleController.php:178
 * @route '/sale/convert/{sale}'
 */
convert.get = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: convert.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SaleController::convert
 * @see app/Http/Controllers/SaleController.php:178
 * @route '/sale/convert/{sale}'
 */
convert.head = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: convert.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SaleController::index
 * @see app/Http/Controllers/SaleController.php:44
 * @route '/sale'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/sale',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SaleController::index
 * @see app/Http/Controllers/SaleController.php:44
 * @route '/sale'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::index
 * @see app/Http/Controllers/SaleController.php:44
 * @route '/sale'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SaleController::index
 * @see app/Http/Controllers/SaleController.php:44
 * @route '/sale'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SaleController::store
 * @see app/Http/Controllers/SaleController.php:103
 * @route '/sale'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/sale',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\SaleController::store
 * @see app/Http/Controllers/SaleController.php:103
 * @route '/sale'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::store
 * @see app/Http/Controllers/SaleController.php:103
 * @route '/sale'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SaleController::show
 * @see app/Http/Controllers/SaleController.php:195
 * @route '/sale/{sale}'
 */
export const show = (args: { sale: string | number } | [sale: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/sale/{sale}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\SaleController::show
 * @see app/Http/Controllers/SaleController.php:195
 * @route '/sale/{sale}'
 */
show.url = (args: { sale: string | number } | [sale: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { sale: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    sale: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        sale: args.sale,
                }

    return show.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::show
 * @see app/Http/Controllers/SaleController.php:195
 * @route '/sale/{sale}'
 */
show.get = (args: { sale: string | number } | [sale: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\SaleController::show
 * @see app/Http/Controllers/SaleController.php:195
 * @route '/sale/{sale}'
 */
show.head = (args: { sale: string | number } | [sale: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\SaleController::update
 * @see app/Http/Controllers/SaleController.php:146
 * @route '/sale/{sale}'
 */
export const update = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/sale/{sale}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\SaleController::update
 * @see app/Http/Controllers/SaleController.php:146
 * @route '/sale/{sale}'
 */
update.url = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { sale: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'uuid' in args) {
            args = { sale: args.uuid }
        }
    
    if (Array.isArray(args)) {
        args = {
                    sale: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        sale: typeof args.sale === 'object'
                ? args.sale.uuid
                : args.sale,
                }

    return update.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::update
 * @see app/Http/Controllers/SaleController.php:146
 * @route '/sale/{sale}'
 */
update.put = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\SaleController::update
 * @see app/Http/Controllers/SaleController.php:146
 * @route '/sale/{sale}'
 */
update.patch = (args: { sale: string | { uuid: string } } | [sale: string | { uuid: string } ] | string | { uuid: string }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\SaleController::destroy
 * @see app/Http/Controllers/SaleController.php:0
 * @route '/sale/{sale}'
 */
export const destroy = (args: { sale: string | number } | [sale: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/sale/{sale}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\SaleController::destroy
 * @see app/Http/Controllers/SaleController.php:0
 * @route '/sale/{sale}'
 */
destroy.url = (args: { sale: string | number } | [sale: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { sale: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    sale: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        sale: args.sale,
                }

    return destroy.definition.url
            .replace('{sale}', parsedArgs.sale.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::destroy
 * @see app/Http/Controllers/SaleController.php:0
 * @route '/sale/{sale}'
 */
destroy.delete = (args: { sale: string | number } | [sale: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})
const SaleController = { showSold, getSold, refund, close, getClose, counter, destroyItem, destroySale, convert, index, store, show, update, destroy }

export default SaleController