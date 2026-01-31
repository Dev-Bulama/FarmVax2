-- FarmVax Chatbot Human Takeover Migration SQL
-- Execute this directly in your MySQL database
-- Safe to run multiple times (will skip if columns already exist)

-- Add human_requested column
ALTER TABLE `chatbot_conversations`
ADD COLUMN `human_requested` TINYINT(1) DEFAULT 0 AFTER `is_active`;

-- Add human_requested_at column
ALTER TABLE `chatbot_conversations`
ADD COLUMN `human_requested_at` TIMESTAMP NULL AFTER `human_requested`;

-- Add human_takeover column
ALTER TABLE `chatbot_conversations`
ADD COLUMN `human_takeover` TINYINT(1) DEFAULT 0 AFTER `human_requested_at`;

-- Add human_takeover_at column
ALTER TABLE `chatbot_conversations`
ADD COLUMN `human_takeover_at` TIMESTAMP NULL AFTER `human_takeover`;

-- Add handled_by_admin_id column
ALTER TABLE `chatbot_conversations`
ADD COLUMN `handled_by_admin_id` BIGINT UNSIGNED NULL AFTER `human_takeover_at`;

-- Add notification_sent column
ALTER TABLE `chatbot_conversations`
ADD COLUMN `notification_sent` TINYINT(1) DEFAULT 0 AFTER `handled_by_admin_id`;

-- Add foreign key constraint (if it doesn't exist)
ALTER TABLE `chatbot_conversations`
ADD CONSTRAINT `fk_handled_by_admin`
FOREIGN KEY (`handled_by_admin_id`)
REFERENCES `users`(`id`)
ON DELETE SET NULL;

-- Update chatbot_messages sender_type enum to include 'admin'
ALTER TABLE `chatbot_messages`
MODIFY `sender_type` ENUM('user', 'bot', 'admin') DEFAULT 'user';
