<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Admin\SensorController as AdminSensorController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\Admin\SuggestionController as AdminSuggestionController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\ProfileController as SuperAdminProfileController;
use App\Http\Controllers\SuperAdmin\SuggestionController as SuperAdminSuggestionController;
use App\Http\Controllers\SuperAdmin\ContentController as SuperAdminContentController;
use App\Http\Controllers\EmailVerificationController;

// ─── Public Routes ────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sensors', [SensorController::class, 'index'])->name('sensors.index');
Route::get('/sensors/{slug}', [SensorController::class, 'show'])->name('sensors.show');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/shop/{id}', [ProductController::class, 'show'])->name('shop.show');

// ─── Community Suggestions (Public - requires auth to comment) ────────────────
Route::get('/community', [SuggestionController::class, 'community'])->name('suggestions.community');

// ─── Authentication Routes ────────────────────────────────────────────────────
// NOTE: No route-level throttle here — all rate limiting is handled
//       manually inside LoginController using RateLimiter::hit/tooManyAttempts
//       so only FAILED attempts count, not every request.
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/sys/secure-entry', [LoginController::class, 'showSuperAdminLoginForm'])->name('super-admin.login');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');

    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/sys/secure-entry', [LoginController::class, 'superAdminLogin'])->name('super-admin.login.submit');
    Route::post('/login/verify', [LoginController::class, 'verifyCode'])->name('login.verify');
    Route::post('/login/resend', [LoginController::class, 'resendCode'])->name('login.resend');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Notification Routes
Route::middleware('auth')->group(function () {
    Route::post('/notifications/{notification}/read', function (\App\Models\Notification $notification) {
        $notification->update(['is_read' => true]);
        return response()->json(['success' => true]);
    })->name('notifications.read');

    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->latest()->paginate(5);
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');
});

// ─── AI Chat Route ────────────────────────────────────────────────────────────
Route::post('/api/chat', [ChatController::class, 'send'])
    ->middleware(['auth', 'throttle:5,1'])
    ->name('chat.send');

// ─── Student Dashboard Routes ────────────────────────────────────────────────────
Route::middleware(['auth.redirect'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Saved Projects
    Route::get('/saved-projects', [ProjectController::class, 'saved'])->name('saved');
    Route::post('/projects/{project}/save', [ProjectController::class, 'toggleSave'])->name('projects.save');

    // My Suggestions
    Route::get('/suggestions', [SuggestionController::class, 'mySuggestions'])->name('suggestions');
    Route::post('/suggestions', [SuggestionController::class, 'store'])
        ->middleware('throttle:10,5')
        ->name('suggestions.store');
    Route::get('/suggestions/{suggestion}/edit', [SuggestionController::class, 'edit'])->name('suggestions.edit');
    Route::put('/suggestions/{suggestion}', [SuggestionController::class, 'update'])->name('suggestions.update');
    Route::delete('/suggestions/{suggestion}', [SuggestionController::class, 'destroy'])->name('suggestions.destroy');

    // View any suggestion (community-linked)
    Route::get('/suggestions/{suggestion}/view', [SuggestionController::class, 'show'])->name('suggestions.show');

    // Comments (any authenticated user can comment)
    Route::post('/suggestions/{suggestion}/comment', [SuggestionController::class, 'storeComment'])
        ->middleware('throttle:10,5')
        ->name('suggestions.comment.store');
    Route::put('/suggestions/{suggestion}/comment/{comment}', [SuggestionController::class, 'updateComment'])->name('suggestions.comment.update');

     // Classes
    Route::get('/classes', [ClassroomController::class, 'studentClasses'])->name('classes.index');
    Route::post('/classes/join', [ClassroomController::class, 'join'])
        ->middleware('throttle:5,1')
        ->name('classes.join');
    Route::get('/classes/{class}', [ClassroomController::class, 'studentShow'])->name('classes.show');
    Route::get('/classes/{class}/modules/{module}', [ModuleController::class, 'show'])->name('classes.modules.show');
    Route::get('/classes/{class}/assessments/{assessment}', [AssessmentController::class, 'show'])->name('classes.assessments.show');
    Route::post('/classes/{class}/assessments/{assessment}/submit', [AssessmentController::class, 'submit'])->name('classes.assessments.submit');
    Route::get('/classes/{class}/announcements', [AnnouncementController::class, 'studentIndex'])->name('classes.announcements.index');
    Route::get('/classes/{class}/modules', [ModuleController::class, 'studentIndex'])->name('classes.modules.index');
    Route::get('/classes/{class}/assessments', [AssessmentController::class, 'studentIndex'])->name('classes.assessments.index');
    Route::get('/classes/{class}/quizzes', [QuizController::class, 'studentIndex'])->name('classes.quizzes.index');
    Route::get('/classes/{class}/quizzes/{quiz}', [QuizController::class, 'show'])->name('classes.quizzes.show');
    Route::post('/classes/{class}/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('classes.quizzes.submit');
});

// ─── Email Verification Routes ────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'show'])->name('verification.notice');
    Route::post('/email/verify', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::post('/email/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');
});

