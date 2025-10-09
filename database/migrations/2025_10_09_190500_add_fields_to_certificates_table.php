<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('certificates', 'serial')) {
                $table->string('serial')->nullable()->after('quiz_attempt_id');
            }

            if (!Schema::hasColumn('certificates', 'issued_at')) {
                $table->timestamp('issued_at')->nullable()->after('serial');
            }

            if (!Schema::hasColumn('certificates', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('issued_at');
            }

            if (!Schema::hasColumn('certificates', 'revoked')) {
                $table->boolean('revoked')->default(false)->after('pdf_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            if (Schema::hasColumn('certificates', 'revoked')) {
                $table->dropColumn('revoked');
            }
            if (Schema::hasColumn('certificates', 'pdf_path')) {
                $table->dropColumn('pdf_path');
            }
            if (Schema::hasColumn('certificates', 'issued_at')) {
                $table->dropColumn('issued_at');
            }
            if (Schema::hasColumn('certificates', 'serial')) {
                $table->dropColumn('serial');
            }
        });
    }
};
