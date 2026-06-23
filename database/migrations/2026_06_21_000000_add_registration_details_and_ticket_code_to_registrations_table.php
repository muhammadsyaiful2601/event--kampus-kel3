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
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('participant_name')->nullable()->after('event_id');
            // `team_name` was added in an earlier migration (2026_06_19_032949),
            // avoid adding it again to prevent duplicate column errors.
            $table->string('department')->nullable()->after('participant_name');
            $table->string('year')->nullable()->after('department');
            $table->unsignedTinyInteger('age')->nullable()->after('year');
            $table->string('participant_photo')->nullable()->after('age');
            $table->string('ticket_code')->unique()->after('participant_photo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'participant_name',
                // 'team_name' intentionally omitted because it's dropped in an earlier migration
                'department',
                'year',
                'age',
                'participant_photo',
                'ticket_code',
            ]);
        });
    }
};
