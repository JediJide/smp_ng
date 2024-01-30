<?php

    use App\Http\Controllers\Api;
    use App\Http\Controllers\Api\V1\Frontend\AuthController;
    use App\Http\Controllers\Api\V1\Frontend\CategoryController;
    use App\Http\Controllers\Api\V1\Frontend\GlossaryController;
    use App\Http\Controllers\Api\V1\Frontend\GlossaryTherapyAreaController;
    use App\Http\Controllers\Api\V1\Frontend\ImportController;
    use App\Http\Controllers\Api\V1\Frontend\InviteController;
    use App\Http\Controllers\Api\V1\Frontend\LexiconController;
    use App\Http\Controllers\Api\V1\Frontend\LexiconTherapyAreaController;
    use App\Http\Controllers\Api\V1\Frontend\MailController;
    use App\Http\Controllers\Api\V1\Frontend\NotificationController;
    use App\Http\Controllers\Api\V1\Frontend\PasswordController;
    use App\Http\Controllers\Api\V1\Frontend\ReferenceController;
    use App\Http\Controllers\Api\V1\Frontend\ResourceController;
    use App\Http\Controllers\Api\V1\Frontend\SearchController;
    use App\Http\Controllers\Api\V1\Frontend\StatementController;
    use App\Http\Controllers\Api\V1\Frontend\StatementReorderController;
    use App\Http\Controllers\Api\V1\Frontend\ThemesController;
    use App\Http\Controllers\Api\V1\Frontend\TherapyAreaController;
    use App\Http\Controllers\Api\V1\Frontend\UsersController;
    use App\Http\Controllers\Api\V1\Frontend\AudienceController;
    use Illuminate\Support\Facades\Route;

    // Auth
    Route::post('login', [AuthController::class, 'login']);

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('register', [AuthController::class, 'register'])->name('registration');

    // Password reset controller
    Route::post('forgot-password', [PasswordController::class, 'forgotPassword']);
    Route::post('reset-password', [PasswordController::class, 'reset']);

    // home/welcome page
    Route::view('welcome', 'welcome', ['ui_url'=> config('app.env_app_ui_url')])->name('welcome');

/*
 |--------------------------------------------------------------------------
 | Invitation Routes
 |--------------------------------------------------------------------------
 | Outside of auth:sanctum protection
 |
 */
    Route::post('invite', [InviteController::class, 'process'])->name('process');

    Route::get('registration', [InviteController::class, 'invite'])
        ->name('auth.registration')->middleware('web', 'signed');

    Route::get('accept/{token}', [InviteController::class, 'accept'])->name('accept');

