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

        // --- Quiz Management ---
        Route::resource('quizzes', AdminQuizController::class)
            ->middleware('role:'.User::ROLE_ADMIN);

        // --- Question Management (nested under quiz) ---
        Route::get('quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])
            ->name('quizzes.questions.create')
            ->middleware('role:'.User::ROLE_ADMIN);

        Route::post('quizzes/{quiz}/questions', [QuestionController::class, 'store'])
            ->name('quizzes.questions.store')
            ->middleware('role:'.User::ROLE_ADMIN);

        Route::get('quizzes/{quiz}/questions/{question}/edit', [QuestionController::class, 'edit'])
            ->name('quizzes.questions.edit')
            ->middleware('role:'.User::ROLE_ADMIN);

        Route::put('quizzes/{quiz}/questions/{question}', [QuestionController::class, 'update'])
            ->name('quizzes.questions.update')
            ->middleware('role:'.User::ROLE_ADMIN);

        Route::delete('quizzes/{quiz}/questions/{question}', [QuestionController::class, 'destroy'])
            ->name('quizzes.questions.destroy')
            ->middleware('role:'.User::ROLE_ADMIN);
    });

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
    });

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
