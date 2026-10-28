<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_email_verifications', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('code_hash');
            $table->timestamp('expires_at');
            $table->timestamp('last_sent_at');
            $table->timestamp('window_started_at');
            $table->unsignedTinyInteger('send_count')->default(0);
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_email_verifications');
    }
};
