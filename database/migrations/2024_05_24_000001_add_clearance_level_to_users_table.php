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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('clearance_level')->default(1)->after('email_verified_at');
            $table->string('timezone')->default('UTC')->after('remember_token');
            $table->text('two_factor_secret')->nullable()->after('timezone');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_secret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'clearance_level',
                'timezone',
                'two_factor_secret',
                'two_factor_confirmed_at',
            ]);
        });
    }
};
