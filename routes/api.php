<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Applications
    Route::post('applications/media', 'ApplicationsApiController@storeMedia')->name('applications.storeMedia');
    Route::apiResource('applications', 'ApplicationsApiController', ['except' => ['show', 'destroy']]);

    // Contacts
    Route::apiResource('contacts', 'ContactsApiController', ['except' => ['store', 'update']]);

    // Categories
    Route::post('categories/media', 'CategoriesApiController@storeMedia')->name('categories.storeMedia');
    Route::apiResource('categories', 'CategoriesApiController', ['except' => ['show']]);
});
