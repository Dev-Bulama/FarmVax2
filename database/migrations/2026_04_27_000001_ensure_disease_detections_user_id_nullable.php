<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Make user_id nullable on disease_detections so guest/public scans work.
     * Handles both MySQL/MariaDB (production) and SQLite (local dev).
     */
    public function up(): void
    {
        if (!Schema::hasTable('disease_detections')) {
            return; // Fresh install — original migration already creates it nullable
        }

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $this->fixSqlite();
        } else {
            $this->fixMysql();
        }
    }

    private function fixMysql(): void
    {
        $rows = DB::select("
            SELECT IS_NULLABLE
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME   = 'disease_detections'
              AND COLUMN_NAME  = 'user_id'
        ");

        if (empty($rows) || $rows[0]->IS_NULLABLE === 'YES') {
            return; // Already nullable — nothing to do
        }

        Schema::table('disease_detections', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    private function fixSqlite(): void
    {
        $cols      = DB::select('PRAGMA table_info(disease_detections)');
        $userIdCol = collect($cols)->firstWhere('name', 'user_id');

        if (!$userIdCol || $userIdCol->notnull == 0) {
            return; // Already nullable — nothing to do
        }

        // SQLite can't ALTER COLUMN directly; rebuild via rename/recreate.
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement("CREATE TABLE disease_detections_new (
            id                   integer      NOT NULL PRIMARY KEY AUTOINCREMENT,
            user_id              integer      NULL,
            livestock_id         integer      NULL,
            image_path           varchar(255) NOT NULL,
            animal_type          varchar(50)  NULL,
            symptoms_reported    varchar(1000) NULL,
            status               varchar(255) NOT NULL DEFAULT 'processing',
            is_sick              tinyint(1)   NULL,
            confidence_score     decimal(5,2) NULL,
            analysis_result      text         NULL,
            detected_conditions  text         NULL,
            recommendations      text         NULL,
            urgency_level        varchar(20)  NULL,
            ai_model             varchar(80)  NULL,
            created_at           datetime     NULL,
            updated_at           datetime     NULL,
            deleted_at           datetime     NULL
        )");

        DB::statement('INSERT INTO disease_detections_new SELECT * FROM disease_detections');
        DB::statement('DROP TABLE disease_detections');
        DB::statement('ALTER TABLE disease_detections_new RENAME TO disease_detections');
        DB::statement('CREATE INDEX disease_detections_user_id_created_at_index
                        ON disease_detections (user_id, created_at)');

        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        // Leaving user_id nullable is always safe — no destructive rollback
    }
};
