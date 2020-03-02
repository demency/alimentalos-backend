<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:devices'])->group(function () {
    Route::post('/device/locations', 'Api\Resource\LocationsController')->name('device.locations');
});

/**
 * Pet authenticated routes ...
 */
Route::middleware(['auth:pets'])->group(function () {
    Route::post('/pet/locations', 'Api\Resource\LocationsController')->name('pet.locations');
});

/**
 * Non authenticated routes ...
 */
Route::middleware(['api'])->group(function () {

    Route::get('/token', 'Api\TokenController')
        ->middleware(['throttle:10']);

    Route::post('/register', 'Api\RegisterController')
        ->middleware('throttle:5');

    Route::post('/password-recovery', 'Api\PasswordRecoveryController')
        ->middleware('throttle:5');
});


/**
 * Authenticated and verified routes ...
 */
Route::middleware(['auth:api', 'verified'])->group(function () {

    /**
     * Groups routes ...
     */
    Route::get('/groups', 'Api\Groups\IndexController');
    Route::post('/groups', 'Api\Groups\StoreController');
    Route::get('/groups/{group}', 'Api\Groups\ShowController');
    Route::put('/groups/{group}', 'Api\Groups\UpdateController');


    // Groups Resources

    Route::get('/groups/{group}/users', 'Api\Groups\Users\IndexController');
    Route::get('/groups/{group}/pets', 'Api\Groups\Pets\IndexController');

    /**
     * Locations routes ...
     */
    Route::get('/locations', 'Api\Locations\IndexController');
    Route::get('/locations/{location}', 'Api\Locations\ShowController');

    /**
     * Actions routes ...
     */
    Route::get('/actions', 'Api\Actions\IndexController');
    Route::get('/actions/{action}', 'Api\Actions\ShowController');

    /**
     * Geofences routes ...
     */
    Route::get('/geofences', 'Api\Geofences\IndexController');
    Route::post('/geofences', 'Api\Geofences\StoreController');
    Route::get('/geofences/{geofence}', 'Api\Geofences\ShowController');
    Route::put('/geofences/{geofence}', 'Api\Geofences\UpdateController');


    // Geofences Users

    Route::get('/geofences/{geofence}/users', 'Api\Geofences\Users\IndexController');

    // Geofences Groups
    Route::get('/geofences/{resource}/groups', 'Api\Resource\Groups\IndexController')
        ->name('geofences.groups.index');

    // Geofences Reactions

    Route::get('/geofences/{geofence}/reactions', 'Api\Geofences\Reactions\IndexController');
    Route::post('/geofences/{geofence}/reactions', 'Api\Geofences\Reactions\StoreController');

    // Geofences Accesses
    Route::get('/geofences/{geofence}/devices/accesses', 'Api\Geofences\Devices\AccessesController');
    Route::get('/geofences/{geofence}/users/accesses', 'Api\Geofences\Users\AccessesController');
    Route::get('/geofences/{geofence}/pets/accesses', 'Api\Geofences\Pets\AccessesController');

    /**
     * Pets routes ...
     */
    Route::get('/pets', 'Api\Pets\IndexController');
    Route::post('/pets', 'Api\Pets\StoreController');
    Route::get('/pets/{pet}', 'Api\Pets\ShowController');
    Route::put('/pets/{pet}', 'Api\Pets\UpdateController');

    // Pet Reactions

    Route::get('/pets/{pet}/reactions', 'Api\Pets\Reactions\IndexController');
    Route::post('/pets/{pet}/reactions', 'Api\Pets\Reactions\StoreController');

    /**
     * Devices routes ...
     */
    Route::get('/devices', 'Api\Devices\IndexController');
    Route::post('/devices', 'Api\Devices\StoreController');
    Route::get('/devices/{device}', 'Api\Devices\ShowController');
    Route::put('/devices/{device}', 'Api\Devices\UpdateController');

    // Resource Devices

    foreach(['groups', 'users'] as $resource) {
        Route::get("/{$resource}/{resource}/devices", 'Api\Resource\Devices\IndexController')
            ->name("{$resource}.devices.index");
    }

    // Resource Photos

    foreach (['pets', 'geofences', 'users', 'groups'] as $resource) {
        Route::get("/{$resource}/{resource}/photos", 'Api\Resource\Photos\IndexController')
            ->name("{$resource}.photos.index");
        Route::post("/{$resource}/{resource}/photos", 'Api\Resource\Photos\StoreController')
            ->name("{$resource}.photos.store");
    }

    // Resource Geofences

    foreach (['devices', 'users', 'groups', 'pets'] as $resource) {
        Route::get("/{$resource}/{resource}/geofences", 'Api\Resource\Geofences\IndexController')
            ->name("{$resource}.geofences.index");
        Route::post("/{$resource}/{resource}/geofences/{geofence}/attach", 'Api\Resource\Geofences\AttachController')
            ->name("{$resource}.geofences.attach");
        Route::post("/{$resource}/{resource}/geofences/{geofence}/detach", 'Api\Resource\Geofences\DetachController')
            ->name("{$resource}.geofences.detach");
    }

    // Resource Comments

    foreach(['pets', 'photos', 'comments', 'alerts', 'groups'] as $resource) {
        Route::get("/{$resource}/{resource}/comments", 'Api\Resource\Comments\IndexController')
            ->name("{$resource}.comments.index");
        Route::post("/{$resource}/{resource}/comments", 'Api\Resource\Comments\StoreController')
            ->name("{$resource}.comments.store");
    }

    // Resource Groups

    foreach(['pets', 'devices', 'users'] as $resource) {
        Route::get("/{$resource}/{resource}/groups", 'Api\Resource\Groups\IndexController')
            ->name("{$resource}.groups.index");
        Route::post("/{$resource}/{resource}/groups/{group}/attach", 'Api\Resource\Groups\AttachController')
            ->name("{$resource}.groups.attach");
        Route::post("/{$resource}/{resource}/groups/{group}/detach", 'Api\Resource\Groups\DetachController')
            ->name("{$resource}.groups.detach");
    }

    // Resource Geofences

    foreach(['pets', 'devices', 'users'] as $resource) {
        Route::get("/{$resource}/{resource}/geofences/accesses", 'Api\Resource\Geofences\AccessesController')
            ->name("{$resource}.geofences.accesses");
        Route::get("/{$resource}/{resource}/geofences/{geofence}/accesses", 'Api\Resource\Geofences\Accesses\IndexController')
            ->name("{$resource}.geofences.accesses.index");
    }

    /**
     * Users routes ...
     */

    Route::get('/user', 'Api\UserController');
    Route::post('/user/locations', 'Api\Resource\LocationsController')->name('user.locations');

    Route::get('/users', 'Api\Users\IndexController');
    Route::post('/users', 'Api\Users\StoreController');
    Route::get('/users/{user}', 'Api\Users\ShowController');
    Route::put('/users/{user}', 'Api\Users\UpdateController');

    // User Groups

    Route::post('/users/{user}/groups/{group}/invite', 'Api\Users\Groups\InviteController');
    Route::post('/users/{user}/groups/{group}/accept', 'Api\Users\Groups\AcceptController');
    Route::post('/users/{user}/groups/{group}/reject', 'Api\Users\Groups\RejectController');
    Route::post('/users/{user}/groups/{group}/block', 'Api\Users\Groups\BlockController');

    // User Resources


    Route::get('/users/{user}/pets', 'Api\Users\Pets\IndexController');


    // User Reactions

    Route::get('/users/{user}/reactions', 'Api\Users\Reactions\IndexController');
    Route::post('/users/{user}/reactions', 'Api\Users\Reactions\StoreController');

    /**
     * Reports routes ...
     */
    Route::get('/reports', 'Api\ReportsController');

    /**
     * Photos routes
     */
    Route::get('/photos', 'Api\Photos\IndexController');
    Route::get('/photos/{photo}', 'Api\Photos\ShowController');
    Route::put('/photos/{photo}', 'Api\Photos\UpdateController');

    // Resource delete routes ...
    foreach(['photos', 'users', 'comments', 'actions', 'devices', 'pets', 'geofences', 'groups', 'alerts'] as $resource) {
        Route::delete("/{$resource}/{resource}", 'Api\Resource\DestroyController')
            ->name("{$resource}.destroy");
    }

    // Photo Reactions routes ...

    Route::get('/photos/{photo}/reactions', 'Api\Photos\Reactions\IndexController');
    Route::post('/photos/{photo}/reactions', 'Api\Photos\Reactions\StoreController');

    /**
     * Comments routes
     */
    Route::get('/comments/{comment}', 'Api\Comments\ShowController');
    Route::put('/comments/{comment}', 'Api\Comments\UpdateController');

    // Comment Reactions routes ...

    Route::get('/comments/{comment}/reactions', 'Api\Comments\Reactions\IndexController');
    Route::post('/comments/{comment}/reactions', 'Api\Comments\Reactions\StoreController');

    /**
     * Find route ...
     */
    Route::get('/find', 'Api\FindController');

    /**
     * Near route ...
     */
    Route::get('/near/{resource}', 'Api\Near\Resource\IndexController');

    /**
     * Alerts routes ...
     */
    Route::get('/alerts', 'Api\Alerts\IndexController');
    Route::post('/alerts', 'Api\Alerts\StoreController');
    Route::get('/alerts/{alert}', 'Api\Alerts\ShowController');
    Route::put('/alerts/{alert}', 'Api\Alerts\UpdateController');
});
