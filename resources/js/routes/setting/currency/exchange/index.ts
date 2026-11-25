import { queryParams, type RouteQueryOptions, type RouteDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/exchange'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/setting/currency/exchange',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/exchange'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AccontCoController::store
 * @see app/Http/Controllers/AccontCoController.php:0
 * @route '/setting/currency/exchange'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})
const exchange = {
    store: Object.assign(store, store),
}

export default exchange