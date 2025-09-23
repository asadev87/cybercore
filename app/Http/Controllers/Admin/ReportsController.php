<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;     // dompdf
use Maatwebsite\Excel\Facades\Excel; // laravel-excel
use App\Exports\ParticipantScoresExport;
use Illuminate\Support\Carbon;


class ReportsController extends Controller
{
    public function index(Request $request)
{
    $request->validate([
        'from'      => ['nullable','date'],
        'to'        => ['nullable','date','after_or_equal:from'],
        'module_id' => ['nullable','exists:modules,id'],
    ]);

    // Safe parsing with defaults
    $from = $request->filled('from')
        ? Carbon::parse($request->input('from'))->startOfDay()
        : now()->startOfMonth()->startOfDay();

    $to = $request->filled('to')
        ? Carbon::parse($request->input('to'))->endOfDay()
        : now()->endOfDay();

    $moduleId = $request->integer('module_id') ?: null;

    // Base query for the date range (optional module filter)
    $q = QuizAttempt::query()
        ->when($moduleId, fn($qq) => $qq->where('module_id', $moduleId))
        ->whereBetween('quiz_attempts.created_at', [$from, $to]);

    // Summaries / ranking / rows (use PHP clone, not ->clone())
    $summary = (clone $q)
        ->join('modules','modules.id','=','quiz_attempts.module_id')
        ->selectRaw('COUNT(*) as attempts,
                     AVG(score) as avg_score,
                     SUM(CASE WHEN score >= modules.pass_score THEN 1 ELSE 0 END) as passes')
        ->first();

    $ranking = (clone $q)
        ->join('modules','modules.id','=','quiz_attempts.module_id')
        ->selectRaw('modules.id, modules.title,
                     COUNT(*) as attempts,
                     AVG(score) as avg_score,
                     SUM(CASE WHEN score >= modules.pass_score THEN 1 ELSE 0 END) as passes')
        ->groupBy('modules.id','modules.title')
        ->orderByDesc('avg_score')
        ->limit(50)
        ->get();

    $rows = (clone $q)
        ->with(['user:id,name,email','module:id,title,pass_score'])
        ->whereNotNull('completed_at')
        ->latest('completed_at')
        ->limit(50)
        ->get();

    $modules = Module::orderBy('title')->get(['id','title']);

    return view('admin.reports.index', compact('modules','from','to','moduleId','summary','ranking','rows'));
}


   public function exportExcel(Request $request)
{
    $request->validate([
        'from'      => ['nullable','date'],
        'to'        => ['nullable','date','after_or_equal:from'],
        'module_id' => ['nullable','exists:modules,id'],
    ]);

    $from = $request->filled('from')
        ? Carbon::parse($request->input('from'))->startOfDay()
        : now()->startOfMonth()->startOfDay();

    $to = $request->filled('to')
        ? Carbon::parse($request->input('to'))->endOfDay()
        : now()->endOfDay();

    $mod  = $request->integer('module_id') ?: null;

    $name = 'participant-scores'
          . ($mod ? "-module{$mod}" : '')
          . '-'.now()->format('Ymd_His').'.xlsx';

    return Excel::download(new ParticipantScoresExport($from, $to, $mod), $name);
}

public function exportPdf(Request $request)
{
    $request->validate([
        'from'      => ['nullable','date'],
        'to'        => ['nullable','date','after_or_equal:from'],
        'module_id' => ['nullable','exists:modules,id'],
    ]);

    $from = $request->filled('from')
        ? Carbon::parse($request->input('from'))->startOfDay()
        : now()->startOfMonth()->startOfDay();

    $to = $request->filled('to')
        ? Carbon::parse($request->input('to'))->endOfDay()
        : now()->endOfDay();

    $moduleId = $request->integer('module_id') ?: null;

    $rows = QuizAttempt::query()
        ->when($moduleId, fn($q) => $q->where('module_id', $moduleId))
        ->whereBetween('quiz_attempts.created_at', [$from, $to])
        ->with(['user:id,name,email','module:id,title,pass_score'])
        ->whereNotNull('completed_at')
        ->latest('completed_at')
        ->limit(200)
        ->get();

    $pdf = Pdf::loadView('admin.reports.pdf', [
        'from'=>$from,'to'=>$to,'rows'=>$rows,'moduleId'=>$moduleId
    ]);

    return $pdf->download('participant-scores-'.now()->format('Ymd_His').'.pdf');
}

}
