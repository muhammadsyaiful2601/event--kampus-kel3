<?php

namespace App\Http\Controllers;

use App\Models\AdminLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLogController extends Controller
{
    /**
     * Display the immutable admin activity log.
     * Read-only — no store / update / destroy methods exposed.
     */
    public function index(Request $request)
    {
        if (strtolower(Auth::user()->role) !== 'admin') {
            abort(403);
        }

        $query = AdminLog::latest('created_at');

        // Filter by action category
        if ($request->filled('action') && $request->input('action') !== 'semua') {
            $query->where('action', 'like', $request->input('action') . '%');
        }

        // Filter by admin
        if ($request->filled('admin_id') && $request->input('admin_id') !== 'semua') {
            $query->where('admin_id', $request->input('admin_id'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $logs  = $query->paginate(25)->withQueryString();
        $admins = User::where('role', 'admin')->get();

        // Distinct action prefixes for filter dropdown
        $actionCategories = [
            'semua'        => 'Semua Tindakan',
            'event'        => '📅 Event',
            'registration' => '📋 Pendaftaran',
            'admin'        => '👤 Admin',
        ];

        return view('dashboard_admin.logs.index', compact('logs', 'admins', 'actionCategories'));
    }
}
