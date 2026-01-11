<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add invitation columns
            $table->string('invitation_token')->nullable()->unique();
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamp('invitation_accepted_at')->nullable();
            $table->timestamp('invitation_expires_at')->nullable();
            $table->string('invitation_status')->default('none'); // none, pending, accepted, expired, cancelled
            $table->foreignId('invited_by')->nullable()->constrained('users', 'userID')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'invitation_token',
                'invitation_sent_at',
                'invitation_accepted_at',
                'invitation_expires_at',
                'invitation_status',
                'invited_by'
            ]);
        });
    }
};