<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── chatbot_conversations: add human-takeover columns if missing ───────
        Schema::table('chatbot_conversations', function (Blueprint $table) {
            if (!Schema::hasColumn('chatbot_conversations', 'human_requested')) {
                $table->boolean('human_requested')->default(false)->after('status');
            }
            if (!Schema::hasColumn('chatbot_conversations', 'human_requested_at')) {
                $table->timestamp('human_requested_at')->nullable();
            }
            if (!Schema::hasColumn('chatbot_conversations', 'human_takeover')) {
                $table->boolean('human_takeover')->default(false);
            }
            if (!Schema::hasColumn('chatbot_conversations', 'human_takeover_at')) {
                $table->timestamp('human_takeover_at')->nullable();
            }
            if (!Schema::hasColumn('chatbot_conversations', 'handled_by_admin_id')) {
                $table->unsignedBigInteger('handled_by_admin_id')->nullable();
            }
            if (!Schema::hasColumn('chatbot_conversations', 'notification_sent')) {
                $table->boolean('notification_sent')->default(false);
            }
        });

        // ── chatbot_messages: ensure sender_type column exists with 'admin' ───
        // The 2025_12_26_000009 migration used 'sender' (already includes 'admin').
        // The 2025_12_28_193916 migration used 'sender_type' without 'admin'.
        // Only drop-and-recreate sender_type if it exists AND lacks 'admin'.
        if (Schema::hasColumn('chatbot_messages', 'sender_type')) {
            Schema::table('chatbot_messages', function (Blueprint $table) {
                $table->dropColumn('sender_type');
            });
            Schema::table('chatbot_messages', function (Blueprint $table) {
                $table->enum('sender_type', ['user', 'bot', 'admin'])->default('user')->after('message');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('chatbot_messages', 'sender_type')) {
            Schema::table('chatbot_messages', function (Blueprint $table) {
                $table->dropColumn('sender_type');
            });
            Schema::table('chatbot_messages', function (Blueprint $table) {
                $table->enum('sender_type', ['user', 'bot'])->default('user')->after('message');
            });
        }

        Schema::table('chatbot_conversations', function (Blueprint $table) {
            $cols = ['human_requested', 'human_requested_at', 'human_takeover',
                     'human_takeover_at', 'handled_by_admin_id', 'notification_sent'];
            $toDrop = array_filter($cols, fn($c) => Schema::hasColumn('chatbot_conversations', $c));
            if ($toDrop) {
                $table->dropColumn(array_values($toDrop));
            }
        });
    }
};
