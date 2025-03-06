<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            if (!Schema::hasColumn('activities', 'module')) {
                $table->string('module')->after('user_id');
            }
            if (!Schema::hasColumn('activities', 'action')) {
                $table->string('action')->after('module');
            }
            if (!Schema::hasColumn('activities', 'description')) {
                $table->text('description')->after('action');
            }
            if (!Schema::hasColumn('activities', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('description');
            }
            if (!Schema::hasColumn('activities', 'user_agent')) {
                $table->string('user_agent')->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('activities', 'comment_id')) {
                $table->unsignedBigInteger('comment_id')->nullable()->after('user_agent');
            }
            if (!Schema::hasColumn('activities', 'field')) {
                $table->string('field')->nullable()->after('comment_id');
            }
            if (!Schema::hasColumn('activities', 'old_value')) {
                $table->text('old_value')->nullable()->after('field');
            }
            if (!Schema::hasColumn('activities', 'new_value')) {
                $table->text('new_value')->nullable()->after('old_value');
            }
            if (!Schema::hasColumn('activities', 'old_values')) {
                $table->json('old_values')->nullable()->after('new_value');
            }
            if (!Schema::hasColumn('activities', 'new_values')) {
                $table->json('new_values')->nullable()->after('old_values');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn([
                'module',
                'action',
                'description',
                'ip_address',
                'user_agent',
                'comment_id',
                'field',
                'old_value',
                'new_value',
                'old_values',
                'new_values'
            ]);
        });
    }
}; 