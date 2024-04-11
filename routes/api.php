<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Applications
    Route::post('applications/media', 'ApplicationsApiController@storeMedia')->name('applications.storeMedia');
    Route::apiResource('applications', 'ApplicationsApiController', ['except' => ['show']]);

    // Brands
    Route::post('brands/media', 'BrandsApiController@storeMedia')->name('brands.storeMedia');
    Route::apiResource('brands', 'BrandsApiController', ['except' => ['show']]);

    // Industries
    Route::post('industries/media', 'IndustriesApiController@storeMedia')->name('industries.storeMedia');
    Route::apiResource('industries', 'IndustriesApiController', ['except' => ['show']]);

    // Products
    Route::post('products/media', 'ProductsApiController@storeMedia')->name('products.storeMedia');
    Route::apiResource('products', 'ProductsApiController', ['except' => ['show']]);

    // References
    Route::post('references/media', 'ReferencesApiController@storeMedia')->name('references.storeMedia');
    Route::apiResource('references', 'ReferencesApiController', ['except' => ['show']]);

    // Blog
    Route::post('blogs/media', 'BlogApiController@storeMedia')->name('blogs.storeMedia');
    Route::apiResource('blogs', 'BlogApiController', ['except' => ['show']]);

    // Contacts
    Route::apiResource('contacts', 'ContactsApiController', ['except' => ['store', 'update']]);

    // Testimonials
    Route::post('testimonials/media', 'TestimonialsApiController@storeMedia')->name('testimonials.storeMedia');
    Route::apiResource('testimonials', 'TestimonialsApiController', ['except' => ['show']]);

    // Categories
    Route::post('categories/media', 'CategoriesApiController@storeMedia')->name('categories.storeMedia');
    Route::apiResource('categories', 'CategoriesApiController', ['except' => ['show']]);

    // Front Page
    Route::post('front-pages/media', 'FrontPageApiController@storeMedia')->name('front-pages.storeMedia');
    Route::apiResource('front-pages', 'FrontPageApiController', ['except' => ['store', 'show', 'destroy']]);

    // Fron Test
    Route::apiResource('fron-tests', 'FronTestApiController');
});
