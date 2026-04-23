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
        // Update chat_conversations to support all user types
        if (Schema::hasTable('chat_conversations')) {
            Schema::table('chat_conversations', function (Blueprint $table) {
                if (Schema::hasColumn('chat_conversations', 'admin_id')) {
                    $table->dropForeign(['admin_id']);
                    $table->dropColumn('admin_id');
                }
                if (!Schema::hasColumn('chat_conversations', 'participants')) {
                    $table->json('participants')->after('user_id');
                }
                if (!Schema::hasColumn('chat_conversations', 'conversation_type')) {
                    $table->string('conversation_type')->default('direct')->after('participants');
                }
                if (!Schema::hasColumn('chat_conversations', 'title')) {
                    $table->string('title')->nullable()->after('subject');
                }
            });
        }

        // Enhance chat_messages to support multimedia
        if (Schema::hasTable('chat_messages')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                if (!Schema::hasColumn('chat_messages', 'message_type')) {
                    $table->enum('message_type', ['text', 'image', 'video', 'voice', 'file', 'emoji'])->default('text')->after('message');
                }
                if (!Schema::hasColumn('chat_messages', 'file_url')) {
                    $table->string('file_url')->nullable()->after('attachment');
                }
                if (!Schema::hasColumn('chat_messages', 'file_type')) {
                    $table->string('file_type')->nullable()->after('file_url');
                }
                if (!Schema::hasColumn('chat_messages', 'file_size')) {
                    $table->bigInteger('file_size')->nullable()->after('file_type');
                }
                if (!Schema::hasColumn('chat_messages', 'duration')) {
                    $table->integer('duration')->nullable()->after('file_size');
                }
                if (!Schema::hasColumn('chat_messages', 'metadata')) {
                    $table->json('metadata')->nullable()->after('duration');
                }
            });
        }

        // Create chat_participants table for better participant management
        if (!Schema::hasTable('chat_participants')) {
            Schema::create('chat_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->constrained('chat_conversations')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamp('joined_at')->nullable();
                $table->timestamp('left_at')->nullable();
                $table->boolean('is_admin')->default(false);
                $table->boolean('notifications_enabled')->default(true);
                $table->timestamp('last_read_at')->nullable();
                $table->timestamps();

                $table->unique(['conversation_id', 'user_id']);
            });
        }

        // Create message reactions table
        if (!Schema::hasTable('chat_message_reactions')) {
            Schema::create('chat_message_reactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('message_id')->constrained('chat_messages')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('emoji');
                $table->timestamps();

                $table->unique(['message_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_message_reactions');
        Schema::dropIfExists('chat_participants');

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['message_type', 'file_url', 'file_type', 'file_size', 'duration', 'metadata']);
        });

        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->dropColumn(['participants', 'conversation_type', 'title']);
            $table->foreignId('admin_id')->nullable()->after('user_id')->constrained('users')->onDelete('set null');
        });
    }
};
