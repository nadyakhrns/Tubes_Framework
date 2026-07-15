<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InstructorController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Instructor\LessonController;
use App\Http\Controllers\Instructor\QuizController as InstructorQuizController;
use App\Http\Controllers\Instructor\QuestionController as InstructorQuestionController;
use App\Http\Controllers\Instructor\SectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\LearningController;
use App\Http\Controllers\Student\MyCourseController;
use App\Http\Controllers\Student\QuizController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

// Temporary route to fix missing slugs
Route::get('/fix-slugs', function () {
    \App\Models\Course::whereNull('slug')->orWhere('slug', '')->get()->each(function ($course) {
        $course->slug = \Illuminate\Support\Str::slug($course->title);
        $course->save();
    });
    return '✅ Slugs fixed successfully! You can now close this tab and refresh your Instructor/Admin panel.';
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route(auth()->user()->dashboardRouteName());
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function (): void {

    // ─── ADMIN ────────────────────────────────────────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->middleware('role:'.User::ROLE_ADMIN)
            ->name('dashboard');

        Route::resource('categories', CategoryController::class)
            ->middleware('role:'.User::ROLE_ADMIN);

        Route::resource('instructors', InstructorController::class)
            ->middleware('role:'.User::ROLE_ADMIN);

        Route::resource('courses', AdminCourseController::class)
            ->middleware('role:'.User::ROLE_ADMIN);

        Route::get('courses/{course}/approve', [AdminCourseController::class, 'approve'])
            ->name('courses.approve')
            ->middleware('role:'.User::ROLE_ADMIN);

        Route::get('courses/{course}/reject', [AdminCourseController::class, 'reject'])
            ->name('courses.reject')
            ->middleware('role:'.User::ROLE_ADMIN);

        Route::get('courses/{course}/publish', [AdminCourseController::class, 'publish'])
            ->name('courses.publish')
            ->middleware('role:'.User::ROLE_ADMIN);

        Route::get('courses/{course}/unpublish', [AdminCourseController::class, 'unpublish'])
            ->name('courses.unpublish')
            ->middleware('role:'.User::ROLE_ADMIN);

        // --- Quiz Management (Admin: Review & Publish only) ---
        Route::middleware('role:'.User::ROLE_ADMIN)->group(function (): void {
            Route::get('quizzes',                   [AdminQuizController::class, 'index'])->name('quizzes.index');
            Route::get('quizzes/{quiz}',             [AdminQuizController::class, 'show'])->name('quizzes.show');
            Route::patch('quizzes/{quiz}/publish',   [AdminQuizController::class, 'publish'])->name('quizzes.publish');
            Route::patch('quizzes/{quiz}/unpublish', [AdminQuizController::class, 'unpublish'])->name('quizzes.unpublish');
            Route::patch('quizzes/{quiz}/reject',    [AdminQuizController::class, 'reject'])->name('quizzes.reject');
            Route::delete('quizzes/{quiz}',          [AdminQuizController::class, 'destroy'])->name('quizzes.destroy');
        });
    });

    // ─── INSTRUCTOR ───────────────────────────────────────────────────────────
    Route::prefix('instructor')->name('instructor.')->group(function (): void {
        Route::get('/dashboard', [InstructorDashboardController::class, 'index'])
            ->middleware('role:'.User::ROLE_INSTRUCTOR)
            ->name('dashboard');

        Route::resource('courses', InstructorCourseController::class)
            ->middleware('role:'.User::ROLE_INSTRUCTOR);

        Route::resource('courses.sections', SectionController::class)
            ->middleware('role:'.User::ROLE_INSTRUCTOR);

        Route::resource('courses.lessons', LessonController::class)
            ->middleware('role:'.User::ROLE_INSTRUCTOR);

        // --- Quiz Management (Instructor: CRUD + Submit for Review) ---
        Route::middleware('role:'.User::ROLE_INSTRUCTOR)->group(function (): void {
            Route::resource('courses.quizzes', InstructorQuizController::class);

            // Submit for review
            Route::patch(
                'courses/{course}/quizzes/{quiz}/submit-review',
                [InstructorQuizController::class, 'submitForReview']
            )->name('courses.quizzes.submit-review');

            // Question management (nested under quiz)
            Route::get(
                'courses/{course}/quizzes/{quiz}/questions/create',
                [InstructorQuestionController::class, 'create']
            )->name('courses.quizzes.questions.create');

            Route::post(
                'courses/{course}/quizzes/{quiz}/questions',
                [InstructorQuestionController::class, 'store']
            )->name('courses.quizzes.questions.store');

            Route::get(
                'courses/{course}/quizzes/{quiz}/questions/{question}/edit',
                [InstructorQuestionController::class, 'edit']
            )->name('courses.quizzes.questions.edit');

            Route::put(
                'courses/{course}/quizzes/{quiz}/questions/{question}',
                [InstructorQuestionController::class, 'update']
            )->name('courses.quizzes.questions.update');

            Route::delete(
                'courses/{course}/quizzes/{quiz}/questions/{question}',
                [InstructorQuestionController::class, 'destroy']
            )->name('courses.quizzes.questions.destroy');
        });
    });

    // ─── STUDENT ──────────────────────────────────────────────────────────────
    Route::prefix('student')->name('student.')->group(function (): void {
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])
            ->middleware('role:'.User::ROLE_STUDENT)
            ->name('dashboard');

        Route::get('/courses', [StudentCourseController::class, 'index'])
            ->middleware('role:'.User::ROLE_STUDENT)
            ->name('courses.index');

        Route::get('/courses/{course}', [StudentCourseController::class, 'show'])
            ->middleware('role:'.User::ROLE_STUDENT)
            ->name('courses.show');

        Route::post('/courses/{course}/enroll', [StudentCourseController::class, 'enroll'])
            ->middleware('role:'.User::ROLE_STUDENT)
            ->name('courses.enroll');

        Route::get('/my-courses', [MyCourseController::class, 'index'])
            ->middleware('role:'.User::ROLE_STUDENT)
            ->name('my-courses');

        Route::get('/enrollments/{enrollment}/lessons/{lesson}', [LearningController::class, 'show'])
            ->middleware('role:'.User::ROLE_STUDENT)
            ->name('learning.show');

        Route::post('/enrollments/{enrollment}/lessons/{lesson}/complete', [LearningController::class, 'complete'])
            ->middleware('role:'.User::ROLE_STUDENT)
            ->name('learning.complete');

        Route::get('/enrollments/{enrollment}/quizzes/{quiz}', [QuizController::class, 'show'])
            ->middleware('role:'.User::ROLE_STUDENT)
            ->name('quiz.show');

        Route::post('/enrollments/{enrollment}/quizzes/{quiz}/submit', [QuizController::class, 'submit'])
            ->middleware('role:'.User::ROLE_STUDENT)
            ->name('quiz.submit');

        Route::get('/enrollments/{enrollment}/quizzes/{quiz}/result', [QuizController::class, 'result'])
            ->middleware('role:'.User::ROLE_STUDENT)
            ->name('quiz.result');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
