<?php

namespace App\Helpers;

use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AdminActivityLogger
{
    /**
     * Record an admin action to the immutable log.
     *
     * @param  string      $action       Dot-notation action, e.g. 'event.created'
     * @param  string      $description  Human-readable description
     * @param  string|null $subjectType  Model class, e.g. 'Event'
     * @param  int|null    $subjectId    ID of the target record
     */
    public static function log(
        string $action,
        string $description,
        ?string $subjectType = null,
        ?int $subjectId = null
    ): void {
        $user = Auth::user();

        AdminLog::create([
            'admin_id'     => $user?->id,
            'admin_name'   => $user?->name ?? 'System',
            'action'       => $action,
            'description'  => $description,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'ip_address'   => Request::ip(),
        ]);
    }
}
