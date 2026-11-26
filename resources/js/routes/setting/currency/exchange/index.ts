import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
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

/**
* @see \App\Http\Controllers\AccontCoController::store
* @see app/Http/Controllers/AccontCoController.php:0
* @route '/setting/currency/exchange'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\AccontCoController::store
* @see app/Http/Controllers/AccontCoController.php:0
* @route '/setting/currency/exchange'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const exchange = {
    store: Object.assign(store, store),
}

export default exchange