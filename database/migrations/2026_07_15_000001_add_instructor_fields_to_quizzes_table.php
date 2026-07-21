<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table): void {
            // Siapa instructor yang membuat quiz ini
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('course_id');

            // Status alur review: draft → pending_review → published | rejected
            $table->enum('status', ['draft', 'pending_review', 'published', 'rejected'])->default('draft')->after('is_published');

            // Catatan penolakan dari admin (jika rejected)
            $table->text('rejection_note')->nullable()->after('status');
        });

        // Migrasi data lama: jika is_published = true → status = published, else → status = draft
        DB::statement("UPDATE quizzes SET status = CASE WHEN is_published = 1 THEN 'published' ELSE 'draft' END");
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn(['status', 'rejection_note']);
        });
    }
};
