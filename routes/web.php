<?php

use App\Http\Controllers\Admin;
//use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

Route::redirect('/', '/login');

Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});
/*
|--------------------------------------------------------------------------
| Admin Routes the Web Admin Instances
|--------------------------------------------------------------------------
| Web outside of API routes
|
*/

Auth::routes(['register' => false]);

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [Admin\HomeController::class, 'index'])->name('home');
    // Permissions
    Route::delete('permissions/destroy', [Admin\PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', Admin\PermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [Admin\RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', Admin\RolesController::class);

    // Users
    Route::delete('users/destroy', [Admin\UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', Admin\UsersController::class);

    // Therapy Area
    Route::delete('therapy-areas/destroy', [Admin\TherapyAreaController::class, 'massDestroy'])->name('therapy-areas.massDestroy');
    Route::resource('therapy-areas', Admin\TherapyAreaController::class);

    // Category
    Route::delete('categories/destroy', [Admin\CategoryController::class, 'massDestroy'])->name('categories.massDestroy');
    Route::resource('categories', Admin\CategoryController::class);

    // Theme
    Route::delete('themes/destroy', [Admin\ThemeController::class, 'massDestroy'])->name('themes.massDestroy');
    Route::post('themes/media', [Admin\ThemeController::class, 'storeMedia'])->name('themes.storeMedia');
    Route::post('themes/ckmedia', [Admin\ThemeController::class, 'storeCKEditorImages'])->name('themes.storeCKEditorImages');
    Route::resource('themes', Admin\ThemeController::class);

    // Resources
    Route::delete('resources/destroy', [Admin\ResourcesController::class, 'massDestroy'])->name('resources.massDestroy');
    Route::post('resources/media', [Admin\ResourcesController::class, 'storeMedia'])->name('resources.storeMedia');
    Route::post('resources/ckmedia', [Admin\ResourcesController::class, 'storeCKEditorImages'])->name('resources.storeCKEditorImages');
    Route::resource('resources', Admin\ResourcesController::class);

    // Reference
    Route::delete('references/destroy', [Admin\ReferenceController::class, 'massDestroy'])->name('references.massDestroy');
    Route::post('references/media', [Admin\ReferenceController::class, 'storeMedia'])->name('references.storeMedia');
    Route::post('references/ckmedia', [Admin\ReferenceController::class, 'storeCKEditorImages'])->name('references.storeCKEditorImages');
    Route::resource('references', Admin\ReferenceController::class);

    // Statement
    Route::delete('statements/destroy', [Admin\StatementController::class, 'massDestroy'])->name('statements.massDestroy');
    Route::post('statements/media', [Admin\StatementController::class, 'storeMedia'])->name('statements.storeMedia');
    Route::post('statements/ckmedia', [Admin\StatementController::class, 'storeCKEditorImages'])->name('statements.storeCKEditorImages');
    Route::resource('statements', Admin\StatementController::class);

    // Glossary
    Route::delete('glossaries/destroy', [Admin\GlossaryController::class, 'massDestroy'])->name('glossaries.massDestroy');
    Route::post('glossaries/media', [Admin\GlossaryController::class, 'storeMedia'])->name('glossaries.storeMedia');
    Route::post('glossaries/ckmedia', [Admin\GlossaryController::class, 'storeCKEditorImages'])->name('glossaries.storeCKEditorImages');
    Route::resource('glossaries', Admin\GlossaryController::class);

    // Lexicon
    Route::delete('lexicons/destroy', [Admin\LexiconController::class, 'massDestroy'])->name('lexicons.massDestroy');
    Route::post('lexicons/media', [Admin\LexiconController::class, 'storeMedia'])->name('lexicons.storeMedia');
    Route::post('lexicons/ckmedia', [Admin\LexiconController::class, 'storeCKEditorImages'])->name('lexicons.storeCKEditorImages');
    Route::resource('lexicons', Admin\LexiconController::class);

    // Statement Status
    Route::delete('statement-statuses/destroy', [Admin\StatementStatusController::class, 'massDestroy'])->name('statement-statuses.massDestroy');
    Route::resource('statement-statuses', Admin\StatementStatusController::class);

    // Audience
    Route::delete('audiences/destroy', [Admin\AudienceController::class, 'massDestroy'])->name('audiences.massDestroy');
    Route::resource('audiences', Admin\AudienceController::class);

    // Audit Logs
    Route::resource('audit-logs', Admin\AuditLogsController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
});
Route::prefix('profile')->name('profile.')->middleware('auth')->group(function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', [Auth\ChangePasswordController::class, 'edit'])->name('password.edit');
        Route::post('password', [Auth\ChangePasswordController::class, 'update'])->name('password.update');
        Route::post('profile', [Auth\ChangePasswordController::class, 'updateProfile'])->name('password.updateProfile');
        Route::post('profile/destroy', [Auth\ChangePasswordController::class, 'destroy'])->name('password.destroyProfile');
    }
});
