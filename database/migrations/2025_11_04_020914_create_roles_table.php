<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('guard_name')->default('web');
                $table->string('label')->nullable(); // optional display name
                $table->timestamps();
            });
        } else {
            Schema::table('roles', function (Blueprint $table) {
                if (! Schema::hasColumn('roles', 'label')) {
                    $table->string('label')->nullable()->after('name');
                }

                if (! Schema::hasColumn('roles', 'guard_name')) {
                    $table->string('guard_name')->default('web')->after('name');
                }
            });
        }

        if (! Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('role_id')
                    ->nullable()
                    ->after('id')                // move if you prefer another spot
                    ->constrained('roles')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropConstrainedForeignId('role_id');
            });
        }

        if (Schema::hasTable('roles')) {
            if (! Schema::hasColumn('roles', 'guard_name')) {
                Schema::dropIfExists('roles');
            } elseif (Schema::hasColumn('roles', 'label')) {
                Schema::table('roles', function (Blueprint $table) {
                    $table->dropColumn('label');
                });
            }
        }
    }
};
