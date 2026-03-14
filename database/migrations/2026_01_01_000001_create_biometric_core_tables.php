<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->string('address');
            $table->string('timezone')->default('America/Bogota');
            $table->timestamps();
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->string('ip_address');
            $table->unsignedInteger('port')->default(4370);
            $table->string('device_password')->nullable();
            $table->string('api_key')->unique()->nullable();
            $table->timestamp('last_sync')->nullable();
            $table->string('status')->default('offline');
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('document_number')->unique();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->string('fingerprint_id')->unique();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('device_id')->constrained()->restrictOnDelete();
            $table->foreignId('branch_id')->constrained()->restrictOnDelete();
            $table->timestamp('check_time');
            $table->enum('type', ['checkin', 'checkout']);
            $table->json('raw_log')->nullable();
            $table->timestamps();
            $table->unique(['employee_id', 'device_id', 'check_time']);
        });

        Schema::create('device_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->string('user_id');
            $table->timestamp('log_time');
            $table->string('status')->default('ok');
            $table->json('raw_data')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['device_id', 'log_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_logs');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('branches');
    }
};
