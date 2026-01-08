<?php

use App\Http\Controllers\Admin\ApplicationsController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\BrandsController as AdminBrandsController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\ContactsController;
use App\Http\Controllers\Admin\DropDownController;
use App\Http\Controllers\Admin\FiltersController;
use App\Http\Controllers\Admin\FrontPageController;
use App\Http\Controllers\Admin\GlobalSearchController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\IndustriesController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\ReferencesController as AdminReferencesController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\TestimonialsController as AdminTestimonialsController;
use App\Http\Controllers\Admin\TranslationAllLanguagesController;
use App\Http\Controllers\Admin\TranslationDBController;
use App\Http\Controllers\Admin\TranslationGranularController;
use App\Http\Controllers\Admin\TranslationStringsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\AppPageDynamicController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BlogsController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\ReferencesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TestimonialsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::group([
    'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'setBackendLocale']
], function () {
    Route::get('/', [AdminHomeController::class, 'index'])->name('home');
    
    // Permissions
    Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', PermissionsController::class);
    
    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);
    
    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);
    
    // Applications
    Route::delete('applications/destroy', [ApplicationsController::class, 'massDestroy'])->name('applications.massDestroy');
    Route::post('applications/media', [ApplicationsController::class, 'storeMedia'])->name('applications.storeMedia');
    Route::post('applications/ckmedia', [ApplicationsController::class, 'storeCKEditorImages'])->name('applications.storeCKEditorImages');
    Route::resource('applications', ApplicationsController::class, ['except' => ['show']]);
    
    // Brands
    Route::delete('brands/destroy', [AdminBrandsController::class, 'massDestroy'])->name('brands.massDestroy');
    Route::post('brands/media', [AdminBrandsController::class, 'storeMedia'])->name('brands.storeMedia');
    Route::post('brands/ckmedia', [AdminBrandsController::class, 'storeCKEditorImages'])->name('brands.storeCKEditorImages');
    Route::resource('brands', AdminBrandsController::class, ['except' => ['show']]);
    
    // Industries
    Route::delete('industries/destroy', [IndustriesController::class, 'massDestroy'])->name('industries.massDestroy');
    Route::post('industries/media', [IndustriesController::class, 'storeMedia'])->name('industries.storeMedia');
    Route::post('industries/ckmedia', [IndustriesController::class, 'storeCKEditorImages'])->name('industries.storeCKEditorImages');
    Route::resource('industries', IndustriesController::class, ['except' => ['show']]);
    
    // Products
    Route::delete('products/destroy', [ProductsController::class, 'massDestroy'])->name('products.massDestroy');
    Route::post('products/media', [ProductsController::class, 'storeMedia'])->name('products.storeMedia');
    Route::post('products/ckmedia', [ProductsController::class, 'storeCKEditorImages'])->name('products.storeCKEditorImages');
    Route::resource('products', ProductsController::class, ['except' => ['show']]);
    
    // References
    Route::delete('references/destroy', [AdminReferencesController::class, 'massDestroy'])->name('references.massDestroy');
    Route::post('references/media', [AdminReferencesController::class, 'storeMedia'])->name('references.storeMedia');
    Route::post('references/ckmedia', [AdminReferencesController::class, 'storeCKEditorImages'])->name('references.storeCKEditorImages');
    Route::resource('references', AdminReferencesController::class, ['except' => ['show']]);
    
    // Blog
    Route::delete('blogs/destroy', [AdminBlogController::class, 'massDestroy'])->name('blogs.massDestroy');
    Route::post('blogs/media', [AdminBlogController::class, 'storeMedia'])->name('blogs.storeMedia');
    Route::post('blogs/ckmedia', [AdminBlogController::class, 'storeCKEditorImages'])->name('blogs.storeCKEditorImages');
    Route::resource('blogs', AdminBlogController::class, ['except' => ['show']]);
    
    // Contacts
    Route::delete('contacts/destroy', [ContactsController::class, 'massDestroy'])->name('contacts.massDestroy');
    Route::resource('contacts', ContactsController::class, ['except' => ['create', 'store', 'edit', 'update']]);
    
    // Testimonials
    Route::delete('testimonials/destroy', [AdminTestimonialsController::class, 'massDestroy'])->name('testimonials.massDestroy');
    Route::post('testimonials/media', [AdminTestimonialsController::class, 'storeMedia'])->name('testimonials.storeMedia');
    Route::post('testimonials/ckmedia', [AdminTestimonialsController::class, 'storeCKEditorImages'])->name('testimonials.storeCKEditorImages');
    Route::resource('testimonials', AdminTestimonialsController::class, ['except' => ['show']]);
    
    // ==== TRANSLATION STRINGS ===============================================================================
    Route::get('translation/strings', [TranslationStringsController::class, 'strings'])->name('translation.strings');
    Route::post('translation/{lang}/update/', [TranslationStringsController::class, 'updateStrings'])->name('translation.strings.update');
    Route::post('translations/translate/{locale}', [TranslationStringsController::class, 'translate'])->name('translations.translate');
    
    // ==== TRANSLATION DB MODELS =============================================================================
    Route::get('translation/dbmodels', [TranslationDBController::class, 'index'])->name('translation.dbmodels');
    Route::post('translation/dbtranslate/{locale}', [TranslationDBController::class, 'dbtranslate'])->name('translations.dbtranslate');
    //    Route::post('translation/dbreset/{locale}', [TranslationDBController::class, 'dbreset'])->name('translations.dbreset');
    // Granular Translation
    Route::post('translation/granular', [TranslationGranularController::class, 'index'])->name('translation.granular');
    // All Languages Translation by queue
    Route::post('translation/languages', [TranslationAllLanguagesController::class, 'index'])->name('translation.languages');
    
    // Categories
    Route::delete('categories/destroy', [CategoriesController::class, 'massDestroy'])->name('categories.massDestroy');
    Route::post('categories/media', [CategoriesController::class, 'storeMedia'])->name('categories.storeMedia');
    Route::post('categories/ckmedia', [CategoriesController::class, 'storeCKEditorImages'])->name('categories.storeCKEditorImages');
    Route::resource('categories', CategoriesController::class, ['except' => ['show']]);
    
    // Filters
    Route::delete('filters/destroy', [FiltersController::class, 'massDestroy'])->name('filters.massDestroy');
    Route::post('filters/media', [FiltersController::class, 'storeMedia'])->name('filters.storeMedia');
    Route::post('filters/ckmedia', [FiltersController::class, 'storeCKEditorImages'])->name('filters.storeCKEditorImages');
    Route::resource('filters', FiltersController::class, ['except' => ['show']]);
    
    // Front Page
    Route::post('front_pages/media', [FrontPageController::class, 'storeMedia'])->name('front_pages.storeMedia');
    Route::post('front_pages/ckmedia', [FrontPageController::class, 'storeCKEditorImages'])->name('front_pages.storeCKEditorImages');
    Route::resource('front_pages', FrontPageController::class, ['except' => ['create', 'store', 'show', 'destroy']]);
    
    // Page
    Route::delete('pages/destroy', [PageController::class, 'massDestroy'])->name('pages.massDestroy');
    Route::post('pages/media', [PageController::class, 'storeMedia'])->name('pages.storeMedia');
    Route::post('pages/ckmedia', [PageController::class, 'storeCKEditorImages'])->name('pages.storeCKEditorImages');
    Route::resource('pages', PageController::class, ['except' => ['show']]);
    
    Route::get('global-search', [GlobalSearchController::class, 'search'])->name('globalSearch');
    
    Route::get('/get-categories', [DropDownController::class, 'getCategories'])->name('get.categories');
    Route::get('/get-filters', [DropDownController::class, 'getFilters'])->name('get.filters');
});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', [ChangePasswordController::class, 'edit'])->name('password.edit');
        Route::post('password', [ChangePasswordController::class, 'update'])->name('password.update');
        Route::post('profile', [ChangePasswordController::class, 'updateProfile'])->name('password.updateProfile');
        Route::post('profile/destroy', [ChangePasswordController::class, 'destroy'])->name('password.destroyProfile');
    }
});

