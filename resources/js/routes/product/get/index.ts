import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ProductController::json
* @see app/Http/Controllers/ProductController.php:383
* @route '/product/get/json'
*/
export const json = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: json.url(options),
    method: 'get',
})

json.definition = {
    methods: ["get","head"],
    url: '/product/get/json',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProductController::json
* @see app/Http/Controllers/ProductController.php:383
* @route '/product/get/json'
*/
json.url = (options?: RouteQueryOptions) => {
    return json.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProductController::json
* @see app/Http/Controllers/ProductController.php:383
* @route '/product/get/json'
*/
json.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: json.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProductController::json
* @see app/Http/Controllers/ProductController.php:383
* @route '/product/get/json'
*/
json.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: json.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProductController::json
* @see app/Http/Controllers/ProductController.php:383
* @route '/product/get/json'
*/
const jsonForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: json.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProductController::json
* @see app/Http/Controllers/ProductController.php:383
* @route '/product/get/json'
*/
jsonForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: json.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProductController::json
* @see app/Http/Controllers/ProductController.php:383
* @route '/product/get/json'
*/
jsonForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: json.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

json.form = jsonForm

/**
* @see \App\Http\Controllers\ProductController::code
* @see app/Http/Controllers/ProductController.php:261
* @route '/product/get/code'
*/
export const code = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: code.url(options),
    method: 'get',
})

code.definition = {
    methods: ["get","head"],
    url: '/product/get/code',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ProductController::code
* @see app/Http/Controllers/ProductController.php:261
* @route '/product/get/code'
*/
code.url = (options?: RouteQueryOptions) => {
    return code.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ProductController::code
* @see app/Http/Controllers/ProductController.php:261
* @route '/product/get/code'
*/
code.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: code.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProductController::code
* @see app/Http/Controllers/ProductController.php:261
* @route '/product/get/code'
*/
code.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: code.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ProductController::code
* @see app/Http/Controllers/ProductController.php:261
* @route '/product/get/code'
*/
const codeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: code.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProductController::code
* @see app/Http/Controllers/ProductController.php:261
* @route '/product/get/code'
*/
codeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: code.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ProductController::code
* @see app/Http/Controllers/ProductController.php:261
* @route '/product/get/code'
*/
codeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: code.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

code.form = codeForm

const get = {
    json: Object.assign(json, json),
    code: Object.assign(code, code),
}

export default get