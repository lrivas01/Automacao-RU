<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    protected $connection = 'pgsql';

    public function up(): void {
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->id();
                $table->string('ra')->unique();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('course');
                $table->string('password');
                $table->boolean('is_active')->default(true);
                $table->integer('tokens_available')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void {
        Schema::dropIfExists('students');
    }
};