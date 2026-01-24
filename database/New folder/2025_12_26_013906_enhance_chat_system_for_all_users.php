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
        Schema::table('chat_conversations', function (Blueprint $table) {
            // Remove admin_id and make it more flexible
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');

            // Add participants as JSON to support multiple users in a conversation
            $table->json('participants')->after('user_id'); // Array of user IDs
            $table->string('conversation_type')->default('direct')->after('participants'); // direct, group
            $table->string('title')->nullable()->after('subject'); // For group chats
        });

        // Enhance chat_messages to support multimedia
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->enum('message_type', ['text', 'image', 'video', 'voice', 'file', 'emoji'])->default('text')->after('message');
            $table->string('file_url')->nullable()->after('attachment'); // For media files
            $table->string('file_type')->nullable()->after('file_url'); // mime type
            $table->bigInteger('file_size')->nullable()->after('file_type'); // in bytes
            $table->integer('duration')->nullable()->after('file_size'); // For voice/video in seconds
            $table->json('metadata')->nullable()->after('duration'); // Additional data (dimensions, thumbnails, etc.)
        });

        // Create chat_participants table for better participant management
        Schema::create('chat_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('chat_conversations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->boolean('is_admin')->default(false); // For group chat admins
            $table->boolean('notifications_enabled')->default(true);
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();

            // Prevent duplicate participants
            $table->unique(['conversation_id', 'user_id']);
        });

        // Create message reactions table
        Schema::create('chat_message_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('chat_messages')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('emoji'); // The emoji reaction
            $table->timestamps();

            // One reaction per user per message
            $table->unique(['message_id', 'user_id']);
        });
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
