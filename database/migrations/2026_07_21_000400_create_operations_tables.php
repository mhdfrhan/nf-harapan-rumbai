<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('attachable_type', 50)->nullable();
            $table->unsignedBigInteger('attachable_id')->nullable();
            $table->string('category', 40);
            $table->string('original_name', 255);
            $table->string('storage_disk', 40)->default('private');
            $table->string('storage_path', 255)->unique();
            $table->string('detected_mime', 100);
            $table->string('extension', 10);
            $table->unsignedBigInteger('size_bytes');
            $table->char('sha256', 64)->index();
            $table->foreignId('uploaded_by')->constrained('users')->restrictOnDelete();
            $table->string('scanned_status', 20)->default('not_available');
            $table->timestamps();
            $table->softDeletes();
            $table->index(['attachable_type', 'attachable_id']);
        });

        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->timestamp('occurred_at')->index();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event', 100)->index();
            $table->string('auditable_type', 50)->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->uuid('request_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent_summary', 255)->nullable();
            $table->json('before_json')->nullable();
            $table->json('after_json')->nullable();
            $table->json('metadata_json')->nullable();
            $table->index(['auditable_type', 'auditable_id']);
        });

        Schema::create('idempotency_keys', function (Blueprint $table): void {
            $table->id();
            $table->uuid('key');
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('action', 80);
            $table->char('request_hash', 64);
            $table->string('result_type', 50)->nullable();
            $table->unsignedBigInteger('result_id')->nullable();
            $table->string('status', 20)->default('processing');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'action', 'key']);
        });

        Schema::create('business_sequences', function (Blueprint $table): void {
            $table->id();
            $table->string('type', 20);
            $table->date('sequence_date');
            $table->unsignedInteger('last_value')->default(0);
            $table->timestamps();
            $table->unique(['type', 'sequence_date']);
        });

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key', 100)->unique();
            $table->json('value_json')->nullable();
            $table->string('type', 20)->default('string');
            $table->boolean('is_sensitive')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('business_sequences');
        Schema::dropIfExists('idempotency_keys');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('documents');
    }
};
