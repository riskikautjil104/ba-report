<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\BaStatus;
use App\Models\BeritaAcara;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the application dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $baseQuery = BeritaAcara::forUser($user);

        $totalBa = (clone $baseQuery)->count();
        $inProgress = (clone $baseQuery)->whereIn('status', [BaStatus::DalamPenanganan, BaStatus::Tertunda])->count();
        $waitingSign = (clone $baseQuery)->where('status', BaStatus::MenungguTandaTangan)->count();
        $completed = (clone $baseQuery)->whereIn('status', [BaStatus::Selesai, BaStatus::Diarsipkan])->count();

        $recentBas = (clone $baseQuery)
            ->with(['category', 'reporter'])
            ->latest('tanggal')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', [
            'user' => $user,
            'totalBa' => $totalBa,
            'inProgress' => $inProgress,
            'waitingSign' => $waitingSign,
            'completed' => $completed,
            'recentBas' => $recentBas,
        ]);
    }
}
