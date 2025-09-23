<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Imports\QuestionsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function questions(Request $request, Module $module)
    {
        $this->authorize('update', $module); // optional, or use role:admin on route group

        // Validate file: CSV or Excel (max ~2MB here; adjust as needed) :contentReference[oaicite:3]{index=3}
        $data = $request->validate([
            'questions_file' => ['required','file','mimes:csv,txt,xlsx,xls','max:2048']
        ]);

        $import = new QuestionsImport($module->id);
        Excel::import($import, $data['questions_file']); // basic import API :contentReference[oaicite:4]{index=4}

        // Report back failures (row-level)
        $failures = $import->failures(); // from SkipsFailures
        return back()->with([
            'import_ok' => $failures->isEmpty(),
            'import_failures' => $failures
        ]);
    }

    public function template(\App\Models\Module $module)
{
    $filename = 'questions-template-'.$module->slug.'.csv';

    return response()->streamDownload(function () {
        // Send UTF-8 BOM so Excel recognizes encoding
        echo "\xEF\xBB\xBF";  // BOM (Excel-friendly)  :contentReference[oaicite:1]{index=1}

        $out = fopen('php://output', 'w');

        // Headings expected by your importer
        fputcsv($out, ['type','stem','options','answer','explanation','difficulty','is_active']); // :contentReference[oaicite:2]{index=2}

        // A few example rows (authors can overwrite)
        fputcsv($out, [
            'mcq',
            'Which is a common sign of a phishing email?',
            'Generic greeting || Proper corporate domain || No links || Encrypted attachment from IT',
            'Generic greeting',
            'Phish often use generic greetings like “Dear user”.',
            1,
            'true'
        ]);

        fputcsv($out, [
            'truefalse',
            'Shortened URLs can hide the real destination and should be treated with caution.',
            '',
            'true',
            'Shorteners can mask malicious destinations; preview or avoid.',
            1,
            'true'
        ]);

        fputcsv($out, [
            'fib',
            'Type the browser feature that blocks malicious sites: _______ browsing.',
            '',
            'safe || safebrowsing || safe browsing',
            '“Safe Browsing” lists are used by modern browsers.',
            2,
            'true'
        ]);

        fclose($out);
    }, $filename, [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        'Cache-Control'       => 'no-store, no-cache, must-revalidate',
    ]);
}

}
