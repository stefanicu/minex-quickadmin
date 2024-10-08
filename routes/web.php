<?php

Route::get('/', 'HomeController@index')->name('home');

Route::get('/setlocale/{locale}', function (string $locale) {
    if (! in_array($locale, ['en', 'ro', 'bg'])) {
        abort(400);
    }
    app()->setLocale('ro');
    return redirect()->back();
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {

    Route::get('/', 'HomeController@index')->name('home.index');

    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Applications
    Route::delete('applications/destroy', 'ApplicationsController@massDestroy')->name('applications.massDestroy');
    Route::post('applications/media', 'ApplicationsController@storeMedia')->name('applications.storeMedia');
    Route::post('applications/ckmedia', 'ApplicationsController@storeCKEditorImages')->name('applications.storeCKEditorImages');
    Route::resource('applications', 'ApplicationsController', ['except' => ['show']]);

    // Brands
    Route::delete('brands/destroy', 'BrandsController@massDestroy')->name('brands.massDestroy');
    Route::post('brands/media', 'BrandsController@storeMedia')->name('brands.storeMedia');
    Route::post('brands/ckmedia', 'BrandsController@storeCKEditorImages')->name('brands.storeCKEditorImages');
    Route::resource('brands', 'BrandsController', ['except' => ['show']]);

    // Industries
    Route::delete('industries/destroy', 'IndustriesController@massDestroy')->name('industries.massDestroy');
    Route::post('industries/media', 'IndustriesController@storeMedia')->name('industries.storeMedia');
    Route::post('industries/ckmedia', 'IndustriesController@storeCKEditorImages')->name('industries.storeCKEditorImages');
    Route::resource('industries', 'IndustriesController', ['except' => ['show']]);

    // Products
    Route::delete('products/destroy', 'ProductsController@massDestroy')->name('products.massDestroy');
    Route::post('products/media', 'ProductsController@storeMedia')->name('products.storeMedia');
    Route::post('products/ckmedia', 'ProductsController@storeCKEditorImages')->name('products.storeCKEditorImages');
    Route::resource('products', 'ProductsController', ['except' => ['show']]);

    // References
    Route::delete('references/destroy', 'ReferencesController@massDestroy')->name('references.massDestroy');
    Route::post('references/media', 'ReferencesController@storeMedia')->name('references.storeMedia');
    Route::post('references/ckmedia', 'ReferencesController@storeCKEditorImages')->name('references.storeCKEditorImages');
    Route::resource('references', 'ReferencesController', ['except' => ['show']]);

    // Blog
    Route::delete('blogs/destroy', 'BlogController@massDestroy')->name('blogs.massDestroy');
    Route::post('blogs/media', 'BlogController@storeMedia')->name('blogs.storeMedia');
    Route::post('blogs/ckmedia', 'BlogController@storeCKEditorImages')->name('blogs.storeCKEditorImages');
    Route::resource('blogs', 'BlogController', ['except' => ['show']]);

    // Contacts
    Route::delete('contacts/destroy', 'ContactsController@massDestroy')->name('contacts.massDestroy');
    Route::resource('contacts', 'ContactsController', ['except' => ['create', 'store', 'edit', 'update']]);

    // Testimonials
    Route::delete('testimonials/destroy', 'TestimonialsController@massDestroy')->name('testimonials.massDestroy');
    Route::post('testimonials/media', 'TestimonialsController@storeMedia')->name('testimonials.storeMedia');
    Route::post('testimonials/ckmedia', 'TestimonialsController@storeCKEditorImages')->name('testimonials.storeCKEditorImages');
    Route::resource('testimonials', 'TestimonialsController', ['except' => ['show']]);

    // Translation Center
    Route::resource('translation-centers', 'TranslationCenterController', ['except' => ['create', 'store', 'show', 'destroy']]);

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::post('categories/media', 'CategoriesController@storeMedia')->name('categories.storeMedia');
    Route::post('categories/ckmedia', 'CategoriesController@storeCKEditorImages')->name('categories.storeCKEditorImages');
    Route::resource('categories', 'CategoriesController', ['except' => ['show']]);

    // Front Page
    Route::post('front_pages/media', 'FrontPageController@storeMedia')->name('front_pages.storeMedia');
    Route::post('front_pages/ckmedia', 'FrontPageController@storeCKEditorImages')->name('front_pages.storeCKEditorImages');
    Route::resource('front_pages', 'FrontPageController', ['except' => ['create', 'store', 'show', 'destroy']]);

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

# all languages routes
Route::get('/', 'HomeController@index')->name('home.index');
Route::post('contact', 'ContactController@index')->name('contact.index');
Route::get('blog', 'BlogController@index')->name('blog.index');

# romanian routes
Route::get('parteneri', 'BrandsController@index')->name('brands.index.ro');
Route::get('referinte', 'ReferencesController@index')->name('references.index.ro');
Route::get('testimoniale', 'TestimonialsController@index')->name('testimonials.index.ro');

# english and bulgarian routes
Route::get('partners', 'BrandsController@index')->name('brands.index');
Route::get('references', 'ReferencesController@index')->name('references.index');
Route::get('testimonials', 'TestimonialsController@index')->name('testimonials.index');



Route::get('gdpr', 'GdprController@index')->name('gdpr.index');
