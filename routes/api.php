<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Home
    Route::post('homes/media', 'HomeApiController@storeMedia')->name('homes.storeMedia');
    Route::apiResource('homes', 'HomeApiController', ['except' => ['store', 'show', 'destroy']]);

    // Applications
    Route::post('applications/media', 'ApplicationsApiController@storeMedia')->name('applications.storeMedia');
    Route::apiResource('applications', 'ApplicationsApiController', ['except' => ['show', 'destroy']]);

    // Brands
    Route::post('brands/media', 'BrandsApiController@storeMedia')->name('brands.storeMedia');
    Route::apiResource('brands', 'BrandsApiController', ['except' => ['show', 'destroy']]);

    // Industries
    Route::post('industries/media', 'IndustriesApiController@storeMedia')->name('industries.storeMedia');
    Route::apiResource('industries', 'IndustriesApiController', ['except' => ['show', 'destroy']]);

    // Products
    Route::post('products/media', 'ProductsApiController@storeMedia')->name('products.storeMedia');
    Route::apiResource('products', 'ProductsApiController', ['except' => ['show', 'destroy']]);

    // Blog
    Route::post('blogs/media', 'BlogApiController@storeMedia')->name('blogs.storeMedia');
    Route::apiResource('blogs', 'BlogApiController', ['except' => ['show', 'destroy']]);

    // Contacts
    Route::apiResource('contacts', 'ContactsApiController', ['except' => ['store', 'update', 'destroy']]);

    // Testimonials
    Route::post('testimonials/media', 'TestimonialsApiController@storeMedia')->name('testimonials.storeMedia');
    Route::apiResource('testimonials', 'TestimonialsApiController', ['except' => ['show', 'destroy']]);

    // Categories
    Route::post('categories/media', 'CategoriesApiController@storeMedia')->name('categories.storeMedia');
    Route::apiResource('categories', 'CategoriesApiController', ['except' => ['show', 'destroy']]);

    // Home Id
    Route::post('home-ids/media', 'HomeIdApiController@storeMedia')->name('home-ids.storeMedia');
    Route::apiResource('home-ids', 'HomeIdApiController', ['except' => ['store', 'show', 'destroy']]);

    // Application Id
    Route::post('application-ids/media', 'ApplicationIdApiController@storeMedia')->name('application-ids.storeMedia');
    Route::apiResource('application-ids', 'ApplicationIdApiController', ['except' => ['store', 'show', 'update', 'destroy']]);
});
