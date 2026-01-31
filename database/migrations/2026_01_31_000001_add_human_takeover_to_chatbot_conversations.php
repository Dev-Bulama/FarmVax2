<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chatbot_conversations', function (Blueprint $table) {
            $table->boolean('human_requested')->default(false)->after('status');
            $table->timestamp('human_requested_at')->nullable()->after('human_requested');
            $table->boolean('human_takeover')->default(false)->after('human_requested_at');
            $table->timestamp('human_takeover_at')->nullable()->after('human_takeover');
            $table->unsignedBigInteger('handled_by_admin_id')->nullable()->after('human_takeover_at');
            $table->boolean('notification_sent')->default(false)->after('handled_by_admin_id');

            $table->foreign('handled_by_admin_id')->references('id')->on('users')->onDelete('set null');
        });

        // Add 'admin' sender type to chatbot_messages
        Schema::table('chatbot_messages', function (Blueprint $table) {
            // Drop the old enum constraint and recreate with 'admin' option
            $table->dropColumn('sender_type');
        });

        Schema::table('chatbot_messages', function (Blueprint $table) {
            $table->enum('sender_type', ['user', 'bot', 'admin'])->default('user')->after('message');
        });
    }

    public function down(): void
    {
        Schema::table('chatbot_messages', function (Blueprint $table) {
            $table->dropColumn('sender_type');
        });

        Schema::table('chatbot_messages', function (Blueprint $table) {
            $table->enum('sender_type', ['user', 'bot'])->default('user')->after('message');
        });

        Schema::table('chatbot_conversations', function (Blueprint $table) {
            $table->dropForeign(['handled_by_admin_id']);
            $table->dropColumn([
                'human_requested',
                'human_requested_at',
                'human_takeover',
                'human_takeover_at',
                'handled_by_admin_id',
                'notification_sent'
            ]);
        });
    }
};
