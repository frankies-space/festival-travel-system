<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('available_discount', 8, 2)->default(0)->after('points_balance');
            $table->timestamp('vip_until')->nullable()->after('available_discount');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['available_discount', 'vip_until']);
        });
    }
};
