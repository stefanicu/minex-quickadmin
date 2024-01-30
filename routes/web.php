<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Home
    Route::post('homes/media', 'HomeController@storeMedia')->name('homes.storeMedia');
    Route::post('homes/ckmedia', 'HomeController@storeCKEditorImages')->name('homes.storeCKEditorImages');
    Route::resource('homes', 'HomeController', ['except' => ['create', 'store', 'show', 'destroy']]);

    // Applications
    Route::post('applications/media', 'ApplicationsController@storeMedia')->name('applications.storeMedia');
    Route::post('applications/ckmedia', 'ApplicationsController@storeCKEditorImages')->name('applications.storeCKEditorImages');
    Route::resource('applications', 'ApplicationsController', ['except' => ['show', 'destroy']]);

    // Brands
    Route::post('brands/media', 'BrandsController@storeMedia')->name('brands.storeMedia');
    Route::post('brands/ckmedia', 'BrandsController@storeCKEditorImages')->name('brands.storeCKEditorImages');
    Route::resource('brands', 'BrandsController', ['except' => ['show', 'destroy']]);

    // Industries
    Route::post('industries/media', 'IndustriesController@storeMedia')->name('industries.storeMedia');
    Route::post('industries/ckmedia', 'IndustriesController@storeCKEditorImages')->name('industries.storeCKEditorImages');
    Route::resource('industries', 'IndustriesController', ['except' => ['show', 'destroy']]);

    // Products
    Route::post('products/media', 'ProductsController@storeMedia')->name('products.storeMedia');
    Route::post('products/ckmedia', 'ProductsController@storeCKEditorImages')->name('products.storeCKEditorImages');
    Route::resource('products', 'ProductsController', ['except' => ['show', 'destroy']]);

    // References
    Route::post('references/media', 'ReferencesController@storeMedia')->name('references.storeMedia');
    Route::post('references/ckmedia', 'ReferencesController@storeCKEditorImages')->name('references.storeCKEditorImages');
    Route::resource('references', 'ReferencesController', ['except' => ['show', 'destroy']]);

    // Blog
    Route::post('blogs/media', 'BlogController@storeMedia')->name('blogs.storeMedia');
    Route::post('blogs/ckmedia', 'BlogController@storeCKEditorImages')->name('blogs.storeCKEditorImages');
    Route::resource('blogs', 'BlogController', ['except' => ['show', 'destroy']]);

    // Contacts
    Route::resource('contacts', 'ContactsController', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);

    // Testimonials
    Route::post('testimonials/media', 'TestimonialsController@storeMedia')->name('testimonials.storeMedia');
    Route::post('testimonials/ckmedia', 'TestimonialsController@storeCKEditorImages')->name('testimonials.storeCKEditorImages');
    Route::resource('testimonials', 'TestimonialsController', ['except' => ['show', 'destroy']]);

    // Translation Center
    Route::delete('translation-centers/destroy', 'TranslationCenterController@massDestroy')->name('translation-centers.massDestroy');
    Route::resource('translation-centers', 'TranslationCenterController');

    // Categories
    Route::post('categories/media', 'CategoriesController@storeMedia')->name('categories.storeMedia');
    Route::post('categories/ckmedia', 'CategoriesController@storeCKEditorImages')->name('categories.storeCKEditorImages');
    Route::resource('categories', 'CategoriesController', ['except' => ['show', 'destroy']]);

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
