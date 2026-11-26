import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController::show
* @see vendor/laravel/jetstream/src/Http/Controllers/Inertia/UserProfileController.php:23
* @route '/user/profile'
*/
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/user/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController::show
* @see vendor/laravel/jetstream/src/Http/Controllers/Inertia/UserProfileController.php:23
* @route '/user/profile'
*/
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController::show
* @see vendor/laravel/jetstream/src/Http/Controllers/Inertia/UserProfileController.php:23
* @route '/user/profile'
*/
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

/**
* @see \Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController::show
* @see vendor/laravel/jetstream/src/Http/Controllers/Inertia/UserProfileController.php:23
* @route '/user/profile'
*/
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

/**
* @see \Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController::show
* @see vendor/laravel/jetstream/src/Http/Controllers/Inertia/UserProfileController.php:23
* @route '/user/profile'
*/
const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController::show
* @see vendor/laravel/jetstream/src/Http/Controllers/Inertia/UserProfileController.php:23
* @route '/user/profile'
*/
showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(options),
    method: 'get',
})

/**
* @see \Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController::show
* @see vendor/laravel/jetstream/src/Http/Controllers/Inertia/UserProfileController.php:23
* @route '/user/profile'
*/
showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

const UserProfileController = { show }

export default UserProfileController