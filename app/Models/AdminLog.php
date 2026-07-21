<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    // No updated_at — logs are immutable
    const UPDATED_AT = null;

    protected $fillable = [
        'admin_id',
        'admin_name',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * The admin who performed the action.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the badge colour class for display based on action prefix.
     */
    public function getActionColorAttribute(): string
    {
        $prefix = explode('.', $this->action)[0] ?? '';

        return match ($prefix) {
            'event'        => 'bg-label-primary',
            'registration' => 'bg-label-success',
            'admin'        => 'bg-label-warning',
            'scan'         => 'bg-label-info',
            'attendance'   => 'bg-label-warning',
            default        => 'bg-label-secondary',
        };
    }

    /**
     * Human-readable label for an action string.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'event.created'            => '📅 Event Dibuat',
            'event.updated'            => '✏️ Event Diperbarui',
            'event.deleted'            => '🗑️ Event Dihapus',
            'registration.verified'    => '✅ Pendaftaran Diterima',
            'registration.rejected'    => '❌ Pendaftaran Ditolak',
            'registration.updated'     => '✏️ Pendaftaran Diperbarui',
            'registration.deleted'     => '🗑️ Pendaftaran Dihapus',
            'registration.scan_verified' => '📷 Tiket Scan Diverifikasi',
            'admin.created'            => '👤 Admin Ditambahkan',
            'admin.deleted'            => '🗑️ Admin Dihapus',
            'attendance.opened'        => '🔓 Sesi Absen Dibuka',
            'attendance.closed'        => '🔒 Sesi Absen Ditutup',
            default                    => $this->action,
        };
    }
}
