# E-Learning Platform

A Laravel-based e-learning application with role-based access for administrators, instructors, and students. The platform supports course management, lesson delivery, quizzes, enrollments, and student progress tracking.

## Features

- Admin moderation for categories, instructors, and course approval
- Instructor course, section, and lesson management
- Student browsing, enrollment, lesson learning, and quiz submissions
- Progress tracking for completed lessons
- Role-based routes and authorization guards

## Tech Stack

- Laravel 13
- Blade templates with Bootstrap 5 styling
- MySQL for data persistence
- PHPUnit for feature testing

## Local Setup

1. Clone the repository and install dependencies:
   ```bash
   composer install
   npm install
   ```
2. Copy the environment file and configure your database:
   ```bash
   cp .env.example .env
   ```
3. Generate an application key:
   ```bash
   php artisan key:generate
   ```
4. Run the database migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
5. Start the development server:
   ```bash
   php artisan serve
   ```

## Testing

Run the authorization regression tests with:

```bash
php artisan test --filter=AuthorizationTest
```

## User Roles

- Admin: manage categories, instructors, and course approvals
- Instructor: create and manage their own courses, sections, and lessons
- Student: browse published courses, enroll, study lessons, and complete quizzes