Route::get('/', function () {
    return view('layouts.gateway');
});

// Define translatable routes separately for each language
foreach (config('translatable.locales') as $locale) {
    Route::group(['prefix' => $locale, 'middleware' => 'setFrontendLocale'], function () use ($locale) {
        Route::get('/', [HomeController::class, 'index'])->name("home.$locale");
        
        // Route::get('gdpr', 'GdprController@index')->name("gdpr.$locale");
        Route::post('contact', [ContactController::class, 'index'])->name("contact.post.$locale");
        Route::get('contact', [ContactController::class, 'index'])->name("contact.get.$locale");
        Route::get('search', [SearchController::class, 'index'])->name("search.$locale");
        
        // Translatable routes with language-specific names and slugs
        
        // Blog
        Route::get(trans('slugs.blog', [], $locale), [BlogsController::class, 'index'])->name("blogs.$locale");
        Route::get(trans('slugs.blog', [], $locale).'/{slug?}', [BlogController::class, 'index'])->name("blog.$locale");
        
        // Brands --- Partners
        Route::get(trans('slugs.brands', [], $locale), [BrandsController::class, 'index'])->name("brands.$locale");
        Route::get(trans('slugs.brands', [], $locale).'/{slug}', [BrandController::class, 'index'])->name("brand.$locale");
        
        // References
        Route::get(trans('slugs.references', [], $locale), [ReferencesController::class, 'index'])->name("references.$locale");
        Route::get(trans('slugs.references', [], $locale).'/{slug}', [ReferenceController::class, 'index'])->name("reference.$locale");
        
        // Testimonials
        Route::get(trans('slugs.testimonials', [], $locale), [TestimonialsController::class, 'index'])->name("testimonials.$locale");
        
        // Dynamic Applications Pages Brands
        Route::get('{slug}', [AppPageDynamicController::class, 'index'])->name("pages.$locale");
        
        // Application (categories) ==> Category (products)
        // Route::get('{app_slug?}', 'ApplicationController@index')->name("application.$locale");
        Route::get('{app_slug?}'.'/{cat_slug?}', [CategoryController::class, 'index'])->name("category.$locale");
        
        // Product
        Route::get('{app_slug?}'.'/{cat_slug?}'.'/{prod_slug?}', [ProductController::class, 'index'])->name("product.$locale");
    });
}