/*
|--------------------------------------------------------------------------
| Protected Routes, to be accessed by mobiles or Frontend applications
|--------------------------------------------------------------------------
| Typical routers /api/V1/path.
|
*/
Route::prefix('v1')->name('api.')->middleware(['auth:sanctum'])->group(function () {//, 'gzip'
    //Users
    Route::get('/Users', [UsersController::class, 'index']);
    Route::get('/Users/{id}', [UsersController::class, 'show']);
    Route::put('/Users/{id}', [UsersController::class, 'update']); //Linking to UsersApiController in ADMIN
    Route::delete('/Users/{id}', [UsersController::class, 'delete']); //Linking to UsersApiController in ADMIN

    //Audience
    Route::get('/audiences', [AudienceController::class, 'index']);
    Route::get('/theme/therapyarea/{therapy_area_id}/category/{category_id}/view/{view_type}/audience/{audience_id}', [ThemesController::class, 'index']);

    //Therapy Areas
    Route::get('/therapyareas', [TherapyAreaController::class, 'index']);
    Route::post('/therapyareas', [TherapyAreaController::class, 'store']);
    Route::get('/therapyareas/{id}', [TherapyAreaController::class, 'show']);
    Route::put('/therapyareas/{id}', [TherapyAreaController::class, 'update']);
    Route::delete('/therapyareas/{id}', [TherapyAreaController::class, 'destroy']);

    //Category
    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

    //Themes
    Route::post('/theme', [ThemesController::class, 'store']);
    Route::get('/theme/{id}', [ThemesController::class, 'show']);
    Route::put('/theme/{id}', [ThemesController::class, 'update']);
    Route::delete('/theme/{id}', [ThemesController::class, 'destroy']);
    Route::get ('theme-resource/{id}', [Api\V1\Frontend\GenericController::class, 'themesResources']);

    //Statement
    Route::get('/statement/category/{category_id}/', [StatementController::class, 'index']);
    Route::post('/statement', [StatementController::class, 'store']);
    Route::get('/statement/{id}', [StatementController::class, 'show']);
    Route::put('/statement/{id}', [StatementController::class, 'update']);
    Route::delete('/statement/{id}', [StatementController::class, 'destroy']);

    //Reorder
    Route::put('/reorder/statement', [StatementReorderController::class, 'StatementsUpdate']);
    Route::put('/reorder/themes', [StatementReorderController::class, 'ThemesUpdate']);
    Route::put('/reorder/lexicons',[StatementReorderController::class, 'LexiconsUpdate']);
    Route::put('/reorder/glossaries',[StatementReorderController::class, 'GlossariesUpdate']);

    //Resource
    Route::get('/resource', [ResourceController::class, 'index']);
    Route::post('/resource', [ResourceController::class, 'store']);
    Route::get('/resource/{id}', [ResourceController::class, 'show']);
    Route::post('/resource/{id}', [ResourceController::class, 'update']);
    Route::delete('/resource/{id}', [ResourceController::class, 'destroy']);

    //References
    Route::get('/reference', [ReferenceController::class, 'index']);
    Route::get('/reference/therapy_area/{therapy_area_id}', [ReferenceController::class, 'index']);
    Route::post('/reference', [ReferenceController::class, 'store']);
    Route::get('/reference/{id}', [ReferenceController::class, 'show']);
    Route::post('/reference/{id}', [ReferenceController::class, 'update']);
    Route::delete('/reference/{id}', [ReferenceController::class, 'destroy']);

    // Lexicons
    Route::get('/lexicon', [LexiconController::class, 'index']);
    Route::post('/lexicon', [LexiconController::class, 'store']);
    Route::get('/lexicon/{id}', [LexiconController::class, 'show']);
    Route::put('/lexicon/{id}', [LexiconController::class, 'update']);
    Route::delete('/lexicon/{id}', [LexiconController::class, 'destroy']);
    Route::get('/lexicon-therapy-area/{id}', [LexiconTherapyAreaController::class, 'getLexiconByTherapy']);

    // Glossary
    Route::get('/glossary', [GlossaryController::class, 'index']);
    Route::post('/glossary', [GlossaryController::class, 'store']);
    Route::get('/glossary/{id}', [GlossaryController::class, 'show']);
    Route::put('/glossary/{id}', [GlossaryController::class, 'update']);
    Route::delete('/glossary/{id}', [GlossaryController::class, 'destroy']);
    Route::get('/glossary-therapy-area/{id}', [GlossaryTherapyAreaController::class, 'getGlossaryByTherapyArea']);

    // Search
    Route::get('/search', [SearchController::class, 'search']);

    // Notification
    Route::get('/notification/my/{user_id}', [NotificationController::class, 'getNotification']);
    Route::delete('/notification/{notification_id}', [NotificationController::class, 'destroy']);

    // Temporary Import modules delete later
    Route::get('/import/lexicon', [ImportController::class, 'importLexicon'])
        ->name('import-lexicon');
    Route::get('/import/glossary', [ImportController::class, 'importGlossary'])
        ->name('import-glossary');

    Route::get('/import/aws-files',[ImportController::class, 'getAwsFiles']);

    Route::get('/import/themes', [ImportController::class, 'importThemes'])
        ->name('import-themes');

    # Route to run, when removing unlinked Resources.
    Route::get('/remove/statement-resources', [ImportController::class, 'removeUnlinkedResources']);

    # Send mass email
    Route::get('send-mail', [MailController::class, 'sendMail'])->name('sendMail');

    //clear cache
    Route::get('/import/clear-cache', [ImportController::class, 'clearStatementCache'])
    ->name('clear-statement-cache');
});

/*
|--------------------------------------------------------------------------
| Admin Routes the Web Admin Instances
|--------------------------------------------------------------------------
| Web outside of API routes
|
*/
// Admin Routes
Route::prefix('v1')->name('api.')->middleware('auth:sanctum')->group(function () {
    // Users
    Route::apiResource('users', Api\V1\Admin\UsersApiController::class);

    //Audience
    Route::apiResource('audiences', Api\V1\Admin\AudienceApiController::class);

    // Therapy Area
    Route::apiResource('therapy-areas', Api\V1\Admin\TherapyAreaApiController::class);

    // Category
    Route::apiResource('categories', Api\V1\Admin\CategoryApiController::class);

    // Theme
    Route::post('themes/media', [Api\V1\Admin\ThemeApiController::class, 'storeMedia'])->name('themes.storeMedia');
    Route::apiResource('themes', Api\V1\Admin\ThemeApiController::class);

    // Resources
    Route::post('resources/media', [Api\V1\Admin\ResourcesApiController::class, 'storeMedia'])->name('resources.storeMedia');
    Route::apiResource('resources', Api\V1\Admin\ResourcesApiController::class);

    // Reference
    Route::post('references/media', [Api\V1\Admin\ReferenceApiController::class, 'storeMedia'])->name('references.storeMedia');
    Route::apiResource('references', Api\V1\Admin\ReferenceApiController::class);

    // Statement
    Route::post('statements/media', [Api\V1\Admin\StatementApiController::class, 'storeMedia'])->name('statements.storeMedia');
    Route::apiResource('statements', Api\V1\Admin\StatementApiController::class);

    // Lexicon
    Route::post('lexicons/media', [Api\V1\Admin\LexiconApiController::class, 'storeMedia'])->name('lexicons.storeMedia');
    Route::apiResource('lexicons', Api\V1\Admin\LexiconApiController::class);

    // Statement Status
    Route::apiResource('statement-statuses', Api\V1\Admin\StatementStatusApiController::class);
});
