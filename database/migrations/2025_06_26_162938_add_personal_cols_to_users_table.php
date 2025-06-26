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
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('website');
            $table->date('dob')->nullable()->after('gender');
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade')->after('dob');
            $table->boolean('status')->default(1)->after('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('gender');
            $table->dropColumn('dob');
            $table->dropColumn('school_id');
            $table->dropColumn('status');
        });
    }
};
