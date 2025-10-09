<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\{Certificate, QuizAttempt, Module};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateService {
  public function issueForAttempt(QuizAttempt $attempt): Certificate {
    $user   = $attempt->user;
    $module = $attempt->module;

    // idempotent: one cert per passed attempt
    $existing = Certificate::where('quiz_attempt_id',$attempt->id)->first();
    if ($existing) return $existing;

    $serial = strtoupper(Str::random(10));
    $issuedAt = now();
    $score = (int)$attempt->score;

    // 1) Render PDF (view -> bytes)
    $pdf = Pdf::loadView('certificates.template', compact('user','module','score') + [
      'issued_at'=>$issuedAt, 'serial'=>$serial
    ])->setPaper('a4','landscape');

    // 2) (Optional) set PDF permissions: discourage print/copy
    //    Dompdf exposes CPDF -> setEncryption(userPwd, ownerPwd, disallow-list)
    //    NOTE: Readers can ignore this; it’s advisory.
    $dom = $pdf->getDomPDF();
    $dom->render();
    $cpdf = $dom->getCanvas()->get_cpdf();
    // disallow printing & copy/edit (owner password random)
    $cpdf->setEncryption('', Str::random(16), ['print','copy','modify']);  // best-effort only
    // stream/save after render
    $bytes = $dom->output();

    // 3) Store
    $path = 'certificates/'.$serial.'.pdf';
    Storage::disk('local')->put($path, $bytes);   // storage/app/...
    // (Laravel filesystem docs for storing files.) :contentReference[oaicite:1]{index=1}

    // 4) Create record
    return Certificate::create([
      'user_id'        => $user->id,
      'module_id'      => $module->id,
      'quiz_attempt_id'=> $attempt->id,
      'serial'         => $serial,
      'code'           => $serial,
      'issued_at'      => $issuedAt,
      'pdf_path'       => $path,
    ]);
  }
}
