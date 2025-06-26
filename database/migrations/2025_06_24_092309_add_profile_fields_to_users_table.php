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
            $table->string('designation')->nullable()->after('profile_image');
            $table->text('address')->nullable()->after('designation');
            $table->text('about')->nullable()->after('address');
            $table->string('cover_image')->nullable()->after('about');
            $table->string('facebook')->nullable()->after('cover_image');
            $table->string('twitter')->nullable()->after('facebook');
            $table->string('instagram')->nullable()->after('twitter');
            $table->string('whatsapp')->nullable()->after('instagram');
            $table->string('youtube')->nullable()->after('whatsapp');
            $table->string('threads')->nullable()->after('youtube');
            $table->string('website')->nullable()->after('threads');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'designation',
                'address',
                'about',
                'cover_image',
                'facebook',
                'twitter',
                'instagram',
                'whatsapp',
                'youtube',
                'threads',
                'website',
            ]);
        });
    }
};
