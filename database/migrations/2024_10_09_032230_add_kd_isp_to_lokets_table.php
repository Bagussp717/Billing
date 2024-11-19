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
        Schema::table('lokets', function (Blueprint $table) {
            $table->unsignedBigInteger('kd_isp')->nullable()->after('jml_komisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lokets', function (Blueprint $table) {
            $table->dropColumn('kd_isp');
        });
    }
};