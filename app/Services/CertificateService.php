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

    $serial = $this->generateCertificateNumber($module);
    $issuedAt = now();
    $score = (int)$attempt->score;

    // 1) Render PDF (view -> bytes)
    $pdf = Pdf::loadView('certificates.template', compact('user','module','score') + [
      'issued_at'=>$issuedAt, 'serial'=>$serial
    ])->setPaper([0, 0, 595.28, 841.89], "portrait");

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
      'user_id'         => $user->id,
      'module_id'       => $module->id,
      'quiz_attempt_id' => $attempt->id,
      'serial'          => $serial,
      'code'            => $serial,
      'issued_at'       => $issuedAt,
      'pdf_path'        => $path,
    ]);
  }

  public function regenerateCertificate(Certificate $certificate): Certificate
  {
    $module = $certificate->module;
    $user   = $certificate->user;

    if (!$module || !$user) {
      return $certificate;
    }

    $serial   = $this->generateCertificateNumber($module);
    $issuedAt = $certificate->issued_at ?? now();
    $score    = (int) optional($certificate->attempt)->score;

    $pdf = Pdf::loadView('certificates.template', [
      'user'      => $user,
      'module'    => $module,
      'score'     => $score,
      'issued_at' => $issuedAt,
      'serial'    => $serial,
    ])->setPaper([0, 0, 595.28, 841.89], "portrait");

    $dom  = $pdf->getDomPDF();
    $dom->render();
    $cpdf = $dom->getCanvas()->get_cpdf();
    $cpdf->setEncryption('', Str::random(16), ['print','copy','modify']);
    $bytes = $dom->output();

    if ($certificate->pdf_path && Storage::disk('local')->exists($certificate->pdf_path)) {
      Storage::disk('local')->delete($certificate->pdf_path);
    }

    $path = 'certificates/'.$serial.'.pdf';
    Storage::disk('local')->put($path, $bytes);

    $certificate->fill([
      'serial'   => $serial,
      'code'     => $serial,
      'pdf_path' => $path,
    ])->save();

    return $certificate;
  }

  protected function generateCertificateNumber(Module $module): string
  {
    $slug = $module->slug ?: Str::slug($module->title, '-');
    $segments = array_values(array_filter(explode('-', $slug)));
    $abbr = '';
    foreach (array_slice($segments, 0, 3) as $segment) {
      $abbr .= Str::upper(Str::substr($segment, 0, 2));
    }
    if ($abbr === '') {
      $abbr = 'MOD';
    }
    $abbr = Str::substr($abbr, 0, 6);

    $moduleIdPart = $module->id
      ? Str::upper(str_pad(base_convert((string) $module->id, 10, 36), 2, '0', STR_PAD_LEFT))
      : '00';

    $moduleToken = $abbr . $moduleIdPart;

    do {
      $candidate = sprintf('CERT-%s-%s-%s',
        $moduleToken,
        now()->format('Ymd'),
        Str::upper(Str::random(3))
      );
    } while (Certificate::where('serial', $candidate)->exists());

    return $candidate;
  }
}
