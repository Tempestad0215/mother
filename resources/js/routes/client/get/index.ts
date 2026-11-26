import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ClientController::json
* @see app/Http/Controllers/ClientController.php:171
* @route '/client/get-json'
*/
export const json = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: json.url(options),
    method: 'get',
})

json.definition = {
    methods: ["get","head"],
    url: '/client/get-json',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ClientController::json
* @see app/Http/Controllers/ClientController.php:171
* @route '/client/get-json'
*/
json.url = (options?: RouteQueryOptions) => {
    return json.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\ClientController::json
* @see app/Http/Controllers/ClientController.php:171
* @route '/client/get-json'
*/
json.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: json.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ClientController::json
* @see app/Http/Controllers/ClientController.php:171
* @route '/client/get-json'
*/
json.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: json.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\ClientController::json
* @see app/Http/Controllers/ClientController.php:171
* @route '/client/get-json'
*/
const jsonForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: json.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ClientController::json
* @see app/Http/Controllers/ClientController.php:171
* @route '/client/get-json'
*/
jsonForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: json.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\ClientController::json
* @see app/Http/Controllers/ClientController.php:171
* @route '/client/get-json'
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

const get = {
    json: Object.assign(json, json),
}

export default get