// ─── Instructor Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Classes
    Route::get('/classes', [ClassroomController::class, 'index'])->name('classes.index');
    Route::get('/classes/create', [ClassroomController::class, 'create'])->name('classes.create');
    Route::post('/classes', [ClassroomController::class, 'store'])->name('classes.store');
    Route::get('/classes/{class}', [ClassroomController::class, 'show'])->name('classes.show');
    Route::get('/classes/{class}/edit', [ClassroomController::class, 'edit'])->name('classes.edit');
    Route::put('/classes/{class}', [ClassroomController::class, 'update'])->name('classes.update');
    Route::delete('/classes/{class}', [ClassroomController::class, 'destroy'])->name('classes.destroy');
   Route::post('/classes/{class}/approve/{user}', [ClassroomController::class, 'approve'])->name('classes.approve');
    Route::post('/classes/{class}/approve-all', [ClassroomController::class, 'approveAll'])->name('classes.approve-all');
    Route::delete('/classes/{class}/reject/{user}', [ClassroomController::class, 'reject'])->name('classes.reject');

    // Announcements
    Route::get('/classes/{class}/announcements', [AnnouncementController::class, 'index'])->name('classes.announcements.index');
    Route::get('/classes/{class}/announcements/create', [AnnouncementController::class, 'create'])->name('classes.announcements.create');
    Route::post('/classes/{class}/announcements', [AnnouncementController::class, 'store'])->name('classes.announcements.store');
    Route::get('/classes/{class}/announcements/import', [AnnouncementController::class, 'import'])->name('classes.announcements.import');
    Route::post('/classes/{class}/announcements/import', [AnnouncementController::class, 'copyAnnouncements'])->name('classes.announcements.copy');
    Route::get('/classes/{class}/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('classes.announcements.edit');
    Route::put('/classes/{class}/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('classes.announcements.update');
    Route::delete('/classes/{class}/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('classes.announcements.destroy');

    // Modules
    Route::get('/classes/{class}/modules', [ModuleController::class, 'index'])->name('classes.modules.index');
    Route::get('/classes/{class}/modules/create', [ModuleController::class, 'create'])->name('classes.modules.create');
    Route::post('/classes/{class}/modules', [ModuleController::class, 'store'])->name('classes.modules.store');
    Route::delete('/classes/{class}/modules/{module}', [ModuleController::class, 'destroy'])->name('classes.modules.destroy');
    Route::get('/classes/{class}/modules/import', [ModuleController::class, 'import'])->name('classes.modules.import');
    Route::post('/classes/{class}/modules/import', [ModuleController::class, 'copyModules'])->name('classes.modules.copy');
    Route::get('/classes/{class}/modules/{module}/edit', [ModuleController::class, 'edit'])->name('classes.modules.edit');
    Route::put('/classes/{class}/modules/{module}', [ModuleController::class, 'update'])->name('classes.modules.update');

    // Assessments
    Route::get('/classes/{class}/assessments', [AssessmentController::class, 'index'])->name('classes.assessments.index');
    Route::get('/classes/{class}/assessments/create', [AssessmentController::class, 'create'])->name('classes.assessments.create');
    Route::post('/classes/{class}/assessments', [AssessmentController::class, 'store'])->name('classes.assessments.store');
    Route::get('/classes/{class}/assessments/import', [AssessmentController::class, 'import'])->name('classes.assessments.import');
    Route::post('/classes/{class}/assessments/import', [AssessmentController::class, 'copyAssessments'])->name('classes.assessments.copy');
    Route::get('/classes/{class}/assessments/{assessment}', [AssessmentController::class, 'show'])->name('classes.assessments.show');
    Route::get('/classes/{class}/assessments/{assessment}/edit', [AssessmentController::class, 'edit'])->name('classes.assessments.edit');
    Route::put('/classes/{class}/assessments/{assessment}', [AssessmentController::class, 'update'])->name('classes.assessments.update');
    Route::get('/classes/{class}/assessments/{assessment}/submissions', [AssessmentController::class, 'submissions'])->name('classes.assessments.submissions');
    Route::post('/classes/{class}/assessments/{assessment}/grade/{submission}', [AssessmentController::class, 'grade'])->name('classes.assessments.grade');
    Route::delete('/classes/{class}/assessments/{assessment}', [AssessmentController::class, 'destroy'])->name('classes.assessments.destroy');

    // Quizzes
    Route::get('/classes/{class}/quizzes', [QuizController::class, 'index'])->name('classes.quizzes.index');
    Route::get('/classes/{class}/quizzes/create', [QuizController::class, 'create'])->name('classes.quizzes.create');
    Route::post('/classes/{class}/quizzes', [QuizController::class, 'store'])->name('classes.quizzes.store');
    Route::get('/classes/{class}/quizzes/import', [QuizController::class, 'import'])->name('classes.quizzes.import');
    Route::post('/classes/{class}/quizzes/import', [QuizController::class, 'copyQuizzes'])->name('classes.quizzes.copy');
    Route::get('/classes/{class}/quizzes/{quiz}', [QuizController::class, 'show'])->name('classes.quizzes.show');
    Route::get('/classes/{class}/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('classes.quizzes.edit');
    Route::put('/classes/{class}/quizzes/{quiz}', [QuizController::class, 'update'])->name('classes.quizzes.update');
    Route::delete('/classes/{class}/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('classes.quizzes.destroy');
    Route::get('/classes/{class}/quizzes/{quiz}/submissions', [QuizController::class, 'submissions'])->name('classes.quizzes.submissions');

    // Leaderboard
    Route::get('/classes/{class}/leaderboard', [ClassroomController::class, 'leaderboard'])->name('classes.leaderboard');

    // Analytics
    Route::get('/classes/{class}/analytics', [ClassroomController::class, 'analytics'])->name('classes.analytics');

    // Sensors CRUD
    Route::get('/sensors', [AdminSensorController::class, 'index'])->name('sensors.index');
    Route::get('/sensors/create', [AdminSensorController::class, 'create'])->name('sensors.create');
    Route::post('/sensors', [AdminSensorController::class, 'store'])->name('sensors.store');
    Route::get('/sensors/{sensor}/edit', [AdminSensorController::class, 'edit'])->name('sensors.edit');
    Route::put('/sensors/{sensor}', [AdminSensorController::class, 'update'])->name('sensors.update');
    Route::delete('/sensors/{sensor}', [AdminSensorController::class, 'destroy'])->name('sensors.destroy');

    // Projects CRUD
    Route::get('/projects', [AdminProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [AdminProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [AdminProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}/edit', [AdminProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [AdminProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [AdminProjectController::class, 'destroy'])->name('projects.destroy');

    // Products CRUD
    Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Videos CRUD
    Route::get('/videos', [AdminVideoController::class, 'index'])->name('videos.index');
    Route::get('/videos/create', [AdminVideoController::class, 'create'])->name('videos.create');
    Route::post('/videos', [AdminVideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}/edit', [AdminVideoController::class, 'edit'])->name('videos.edit');
    Route::put('/videos/{video}', [AdminVideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [AdminVideoController::class, 'destroy'])->name('videos.destroy');

    // Suggestions Management
    Route::get('/suggestions', [AdminSuggestionController::class, 'index'])->name('suggestions.index');
    Route::get('/suggestions/{suggestion}', [AdminSuggestionController::class, 'show'])->name('suggestions.show');
    Route::put('/suggestions/{suggestion}/status', [AdminSuggestionController::class, 'updateStatus'])->name('suggestions.status');

    // Comment routes (instructor)
    Route::post('/suggestions/{suggestion}/comment', [AdminSuggestionController::class, 'storeComment'])->name('suggestions.comment.store');
    Route::put('/suggestions/{suggestion}/comment/{comment}', [AdminSuggestionController::class, 'updateComment'])->name('suggestions.comment.update');
});

// ─── Administrator Routes ───────────────────────────────────────────────────────
Route::middleware(['auth', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
   
    // Analytics
    Route::get('/analytics', [SuperAdminDashboardController::class, 'analytics'])->name('analytics');

    // Profile
    Route::get('/profile', [SuperAdminProfileController::class, 'show'])->name('profile');
    Route::match(['put', 'post'], '/profile/update', [SuperAdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [SuperAdminProfileController::class, 'updatePassword'])->name('profile.password');

    // Activity Logs
    Route::get('/logs', [SuperAdminDashboardController::class, 'logs'])->name('logs');

    // Database Backup 
    Route::get('/backup', function () {
        return view('super-admin.backup');
    })->name('backup');
    Route::get('/backup/download', [SuperAdminDashboardController::class, 'backup'])->name('backup.download');
    Route::get('/backup/download/{filename}', [SuperAdminDashboardController::class, 'downloadBackup'])->name('backup.download-file');
    Route::delete('/backup/delete/{filename}', [SuperAdminDashboardController::class, 'deleteBackup'])->name('backup.delete');

    // Users CRUD
    Route::get('/users', [SuperAdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [SuperAdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [SuperAdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [SuperAdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [SuperAdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [SuperAdminUserController::class, 'update'])->name('users.update');
    Route::put('/users/{user}/role', [SuperAdminUserController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [SuperAdminUserController::class, 'destroy'])->name('users.destroy');

    // Suggestions Management
    Route::get('/suggestions', [SuperAdminSuggestionController::class, 'index'])->name('suggestions.index');
    Route::get('/suggestions/{suggestion}', [SuperAdminSuggestionController::class, 'show'])->name('suggestions.show');
    Route::put('/suggestions/{suggestion}/status', [SuperAdminSuggestionController::class, 'updateStatus'])->name('suggestions.status');

    // Comment routes (administrator)
    Route::post('/suggestions/{suggestion}/comment', [SuperAdminSuggestionController::class, 'storeComment'])->name('suggestions.comment.store');
    Route::put('/suggestions/{suggestion}/comment/{comment}', [SuperAdminSuggestionController::class, 'updateComment'])->name('suggestions.comment.update');

    // Content
    Route::get('/sensors', [SuperAdminContentController::class, 'sensors'])->name('sensors.index');
    Route::get('/projects', [SuperAdminContentController::class, 'projects'])->name('projects.index');
    Route::get('/products', [SuperAdminContentController::class, 'products'])->name('products.index');
    Route::get('/videos', [SuperAdminContentController::class, 'videos'])->name('videos.index');
    Route::get('/{type}/create', [SuperAdminContentController::class, 'create'])
        ->whereIn('type', ['sensors', 'projects', 'products', 'videos'])
        ->name('content.create');
    Route::post('/{type}', [SuperAdminContentController::class, 'store'])
        ->whereIn('type', ['sensors', 'projects', 'products', 'videos'])
        ->name('content.store');
    Route::get('/{type}/{id}/edit', [SuperAdminContentController::class, 'edit'])
        ->whereIn('type', ['sensors', 'projects', 'products', 'videos'])
        ->name('content.edit');
    Route::put('/{type}/{id}', [SuperAdminContentController::class, 'update'])
        ->whereIn('type', ['sensors', 'projects', 'products', 'videos'])
        ->name('content.update');
    Route::delete('/{type}/{id}', [SuperAdminContentController::class, 'destroy'])
        ->whereIn('type', ['sensors', 'projects', 'products', 'videos'])
        ->name('content.destroy');
});