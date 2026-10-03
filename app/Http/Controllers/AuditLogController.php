<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs.
     */
    public function index(Request $request): View
    {
        $query = AuditLog::with(['user', 'beritaAcara']);

        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('action', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search): void {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    })
                    ->orWhereHas('beritaAcara', function ($bq) use ($search): void {
                        $bq->where('nomor', 'like', "%{$search}%");
                    });
            });
        }

        $auditLogs = $query->latest('created_at')->paginate(20)->withQueryString();

        return view('audit-logs.index', [
            'auditLogs' => $auditLogs,
        ]);
    }
}
