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
        Schema::table('notifications', function (Blueprint $table) {
            // Verificar y agregar columnas faltantes solo si no existen
            if (!Schema::hasColumn('notifications', 'notification_type')) {
                $table->enum('notification_type', [
                    'attendance_pending',
                    'new_subject',
                    'schedule_change',
                    'direct_message',
                    'reservation_approved',
                    'reservation_rejected',
                    'reservation_permission'
                ])->after('user_id');
            }

            if (!Schema::hasColumn('notifications', 'priority')) {
                $table->enum('priority', ['info', 'important', 'urgent'])->default('info')->after('notification_type');
            }

            if (!Schema::hasColumn('notifications', 'is_automatic')) {
                $table->boolean('is_automatic')->default(true)->after('priority');
            }

            if (!Schema::hasColumn('notifications', 'data')) {
                $table->json('data')->nullable()->after('message');
            }

            // Agregar índices si no existen
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('notifications');
            
            if (!isset($indexesFound['notifications_user_id_read_at_index'])) {
                $table->index(['user_id', 'read_at']);
            }
            
            if (!isset($indexesFound['notifications_user_id_is_automatic_index'])) {
                $table->index(['user_id', 'is_automatic']);
            }
            
            if (!isset($indexesFound['notifications_notification_type_index'])) {
                $table->index('notification_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['notification_type', 'priority', 'is_automatic', 'data']);
            $table->dropIndex(['user_id', 'read_at']);
            $table->dropIndex(['user_id', 'is_automatic']);
            $table->dropIndex(['notification_type']);
        });
    }
};
