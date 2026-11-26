import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\SaleController::close
* @see app/Http/Controllers/SaleController.php:301
* @route '/sale/close/get'
*/
export const close = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: close.url(options),
    method: 'post',
})

close.definition = {
    methods: ["post"],
    url: '/sale/close/get',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\SaleController::close
* @see app/Http/Controllers/SaleController.php:301
* @route '/sale/close/get'
*/
close.url = (options?: RouteQueryOptions) => {
    return close.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\SaleController::close
* @see app/Http/Controllers/SaleController.php:301
* @route '/sale/close/get'
*/
close.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: close.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SaleController::close
* @see app/Http/Controllers/SaleController.php:301
* @route '/sale/close/get'
*/
const closeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: close.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\SaleController::close
* @see app/Http/Controllers/SaleController.php:301
* @route '/sale/close/get'
*/
closeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: close.url(options),
    method: 'post',
})

close.form = closeForm

const get = {
    close: Object.assign(close, close),
}

export default get