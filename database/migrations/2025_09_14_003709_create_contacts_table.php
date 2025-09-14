<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')
                  ->constrained('applications')
                  ->cascadeOnDelete();
            $table->string('name')->nullable();                // 問い合わせ者名（任意）
            $table->string('category');                        // カテゴリ（必須）
            $table->string('title')->nullable();               // タイトル（任意）
            $table->unsignedTinyInteger('rating')->nullable(); // 1-5（任意）
            $table->text('detail');                            // 詳細（必須）
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['application_id', 'created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('contacts');
    }
};
