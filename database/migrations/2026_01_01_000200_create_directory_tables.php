<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->unique();
            $table->timestamps();
        });

        Schema::create('barangays', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->unique();
            $table->timestamps();
        });

        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('barangay_id')->constrained('barangays')->restrictOnDelete();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('phone', 20);
            $table->string('messenger_link', 150)->nullable();
            $table->string('hours', 100)->nullable();
            $table->string('address', 150)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('photo_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->string('admin_note')->nullable();
            $table->timestamps();

            $table->index(['status', 'category_id', 'barangay_id']);
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->string('reviewer_name', 60);
            $table->unsignedTinyInteger('rating');
            $table->string('comment', 255)->nullable();
            $table->enum('status', ['visible', 'hidden'])->default('visible');
            $table->timestamps();
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->string('reason', 100);
            $table->string('details', 255)->nullable();
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->enum('action', ['view', 'call', 'text', 'map', 'messenger', 'share']);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['business_id', 'action']);
        });

        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barangay_id')->nullable()->constrained('barangays')->nullOnDelete();
            $table->string('name', 80);
            $table->string('number', 20);
            $table->string('type', 40)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_contacts');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('businesses');
        Schema::dropIfExists('barangays');
        Schema::dropIfExists('categories');
    }
};
